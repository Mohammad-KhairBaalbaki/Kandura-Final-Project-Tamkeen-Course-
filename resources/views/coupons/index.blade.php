@extends('layouts.dashboard')

@section('title', __('coupons.coupons'))
@section('page-title', __('coupons.coupons_management'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('coupons.all_coupons') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('coupons.manage_coupons') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('coupons.create') }}"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                {{ __('coupons.add_new_coupon') }}
            </a>
        </div>
    </div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('coupons.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Code -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    {{ __('coupons.search_coupons') }}
                </label>
                <input type="text" name="code" value="{{ request('code') }}"
                    placeholder="{{ __('coupons.search_by_code') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('coupons.type') }}
                </label>
                <select name="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('coupons.all_types') }}</option>
                    <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>
                        {{ __('coupons.percentage') }}
                    </option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>
                        {{ __('coupons.fixed') }}
                    </option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('coupons.status') }}
                </label>
                <select name="is_active"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('coupons.all_status') }}</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>
                        {{ __('coupons.active') }}
                    </option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>
                        {{ __('coupons.inactive') }}
                    </option>
                </select>
                @error('is_active')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expired Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('coupons.expired') }}
                </label>
                <select name="expired"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('coupons.all_expired') }}</option>
                    <option value="1" {{ request('expired') === '1' ? 'selected' : '' }}>
                        {{ __('coupons.expired') }}
                    </option>
                    <option value="0" {{ request('expired') === '0' ? 'selected' : '' }}>
                        {{ __('coupons.not_expired') }}
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2 md:col-span-4">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('coupons.search') }}
                </button>
                <a href="{{ route('coupons.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
            @error('form')
                <p class="mt-2 text-xs text-red-600 md:col-span-4">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.code') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.type') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.amount') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.order_limit') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.general_limit') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.usages') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.validity') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.validity') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.status') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('coupons.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-800">
                                {{ $coupon->code }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                {{ $coupon->is_percentage ? __('coupons.percentage') : __('coupons.fixed') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                @if ($coupon->is_percentage)
                                    {{ number_format($coupon->amount ?? 0, 2) }}%
                                @else
                                    ${{ number_format($coupon->amount ?? 0, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                ${{ number_format($coupon->order_limit_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                {{ $coupon->general_limit ?? 0 }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-800">
                                {{ $coupon->usages ?? 0 }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div>{{ ($coupon->validate_from) }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ ($coupon->validate_until) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $expiresAt = $coupon->validate_until
                                        ? \Illuminate\Support\Carbon::parse($coupon->validate_until)
                                        : null;
                                    $isExpired = ($coupon->usages ?? 0) >= ($coupon->general_limit ?? 0)
                                        || ($expiresAt ? $expiresAt->isPast() : false);
                                @endphp
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $isExpired ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $isExpired ? __('coupons.expired') : __('coupons.not_expired') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="relative inline-block text-left">
                                    <button type="button" onclick="toggleStatusMenu({{ $coupon->id }})"
                                        class="px-4 py-1.5 text-xs font-semibold rounded-full transition
        {{ $coupon->is_active
            ? 'bg-green-100 text-green-800 hover:bg-green-200'
            : 'bg-purple-100 text-purple-700 hover:bg-purple-200' }}">
                                        {{ $coupon->is_active ? __('coupons.active') : __('coupons.inactive') }}
                                        <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                                    </button>

                                    <div id="status-menu-{{ $coupon->id }}"
                                        class="hidden fixed z-50 w-32 bg-white rounded-xl shadow-lg border p-2">
                                        <form method="POST" action="{{ route('coupons.updateStatus', $coupon->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" name="is_active" value="1"
                                                class="w-full px-3 py-2 text-sm rounded-lg text-left
                hover:bg-green-100 text-green-800 transition">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                {{ __('coupons.active') }}
                                            </button>

                                            <button type="submit" name="is_active" value="0"
                                                class="w-full px-3 py-2 mt-1 text-sm rounded-lg text-left
                hover:bg-purple-100 text-purple-700 transition">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                {{ __('coupons.inactive') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('coupons.edit', $coupon->id) }}"
                                        class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition"
                                        title="{{ __('coupons.edit') }}">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-ticket-alt text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('coupons.no_coupons_found') }}</p>
                                    <p class="text-sm mt-2">{{ __('coupons.try_adjusting_search') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function toggleStatusMenu(id) {
            const menu = document.getElementById(`status-menu-${id}`);
            const button = event.currentTarget;
            const isOpen = !menu.classList.contains('hidden');
            document
                .querySelectorAll('[id^="status-menu-"]')
                .forEach(el => el.classList.add('hidden'));

            if (!isOpen) {
                const rect = button.getBoundingClientRect();
                menu.style.top = `${rect.bottom + 8}px`;
                menu.style.left = `${rect.left}px`;
                menu.classList.remove('hidden');
            }
        }
    </script>
@endpush
