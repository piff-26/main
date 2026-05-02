<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasUuids;
    protected $fillable = ['type', 'status', 'message', 'reference', 'context'];
    protected $casts = ['context' => 'array'];

    public static function info(string $type, string $message, ?string $reference = null, array $context = []): void
    {
        static::create(compact('type', 'message', 'reference', 'context') + ['status' => 'info']);
    }

    public static function success(string $type, string $message, ?string $reference = null, array $context = []): void
    {
        static::create(compact('type', 'message', 'reference', 'context') + ['status' => 'success']);
    }

    public static function fail(string $type, string $message, ?string $reference = null, array $context = []): void
    {
        static::create(compact('type', 'message', 'reference', 'context') + ['status' => 'failed']);
    }
}
