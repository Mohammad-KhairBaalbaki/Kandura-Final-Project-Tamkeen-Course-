<?php

namespace App\Enums;

enum PaymentMethodEnum
{
    //

    const WALLET = 'wallet';

    const STRIPE = 'stripe';

    const AFTER_DELIVERY = 'after_delivery';
}
