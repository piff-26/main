<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TicketCheckedIn implements ShouldBroadcastNow
{
    public function __construct(
        public string $ticket_code,
        public string $category_name,
        public string $event_name,
        public string $holder_name,
        public string $checked_in_by,
        public string $checked_in_at,
        public int    $total_checked_in,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('checkin-monitor');
    }

    public function broadcastAs(): string
    {
        return 'ticket.checked-in';
    }
}
