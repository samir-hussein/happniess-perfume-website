<?php

namespace App\Interfaces;

interface IFirebaseService
{
    /**
     * Send notification to FCM tokens
     *
     * @param array $fcmTokens
     * @param array $payload
     * @return bool
     */
    public function sendNotification($fcmTokens, $payload = []);
}
