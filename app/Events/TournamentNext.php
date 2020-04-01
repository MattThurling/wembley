<?php

namespace App\Events;

use App\Round;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class TournamentNext implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $round;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Get the tournament_id from the round
        $tournament_id = $this->round->tournament_id;
        return new PresenceChannel($tournament_id);
    }
}
