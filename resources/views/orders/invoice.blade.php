@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('orders.invoice') }} #{{ $order->num ?? $order->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111827; font-size: 12px; }
        .header { border-bottom: 2px solid #6d28d9; padding-bottom: 12px; margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: bold; color: #4c1d95; }
        .muted { color: #6b7280; }
        .row { width: 100%; }
        .col { display: inline-block; vertical-align: top; }
        .col-50 { width: 49%; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 12px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; }
        .badge-pending { background: #fef9c3; color: #854d0e; }
        .badge-confirmed { background: #dbeafe; color: #1e40af; }
        .badge-delivered { background: #dcfce7; color: #166534; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 6px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f5f3ff; font-weight: 700; }
        .text-right { text-align: right; }
        .totals td { border: none; }
        .totals .label { color: #6b7280; }
        .grand { font-size: 14px; font-weight: 700; color: #4c1d95; }

        html[dir="rtl"] body {
            direction: rtl;
            text-align: right;
        }

        html[dir="rtl"] .row {
            direction: rtl;
        }

        html[dir="rtl"] th,
        html[dir="rtl"] td {
            text-align: right;
        }
    </style>
    @include('partials.rtl')
</head>

<body dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="header">
        <div class="row">
            <div class="col col-50">
                <div class="title">{{ __('Kandoura Store') }}</div>
                <div class="muted">{{ __('orders.invoice') }}</div>
            </div>
            <div class="col col-50" style="text-align:right;">
                <div><strong>{{ __('orders.order') }} #{{ $order->num ?? $order->id }}</strong></div>
                <div class="muted">{{ __('orders.date') }}: {{ $order->created_at->format('d M, Y') }}</div>
                @php
                    $statusClass = [
                        'pending' => 'badge-pending',
                        'confirmed' => 'badge-confirmed',
                        'delivered' => 'badge-delivered',
                        'cancelled' => 'badge-cancelled',
                    ][$order->status] ?? 'badge-pending';
                @endphp
                <span class="badge {{ $statusClass }}">{{ __(ucfirst($order->status)) }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col col-50">
            <div class="card">
                <div><strong>{{ __('orders.customer') }}</strong></div>
                <div>{{ $order->user->name ?? __('N/A') }}</div>
                <div class="muted">{{ $order->user->email ?? __('N/A') }}</div>
                <div class="muted">{{ $order->user->phone ?? __('N/A') }}</div>
            </div>
        </div>
        <div class="col col-50">
            <div class="card">
                <div><strong>{{ __('orders.payment') }}</strong></div>
                <div class="muted">{{ __('orders.method') }}: {{ $order->payment->method ?? __('N/A') }}</div>
                <div class="muted">{{ __('orders.status') }}: {{ __(ucfirst($order->payment->status ?? 'pending')) }}</div>
                @if (isset($order->payment->num))
                    <div class="muted">{{ __('orders.transaction_id') }}: {{ $order->payment->num }}</div>
                @endif
            </div>
        </div>
    </div>

    @if ($order->address)
        <div class="card">
            <div><strong>{{ __('orders.delivery_address') }}</strong></div>
            <div>{{ $order->address->city->name ?? __('N/A') }}</div>
            <div class="muted">{{ $order->address->street }}</div>
            @if ($order->address->details)
                <div class="muted">{{ $order->address->details }}</div>
            @endif
        </div>
    @endif

    <div class="card">
        <div style="margin-bottom:8px;"><strong>{{ __('orders.order_items') }}</strong></div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('orders.design') }}</th>
                    <th>{{ __('orders.details') }}</th>
                    <th class="text-right">{{ __('orders.price') }}</th>
                    <th class="text-right">{{ __('orders.qty') }}</th>
                    <th class="text-right">{{ __('orders.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->itemsOrder as $item)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if ($item->design && $item->design->images && $item->design->images->first())
                                    @php
                                        $imageUrl = $item->design->images->first()->fullUrl;
                                        $imageFile = $imageUrl ? public_path(ltrim($imageUrl, '/')) : null;
                                    @endphp
                                    @if ($imageFile && file_exists($imageFile))
                                        <img src="{{ $imageFile }}"
                                            alt="{{ $item->design->name ?? __('Design') }}"
                                            style="width:36px; height:36px; border-radius:6px; object-fit:cover; border:1px solid #e5e7eb;">
                                    @endif
                                @endif
                                <div>
                                    <div style="font-weight:700;">{{ $item->design->name ?? __('N/A') }}</div>
                                    <div class="muted" style="font-size:11px;">#{{ $item->design->id ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="muted">
                            <div><strong>{{ __('orders.size') }}:</strong> {{ $item->measurement->size ?? __('N/A') }}</div>
                            <div><strong>{{ __('orders.color') }}:</strong> {{ $item->color ?? __('N/A') }}</div>
                            <div><strong>{{ __('orders.dome') }}:</strong> {{ $item->dome ?? __('N/A') }}</div>
                            <div><strong>{{ __('orders.sleeve') }}:</strong> {{ $item->sleeve ?? __('N/A') }}</div>
                            <div><strong>{{ __('orders.fabric') }}:</strong> {{ $item->fabric ?? __('N/A') }}</div>
                        </td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <table class="totals" style="width:100%; margin-top: 8px;">
        <tr>
            <td class="label text-right" style="width:80%;">{{ __('orders.subtotal') }}</td>
            <td class="text-right" style="width:20%;">${{ number_format($order->subtotal ?? 0, 2) }}</td>
        </tr>
        @if ($order->coupon)
            <tr>
                <td class="label text-right">{{ __('orders.coupon_code') }}</td>
                <td class="text-right">{{ $order->coupon->code }}</td>
            </tr>
        @endif
        <tr>
            <td class="label text-right">{{ __('orders.discount') }}</td>
            <td class="text-right">-${{ number_format($order->discount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="label text-right grand">{{ __('orders.total') }}</td>
            <td class="text-right grand">${{ number_format(($order->subtotal ?? 0) - ($order->discount ?? 0), 2) }}</td>
        </tr>
    </table>
</body>

</html>
