<?php

namespace App\Enums;

enum PaymentAccountEnum
{
    case BCA;
    // case PAYPAL;

    public function accountNumber(): string
    {
        return match($this) {
            self::BCA    => '1039131313',
            // self::PAYPAL => 'piff2026@gmail.com',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::BCA    => 'BCA (INDONESIA)',
            // self::PAYPAL => 'PayPal (INTERNATIONAL)',
        };
    }

    public function accountName(): string
    {
        return match($this) {
            self::BCA    => 'PIFF 2026',
            // self::PAYPAL => 'PIFF 2026',
        };
    }
}
