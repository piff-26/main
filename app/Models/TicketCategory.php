<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCategory extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = ['event_id', 'name', 'slug', 'price', 'quota', 'sold_count'];

    public function event() {
        return $this->belongsTo(Event::class);
    }
    public function transactionItems() {
        return $this->hasMany(TransactionItem::class);
    }
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
