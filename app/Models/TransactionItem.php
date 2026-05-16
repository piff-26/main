<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['transaction_id', 'ticket_category_id', 'online_ticket_id', 'quantity', 'price', 'holder_names'];
    protected $casts = ['holder_names' => 'array'];

    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function ticketCategory() { return $this->belongsTo(TicketCategory::class); }
    public function onlineTicket() { return $this->belongsTo(OnlineTicket::class); }
}
