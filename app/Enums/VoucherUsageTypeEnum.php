<?php

namespace App\Enums;

enum VoucherUsageTypeEnum: string
{
    case OFFLINE_ONLY = 'offline_only';
    case ONLINE_ONLY  = 'online_only';
    case ALL          = 'all';
}
