<?php

namespace App\Services;

use App\Interfaces\IFirebaseService;
use App\Models\FCMToken;
use Google_Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FirebaseService implements IFirebaseService
{
    protected $client;
    protected $projectId;

    public function __construct()
    {
        $this->projectId = config('services.fcm.project_id');
        $credentialsFilePath = Storage::path(config('services.fcm.credentials'));

        $this->client = new Google_Client();
        $this->client->setAuthConfig($credentialsFilePath);
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    }

    public function sendNotification($fcmTokens, $payload = [])
    {
        // Fetch the access token with assertions
        $accessTokenArray = $this->client->fetchAccessTokenWithAssertion();

        // Check for token errors
        if (isset($accessTokenArray['error'])) {
            throw new \Exception("Error fetching access token: " . $accessTokenArray['error_description']);
        }

        $accessToken = $accessTokenArray['access_token'];

        $headers = [
            "Authorization" => "Bearer $accessToken",
            'Content-Type' => 'application/json'
        ];

        foreach ($fcmTokens as $fcmToken) {
            $data = [
                "message" => [
                    "token" => $fcmToken,
                    "data" => $payload,
                ]
            ];

            $response = Http::withHeaders($headers)->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", $data);

            if ($response->failed()) {
                Log::info('Notification failed for token ' . $fcmToken . ': ' . $response->body());
                FCMToken::where("token", $fcmToken)->delete();
            }
        }

        return true;
    }
}
