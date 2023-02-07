<?php

namespace App\Events;

use App\Element;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ElementCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $uuid;
    public $model;

    /**
     * ElementCreatedEvent constructor.
     * @param $uuid
     * @param $model
     */
    public function __construct($model, $uuid)
    {
        $this->model = $model;
        $this->uuid = $uuid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
