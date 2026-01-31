@extends('layouts.dashboard')

@section('title', __('coupons.edit_coupon'))
@section('page-title', __('coupons.edit_coupon'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('coupons.edit_coupon') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('coupons.coupon_details') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('coupons.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('coupons.all_coupons') }}
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('coupons.update', $coupon->id) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.code') }}
                    </label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('code')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.discount_type') }}
                    </label>
                    <select name="is_percentage"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="0" {{ old('is_percentage', $coupon->is_percentage ? '1' : '0') === '0' ? 'selected' : '' }}>
                            {{ __('coupons.fixed') }}
                        </option>
                        <option value="1" {{ old('is_percentage', $coupon->is_percentage ? '1' : '0') === '1' ? 'selected' : '' }}>
                            {{ __('coupons.percentage') }}
                        </option>
                    </select>
                    @error('is_percentage')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.amount') }}
                    </label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $coupon->amount) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Limit Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.order_limit_amount') }}
                    </label>
                    <input type="number" step="0.01" name="order_limit_amount"
                        value="{{ old('order_limit_amount', $coupon->order_limit_amount) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('order_limit_amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- General Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.general_limit') }}
                    </label>
                    <input type="number" name="general_limit" value="{{ old('general_limit', $coupon->general_limit) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('general_limit')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.status') }}
                    </label>
                    <select name="is_active"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="1" {{ old('is_active', $coupon->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>
                            {{ __('coupons.active') }}
                        </option>
                        <option value="0" {{ old('is_active', $coupon->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>
                            {{ __('coupons.inactive') }}
                        </option>
                    </select>
                    @error('is_active')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Validate From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.valid_from') }}
                    </label>
                    <input type="date" name="validate_from"
                        value="{{ old('validate_from', optional($coupon->validate_from)->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('validate_from')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Validate Until -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('coupons.valid_until') }}
                    </label>
                    <input type="date" name="validate_until"
                        value="{{ old('validate_until', optional($coupon->validate_until)->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('validate_until')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6 space-x-3">
                <a href="{{ route('coupons.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    {{ __('coupons.cancel') }}
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('coupons.save') }}
                </button>
            </div>
            @error('form')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </form>
    </div>

@endsection
