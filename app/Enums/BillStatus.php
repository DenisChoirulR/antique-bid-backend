<?php

namespace App\Enums;

enum BillStatus: string
{
    case UNPAID = 'Unpaid';
    case PAID = 'Paid';
    case OVERDUE = 'Overdue';

    public static function values(): array
    {
        return [
            self::UNPAID,
            self::PAID,
            self::OVERDUE,
        ];
    }
}
