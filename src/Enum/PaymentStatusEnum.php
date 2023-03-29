<?php

namespace App\Enum;

enum PaymentStatusEnum: string
{
    case NEW = 'new';
    case PAID = 'paid';
}
