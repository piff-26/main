<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id']; // Memperbolehkan semua field diisi massal kecuali ID
    protected $casts = ['paid_at' => 'datetime', 'agree_tnc' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function voucher() { return $this->belongsTo(Voucher::class); }
    public function transactionItems() { return $this->hasMany(TransactionItem::class); }
    public function tickets() { return $this->hasMany(Ticket::class); }
}
