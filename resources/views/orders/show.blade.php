@extends('layouts.dashboard')

@section('title', __('orders.order_details'))
@section('page-title', __('orders.order_details'))

@section('content')

@if (session('payment_failed'))
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">
        {{ session('payment_failed') }}
    </div>
@endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('orders.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ __('orders.back_to_orders') }}
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ __('orders.order') }} <span class="text-purple-600 hover:text-purple-700 font-bold">#{{ $order->num }}</span>
                </h2>
                <p class="text-sm text-gray-600">
                    {{ __('orders.placed_on') }} {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                </p>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-col items-start lg:items-end space-y-2">
                <div class="flex items-center space-x-3">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span
                    class="px-4 py-2 text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ __(ucfirst($order->status)) }}
                </span>
                <a href="{{ route('orders.invoice', $order->id) }}"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-file-pdf mr-2"></i>
                    {{ __('orders.download_pdf') }}
                </a>
                </div>
                @if ($order->status === \App\Enums\StatusEnum::DELIVERED && $order->review)
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-1 text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $order->review->rating ? '' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">({{ $order->review->rating }}/5)</span>
                        @if (!empty($order->review->comment))
                            <span class="text-sm text-gray-700">- {{ $order->review->comment }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Order Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                        {{ __('orders.payment_information') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('orders.method') }}</span>
                            <span class="font-semibold text-gray-800">{{ $order->payment->method ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('orders.status') }}</span>
                            @php
                                $paymentStatusColors = [
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $paymentStatusColors[$order->payment->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __(ucfirst($order->payment->status ?? 'pending')) }}
                            </span>
                        </div>
                        @if (isset($order->payment->num))
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('orders.transaction_id') }}</span>
                                <span class="font-mono text-gray-800 text-xs">{{ $order->payment->num }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-box text-purple-600 mr-2"></i>
                        {{ __('orders.order_items') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($order->itemsOrder as $item)
                            <a href="{{ route('designs.show', $item->design->id) }}"
                                class="flex items-start space-x-4 pb-4 border-b last:border-b-0 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-300 rounded-lg p-3 -m-3 cursor-pointer group hover:shadow-md hover:scale-[1.01] hover:border-purple-200">
                                <div
                                    class="w-20 h-20 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    <img src="{{ $item->design->images->first()->fullUrl }}" alt="Design image"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $item->design->name }}</h4>

                                    <div class="flex items-center space-x-10 mt-2 text-sm text-gray-600"
                                        style="margin-top: 20px ;">
                                        <span>{{ __('orders.size') }}: <span
                                                class="font-medium">{{ $item->measurement->size ?? 'N/A' }}</span></span>
                                        <span>{{ __('orders.color') }}: <span class="font-medium"
                                                style="color: {{ $item->color }};">{{ $item->color ?? 'N/A' }}</span></span>
                                        <span>{{ __('orders.dome') }}: <span
                                                class="font-medium">{{ $item->dome ?? 'N/A' }}</span></span>
                                        <span>{{ __('orders.sleeve') }}: <span
                                                class="font-medium">{{ $item->sleeve ?? 'N/A' }}</span></span>
                                        <span>{{ __('orders.fabric') }}: <span
                                                class="font-medium">{{ $item->fabric ?? 'N/A' }}</span></span>
                                        <span>{{ __('orders.quantity') }}: <span
                                                class="font-medium">{{ $item->quantity }}</span></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-purple-600">
                                        ${{ number_format($item->price * $item->quantity, 2) }}</p>
                                    <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }}
                                        {{ __('orders.each') }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Delivery Address -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                        {{ __('orders.delivery_address') }}
                    </h3>
                </div>
                <div class="p-6">
                    @if ($order->address)
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-map-pin text-purple-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $order->address->city->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $order->address->street }}</p>
                                    @if ($order->address->details)
                                        <p class="text-sm text-gray-500 mt-1">{{ $order->address->details }}</p>
                                    @endif
                                </div>
                            </div>

                            @if ($order->address->latitude && $order->address->longitude)
                                <div class="mt-4 pt-4 border-t">
                                    <a href="https://www.google.com/maps?q={{ $order->address->latitude }},{{ $order->address->longitude }}"
                                        target="_blank"
                                        class="inline-flex items-center text-sm text-purple-600 hover:text-purple-700 font-medium">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        {{ __('orders.view_on_map') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-map-marker-alt text-3xl mb-2 text-gray-300"></i>
                            <p class="text-sm">{{ __('orders.no_address') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-receipt text-purple-600 mr-2"></i>
                        {{ __('orders.order_summary') }}
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('orders.subtotal') }}</span>
                        <span class="font-semibold text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if ($order->coupon)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('orders.coupon_code') }}</span>
                            <span class="font-semibold text-gray-800">{{ $order->coupon->code }}</span>
                        </div>
                    @endif
                    @if ($order->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('orders.discount') }}</span>
                            <span class="font-semibold text-red-600">-${{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-3">
                    <div class="flex justify-between">
                        <span class="text-lg font-bold text-gray-800">{{ __('orders.total') }}</span>
                        <span
                            class="text-lg font-bold text-purple-600">${{ number_format($order->subtotal - $order->discount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-user text-purple-600 mr-2"></i>
                        {{ __('orders.customer_information') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        @if ($order->user?->image && $order->user->image->fullUrl)
                            <img src="{{ $order->user->image->fullUrl }}"
                                class="w-12 h-12 rounded-full object-cover border flex-shrink-0"
                                alt="{{ $order->user->name }}">
                        @else
                            <div
                                class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 flex-shrink-0">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $order->user->first_name ?? '' }}
                                {{ $order->user->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="space-y-5 text-sm">
                        <p class="flex items-center text-gray-600">
                            <i class="fas fa-envelope w-5 text-purple-600"></i>
                            <span>{{ $order->user->email ?? 'N/A' }}</span>
                        </p>
                        <p class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-5 text-purple-600"></i>
                            <span>{{ $order->user->phone ?? 'N/A' }}</span>
                        </p>
                        <p class="flex items-center text-gray-600">
                            <i class="fas fa-shopping-bag w-5 text-purple-600"></i>
                            <span>{{ $order->user->ordersCount ?? 0 }} {{ __('orders.orders') }}</span>
                        </p>
                    </div>

                    <a href="{{ route('users.show', $order->user->id) }}"
                        class="mt-4 block text-center px-4 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition text-sm font-medium">
                        {{ __('orders.view_profile') }}
                    </a>
                </div>
            </div>



        </div>

    </div>

@endsection

@push('scripts')
    <script>
        // Add any additional JavaScript functionality here
    </script>
@endpush
