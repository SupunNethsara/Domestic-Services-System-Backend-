<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Chat implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $chatData;

    public function __construct($chatData)
    {
        $this->chatData = $chatData;
    }

    public function broadcastOn()
    {
//        return new PrivateChannel('chat.'.$this->chatData['receiver_id']);
        new PrivateChannel('chat.user.'.$this->chatData['sender_id']);
        new PrivateChannel('chat.user.'.$this->chatData['receiver_id']) ;
    }

    public function broadcastAs()
    {
        return 'message';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->chatData['id'],
            'message' => $this->chatData['message'],
            'sender_id' => $this->chatData['sender_id'],
            'timestamp' => $this->chatData['timestamp']
        ];
    }
}
