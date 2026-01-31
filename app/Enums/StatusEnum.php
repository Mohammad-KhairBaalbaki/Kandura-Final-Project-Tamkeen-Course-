<?php

namespace App\Enums;

enum StatusEnum
{
    //
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';
    const FAILED = 'failed';

    const BLOCKED = 'blocked';
}
