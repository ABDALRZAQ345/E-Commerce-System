<?php

namespace App\Enums;

enum OrderStatusEnum
{
    const Processing = 'processing';

    const Shipped = 'shipped';

    const Delivered = 'delivered';

    // Add other status as needed

    public static function getAllStatus(): array
    {
        return [
            self::Processing,
            self::Shipped,
            self::Delivered,
            // Add other status as needed
        ];
    }
}
