<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function values(): array
    {
        return [
            self::ADMIN,
            self::USER,
        ];
    }
}
