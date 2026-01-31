<?php

namespace App\Services\Web;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Payment::with(['user.image', 'order']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', $search)
                        ->orWhere('num', 'like', "%{$search}%")
                        ->orWhereHas('order', function ($orderQuery) use ($search) {
                            $orderQuery->where('num', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('method')) {
                $query->where('method', $request->method);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            $payments = $query->latest()->paginate(15)->withQueryString();

            $stats = [
                'total_payments' => Payment::count(),
                'confirmed_payments' => Payment::where('status', 'confirmed')->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),
                'failed_payments' => Payment::where('status', 'failed')->count(),
            ];

            return [
                'payments' => $payments,
                'stats' => $stats,
            ];
        });
    }
}
