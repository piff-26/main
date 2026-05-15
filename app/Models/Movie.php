<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $guarded = [];

    // Relasi balik ke tiket
    public function onlineTickets()
    {
        return $this->belongsToMany(OnlineTicket::class, 'online_ticket_movie');
    }

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(MovieCategory::class, 'category_id');
    }
}
