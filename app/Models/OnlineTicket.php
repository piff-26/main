<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineTicket extends Model
{
    protected $guarded = [];

    // Relasi ke film (Many to Many)
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'online_ticket_movie');
    }
}
