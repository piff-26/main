<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = ['code', 'discount_type', 'discount_nominal', 'discount_percentage', 'event_id', 'ticket_category_id', 'max_uses', 'used_count', 'expired_at', 'status'];
    protected $casts = ['expired_at' => 'datetime'];

    public function event() { return $this->belongsTo(Event::class); }
    public function ticketCategory() { return $this->belongsTo(TicketCategory::class); }
}
