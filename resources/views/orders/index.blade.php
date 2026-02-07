@extends('layouts.dashboard')

@section('title', __('orders.orders'))
@section('page-title', __('orders.orders'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('orders.all_orders') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('orders.manage_orders') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('orders.total_orders') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('orders.pending') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending_orders'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('orders.confirmed') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['confirmed_orders'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('orders.delivered') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['delivered_orders'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-truck text-xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    {{ __('orders.search_orders') }}
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('orders.search_placeholder') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('orders.status') }}
                </label>
                <select name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('orders.all_status') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                        {{ __('orders.pending') }}
                    </option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>
                        {{ __('orders.confirmed') }}
                    </option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                        {{ __('orders.delivered') }}
                    </option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                        {{ __('orders.cancelled') }}
                    </option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('orders.payment_status') }}
                </label>
                <select name="payment_status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('orders.all_status') }}</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>
                        {{ __('orders.pending') }}
                    </option>
                    <option value="confirmed" {{ request('payment_status') === 'confirmed' ? 'selected' : '' }}>
                        {{ __('orders.confirmed') }}
                    </option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>
                        {{ __('orders.failed') }}
                    </option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-day mr-1"></i>
                    {{ __('orders.start_date') }}
                </label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-day mr-1"></i>
                    {{ __('orders.end_date') }}
                </label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2 md:col-span-4">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('orders.search') }}
                </button>
                <a href="{{ route('orders.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between">
            <div class="text-sm text-gray-600">{{ __('orders.select_orders') }}</div>
            <button type="submit" form="orders-zip-form"
                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                <i class="fas fa-file-zipper mr-2"></i>
                {{ __('orders.download_zip') }}
            </button>
        </div>
        <div class="overflow-x-auto">
            <form method="POST" action="{{ route('orders.invoices.zip') }}" id="orders-zip-form">
                @csrf
                <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            <input type="checkbox" id="select-all-orders" class="h-4 w-4">
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.order_id') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.customer') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.subtotal') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.discount') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.total') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.status') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.payment_status') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.date') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('orders.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                    class="h-4 w-4 order-select">
                            </td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="text-purple-600 hover:text-purple-700 font-semibold">
                                    #{{ $order->num }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-left space-x-3">
                                    @if ($order->user?->image && $order->user->image->fullUrl)
                                        <img src="{{ $order->user->image->fullUrl }}"
                                            class="w-10 h-10 rounded-full object-cover border flex-shrink-0"
                                            alt="{{ $order->user->name }}">
                                    @else
                                        <div
                                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 flex-shrink-0">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="text-left">
                                        <div class="text-sm text-gray-800 flex items-center gap-2">
                                            @if ($order->user)
                                                <a href="{{ route('users.show', $order->user->id) }}"
                                                    class="hover:text-purple-700 font-semibold">
                                                    {{ $order->user->name }}
                                                </a>
                                                @if ($order->user->trashed())
                                                    <span
                                                        class="px-2 py-0.5 text-[10px] rounded-full bg-red-100 text-red-700">
                                                        {{ __('orders.deleted_user') }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $order->user->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                ${{ number_format($order->subtotal ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                ${{ number_format($order->discount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-800">
                                ${{ number_format(($order->subtotal ?? 0) - ($order->discount ?? 0), 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ __(ucfirst($order->status)) }}
                                </span>
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
                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $paymentStatusColors[$order->payment->status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ __(ucfirst($order->payment->status ?? 'pending')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div>{{ $order->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                        title="{{ __('orders.view') }}">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('orders.no_orders_found') }}</p>
                                    <p class="text-sm mt-2">{{ __('orders.try_adjusting_search') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </form>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        const selectAll = document.getElementById('select-all-orders');
        const checkboxes = document.querySelectorAll('.order-select');
        const zipForm = document.getElementById('orders-zip-form');

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }

        if (zipForm) {
            zipForm.addEventListener('submit', (event) => {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                if (!anyChecked) {
                    event.preventDefault();
                    alert("{{ __('orders.select_at_least_one') }}");
                }
            });
        }
    </script>
@endpush
