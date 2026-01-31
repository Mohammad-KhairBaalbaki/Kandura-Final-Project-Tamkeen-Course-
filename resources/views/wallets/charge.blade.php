@extends('layouts.dashboard')

@section('title', __('wallets.charge_wallet'))
@section('page-title', __('wallets.charge_wallet'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('wallets.charge_wallet') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('wallets.charge_wallet_desc') }}</p>
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

        <form method="POST" action="{{ route('wallets.storeCharge') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email or Phone -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('wallets.email_or_phone') }}
                    </label>
                    <input type="text" id="identifier-input" name="identifier" value="{{ old('identifier') }}" required
                        placeholder="{{ __('wallets.email_or_phone_placeholder') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('identifier')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('wallets.amount') }}
                    </label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6 space-x-3">
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-wallet mr-2"></i>
                    {{ __('wallets.charge') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Wallets Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-wallet text-purple-600 mr-2"></i>
                {{ __('wallets.wallets') }}
            </h3>
            <p class="text-sm text-gray-600 mt-1">{{ __('wallets.select_user_help') }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('wallets.user') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('wallets.email') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('wallets.phone') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('wallets.balance') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('wallets.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($wallets as $wallet)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    @if ($wallet->user?->image && $wallet->user->image->fullUrl)
                                        <img src="{{ $wallet->user->image->fullUrl }}"
                                            class="w-10 h-10 rounded-full object-cover border flex-shrink-0"
                                            alt="{{ $wallet->user->name }}">
                                    @else
                                        <div
                                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 flex-shrink-0">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <span class="text-sm text-gray-800">{{ $wallet->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-800">{{ $wallet->user->email ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-800">{{ $wallet->user->phone ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ number_format($wallet->balance ?? 0, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button"
                                    data-identifier="{{ $wallet->user->phone ?: $wallet->user->email }}"
                                    class="px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition text-sm font-medium js-fill-identifier">
                                    <i class="fas fa-arrow-up-right-from-square mr-1 text-xs"></i>
                                    {{ __('wallets.use_user') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-wallet text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('wallets.no_wallets_found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($wallets->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $wallets->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.js-fill-identifier').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById('identifier-input');
                input.value = button.dataset.identifier || '';
                input.focus();
            });
        });
    </script>
@endpush
