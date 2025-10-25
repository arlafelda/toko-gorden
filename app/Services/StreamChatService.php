<?php

namespace App\Services;

use GetStream\StreamChat\Client;
use Illuminate\Support\Facades\Log;

class StreamChatService
{
    private $client;

    public function __construct()
    {
        // Prefer config values but fallback to environment variables if config cache wasn't populated
        $key = config('services.stream.key') ?: env('STREAM_KEY') ?: env('STREAM_API_KEY');
        $secret = config('services.stream.secret') ?: env('STREAM_SECRET') ?: env('STREAM_API_SECRET');

        if (empty($key) || empty($secret)) {
            Log::error('Stream API key or secret not configured.');
            throw new \Exception('Stream API key or secret not configured. Please set STREAM_KEY and STREAM_SECRET (or STREAM_API_KEY / STREAM_API_SECRET) in your .env');
        }

        $this->client = new Client($key, $secret);
    }

    public function generateToken($userId)
    {
        return $this->client->createToken($userId);
    }

    public function createChannel($channelId, $members, $data = [])
    {
        $channel = $this->client->channel('messaging', $channelId);
        $channelData = array_merge([
            'members' => $members,
            'created_by_id' => $members[0]
        ], $data);
        
        $channel->create($members[0], $channelData);
        return $channel;
    }

    public function getChannel($channelId)
    {
        return $this->client->channel('messaging', $channelId);
    }

    public function sendMessage($channelId, $userId, $message)
    {
        $channel = $this->getChannel($channelId);
        return $channel->sendMessage([
            'text' => $message,
            'user_id' => $userId,
        ], $userId);
    }

    public function getChannelMessages($channelId, $limit = 50)
    {
        $channel = $this->getChannel($channelId);
        $response = $channel->query(['messages' => ['limit' => $limit]]);
        return $response['messages'] ?? [];
    }
}