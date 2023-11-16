<?php

namespace App\Enums;

enum OrderStatus: string
{

    case PENDING = 'Pending';
    case CANCELLED = 'Cancelled';
    case PAID = 'Paid';

    public  static function values()
    {

        return array_column(self::cases(), 'value');
    }
}
