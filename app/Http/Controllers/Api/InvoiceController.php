<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Order;
use App\Services\Api\InvoiceService;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    //
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function invoice(Order $order)
    {
        try {
            $data = $this->invoiceService->invoice($order);
            if ($data === '1') {
                return $this->success(false, 'this order is not for you !', 401);
            }
            if (! $data) {
                return $this->success(false, 'Invoice not found.', 404);
            }

            return $this->success(InvoiceResource::make($data), 'Invoice retrived successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
