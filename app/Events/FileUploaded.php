<?php

namespace App\Events;

use App\Models\File;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class FileUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public File $file,
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $myUsernameKey = "WEB_USERNAME";
        $myUsername = env($myUsernameKey);
        if ($myUsername == null) {
            throw new RuntimeException("$myUsernameKey was not found in .env file.");
        }
        $myUser = User::where("username", $myUsername)->first();
        if ($myUser == null) {
            throw new RuntimeException("User with username $myUsername was not found in DB.");
        }
        return [
            new PrivateChannel('users.' . $myUser->id . '.files'),
        ];
    }
}
