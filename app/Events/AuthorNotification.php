<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthorNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private readonly int $authorId,
        private readonly string $message,
        private readonly array $meta = []
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('author_notification_' . $this->authorId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'author.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'meta' => $this->meta,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
