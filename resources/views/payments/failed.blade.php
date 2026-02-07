@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Payment Failed') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.rtl')
</head>

<body class="min-h-screen bg-gradient-to-br from-red-50 via-white to-rose-50 flex items-center justify-center p-6"
    dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="w-full max-w-lg bg-white shadow-xl rounded-2xl p-8 border border-gray-100 text-center">
        <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
            <i class="fas fa-times text-red-600 text-2xl"></i>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Payment Failed') }}</h1>
        <p class="text-sm text-gray-600 mb-6">
            {{ __('Your payment could not be completed. Please try again.') }}
        </p>

        @if (isset($order))
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-left text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">{{ __('Order') }}</span>
                    <span class="font-semibold text-gray-800">#{{ $order->num ?? $order->id }}</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-gray-500">{{ __('Amount') }}</span>
                    <span class="font-semibold text-gray-800">
                        ${{ number_format($order->payment->amount ?? 0, 2) }}
                    </span>
                </div>
            </div>
        @endif

        <div class="mt-6 space-y-3">
            <a href="{{ route('dashboard') }}"
                class="inline-block w-full px-4 py-2 rounded-lg bg-gray-900 text-white font-semibold hover:bg-black transition">
                {{ __('Back to Dashboard') }}
            </a>
            @if (isset($order))
                <a href="{{ route('orders.show', $order->id) }}"
                    class="inline-block w-full px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition">
                    {{ __('View Order') }}
                </a>
            @endif
        </div>
    </div>

</body>

</html>
