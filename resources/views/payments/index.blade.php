@extends('layouts.dashboard')

@section('title', __('payments.payments'))
@section('page-title', __('payments.payments'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('payments.all_payments') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('payments.manage_payments') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('payments.total_payments') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_payments'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('payments.confirmed') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['confirmed_payments'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('payments.pending') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending_payments'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('payments.failed') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['failed_payments'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('payments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    {{ __('payments.search_payments') }}
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('payments.search_placeholder') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('payments.status') }}
                </label>
                <select name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('payments.all_status') }}</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>
                        {{ __('payments.confirmed') }}
                    </option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                        {{ __('payments.pending') }}
                    </option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>
                        {{ __('payments.failed') }}
                    </option>
                </select>
            </div>

            <!-- Method Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-credit-card mr-1"></i>
                    {{ __('payments.method') }}
                </label>
                <select name="method"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('payments.method_placeholder') }}</option>
                    <option value="{{ \App\Enums\PaymentMethodEnum::WALLET }}"
                        {{ request('method') === \App\Enums\PaymentMethodEnum::WALLET ? 'selected' : '' }}>
                        {{ \App\Enums\PaymentMethodEnum::WALLET }}
                    </option>
                    <option value="{{ \App\Enums\PaymentMethodEnum::STRIPE }}"
                        {{ request('method') === \App\Enums\PaymentMethodEnum::STRIPE ? 'selected' : '' }}>
                        {{ \App\Enums\PaymentMethodEnum::STRIPE }}
                    </option>
                    <option value="{{ \App\Enums\PaymentMethodEnum::AFTER_DELIVERY }}"
                        {{ request('method') === \App\Enums\PaymentMethodEnum::AFTER_DELIVERY ? 'selected' : '' }}>
                        {{ \App\Enums\PaymentMethodEnum::AFTER_DELIVERY }}
                    </option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-exchange-alt mr-1"></i>
                    {{ __('payments.type') }}
                </label>
                <select name="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('payments.all_types') }}</option>
                    <option value="charge" {{ request('type') === 'charge' ? 'selected' : '' }}>
                        {{ __('payments.charge') }}
                    </option>
                    <option value="pay" {{ request('type') === 'pay' ? 'selected' : '' }}>
                        {{ __('payments.pay') }}
                    </option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-day mr-1"></i>
                    {{ __('payments.start_date') }}
                </label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-day mr-1"></i>
                    {{ __('payments.end_date') }}
                </label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2 md:col-span-4">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('payments.search') }}
                </button>
                <a href="{{ route('payments.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.payment_id') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.user') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.order') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.amount') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.method') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.type') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.status') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('payments.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-semibold text-gray-800">
                                    #{{ $payment->num ?? $payment->id }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    @if ($payment->user?->image && $payment->user->image->fullUrl)
                                        <img src="{{ $payment->user->image->fullUrl }}"
                                            class="w-10 h-10 rounded-full object-cover border flex-shrink-0"
                                            alt="{{ $payment->user->name }}">
                                    @else
                                        <div
                                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 flex-shrink-0">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="text-left">
                                        <div class="text-sm text-gray-800">
                                            {{ $payment->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $payment->user->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($payment->order)
                                    <a href="{{ route('orders.show', $payment->order->id) }}"
                                        class="text-purple-600 hover:text-purple-700 font-semibold">
                                        #{{ $payment->order->num ?? $payment->order->id }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-800">
                                ${{ number_format($payment->amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                {{ $payment->method ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                {{ $payment->type ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $paymentStatusColors = [
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $paymentStatusColors[$payment->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ __(ucfirst($payment->status ?? 'pending')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div>{{ $payment->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('payments.no_payments_found') }}</p>
                                    <p class="text-sm mt-2">{{ __('payments.try_adjusting_search') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

@endsection
