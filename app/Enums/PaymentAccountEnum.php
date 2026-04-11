<?php

namespace App\Enums;

enum PaymentAccountEnum
{
    case BCA;
    case MANDIRI;

    public function accountNumber(): string
    {
        return '10391313';
    }

    public function label(): string
    {
        return match($this) {
            self::BCA     => 'BCA',
            self::MANDIRI => 'Mandiri',
        };
    }

    public function accountName(): string
    {
        return 'PIFF 2026';
    }
}
