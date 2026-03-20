<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'location', 'event_date', 'start_time', 'end_time', 'image', 'description', 'close_sell_time'];
    protected $casts = ['event_date' => 'date', 'start_time' => 'datetime:H:i', 'end_time' => 'datetime:H:i', 'close_sell_time' => 'datetime'];

    public function ticketCategories() {
        return $this->hasMany(TicketCategory::class);
    }
}
