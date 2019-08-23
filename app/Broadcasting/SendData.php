<?php

namespace App\Broadcasting;

use App\Models\AppUserModels\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\AppNotificationModels\Notification;

class SendData implements ShouldBroadcast
{

    use InteractsWithSockets;
   
    private $user;

    public $message;
/**
     * Create a new event instance.
     *
     * @param  User $user
     * @param  Notification $message
     * @return void
     */
    public function __construct(User $user, Notification $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        $username= $this->user->username;


        return new Channel("test.{$this->message}");
    }

}
