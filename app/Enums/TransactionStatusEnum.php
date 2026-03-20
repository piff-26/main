<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case DRAFT   = 'draft';
    case PENDING = 'pending';
    case PAID    = 'paid';
    case FAILED  = 'failed';
    case EXPIRED = 'expired';
}
