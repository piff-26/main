<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = ['name', 'slug', 'location', 'event_date', 'start_time', 'end_time', 'image', 'seat_map_image', 'description', 'tnc', 'close_sell_time', 'event_closed'];
    protected $casts = ['event_date' => 'date', 'start_time' => 'datetime:H:i', 'end_time' => 'datetime:H:i', 'close_sell_time' => 'datetime', 'event_closed' => 'datetime'];

    public function ticketCategories() {
        return $this->hasMany(TicketCategory::class);
    }

    /**
     * Returns true if the event_closed datetime is set and has already passed.
     */
    public function isClosed(): bool
    {
        return $this->event_closed !== null && now()->greaterThan($this->event_closed);
    }
}
