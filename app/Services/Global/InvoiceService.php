<?php

namespace App\Services\Global;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function orderPdf(Order $order)
    {
        $previousLocale = app()->getLocale();

        try {
            app()->setLocale('en');

            $order->load([
                'itemsOrder.design.images',
                'itemsOrder.measurement',
                'itemsOrder.itemsSelected.designOption',
                'user',
                'coupon',
                'address',
                'payment',
            ]);

            return Pdf::loadView('orders.invoice', compact('order'))
                ->setPaper('a4');
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
