<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use Pusher\Pusher;

class PusherController extends Controller
{
    public function auth(Request $request)
    {
        $user = Auth::user();
        $channelName = $request->channel_name;
        $socketId = $request->socket_id;

        if (!$channelName || !$socketId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Channel name or socket id is missing',
            ], 422);
        }

        $chatId = explode('.', $channelName);

        if (count($chatId) !== 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid channel name',
            ], 400);
        }

        $chatId = $chatId[1];

        $chat = Chat::find($chatId);

        if (!$chat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chat not found',
            ], 404);
        }

        if ($user) {
            if ($chat->client_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 403);
            }
        } else {
            if ($chat->client_ip !== $request->ip()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 403);
            }
        }

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        return response(
            $pusher->authorizeChannel($channelName, $socketId)
        );
    }
}
