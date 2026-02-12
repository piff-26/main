<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Admin extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'nrp',
        'email',
        'division_id',
    ];

    public function relations()
    {
        return ['division'];
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
