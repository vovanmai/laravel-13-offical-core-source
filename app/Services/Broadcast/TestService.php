<?php

namespace App\Services\Broadcast;

use App\Events\TestReverbEvent;

class TestService
{
    public function execute(array $data): array
    {
        $message = $data['message'] ?? 'Hello from Reverb at ' . now()->toDateTimeString();

        event(new TestReverbEvent($message));

        return [
            'channel' => 'test-channel',
            'event'   => 'test.event',
            'message' => $message,
        ];
    }
}
