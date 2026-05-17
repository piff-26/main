<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOnlinePass extends Model
{
    protected $fillable = ['user_id', 'transaction_id', 'online_ticket_id', 'status'];

    protected $casts = [
        'status' => \App\Enums\UserOnlinePassStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function onlineTicket()
    {
        return $this->belongsTo(OnlineTicket::class);
    }
}
