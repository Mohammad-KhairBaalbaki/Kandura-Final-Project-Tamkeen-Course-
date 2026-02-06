<?php

namespace App\Services\Api;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\Global\InvoiceService as GlobalInvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Create a new class instance.
     */
    protected $invoiceService;

    public function __construct(GlobalInvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Store an invoice for a given order.
     *
     * @return Invoice
     */
    public function store(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if ($order->user_id !== Auth::id()) {
                return '1';
            }

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'num' => $order->num,
                'total' => $order->total,
            ]);

            return $invoice;
        });
    }

    /**
     * Generate a PDF invoice for an order and save it to storage/app/public/invoices
     *
     * @return Invoice
     */
    public function makePdf(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $num = $order->num;
            $path = 'invoices/order-'.$num.'-invoice.pdf';

            $order->load([
                'itemsOrder.design.images',
                'itemsOrder.measurement',
                'itemsOrder.itemsSelected.designOption',
                'user',
                'coupon',
                'address',
                'payment',
            ]);
            $invoice = $order->invoice;
            $pdf = $this->invoiceService->orderPdf($order);

            // ✅ save to storage/app/public/invoices/...
            Storage::disk('public')->put($path, $pdf->output());

            $total = $order->subtotal - $order->discount;
            $invoice->update([
                'total' => $total,
                'pdf_url' => $path,
            ]);

            return $invoice;
        });

    }

    /**
     * Retrieve an invoice for a given order.
     *
     * @return Invoice|null
     */
    public function invoice(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if ($order->user_id !== Auth::id()) {
                return '1';
            }

            return Invoice::where('order_id', $order->id)->first();
        });
    }
}
