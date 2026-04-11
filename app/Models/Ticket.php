<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['transaction_id', 'ticket_category_id', 'ticket_code', 'holder_name', 'is_checked_in', 'checked_in_at', 'checked_in_by', 'is_canceled', 'canceled_at'];
    protected $casts = ['checked_in_at' => 'datetime', 'canceled_at' => 'datetime', 'is_checked_in' => 'boolean', 'is_canceled' => 'boolean'];

    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function ticketCategory() { return $this->belongsTo(TicketCategory::class); }
    public function checker() { return $this->belongsTo(Admin::class, 'checked_in_by'); }
}
