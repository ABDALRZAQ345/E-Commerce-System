<?php

namespace App\Enums;

enum OrderStatusEnum
{
    const Pending = 'pending';

    const Canceled = 'canceled';

    const Delivered = 'delivered';

    // Add other status as needed

    public static function getAllStatus(): array
    {
        return [
            self::Pending,
            self::Canceled,
            self::Delivered,

        ];
    }
}
