@extends('layouts.dashboard')

@section('title', __('users.user_details'))
@section('page-title', __('users.user_details'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('users.user_details') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('users.manage_users') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('users.all_users') }}
            </a>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center space-x-4">
                @if ($user->image && $user->image->fullUrl)
                    <img src="{{ $user->image->fullUrl }}"
                        class="w-16 h-16 rounded-full object-cover border"
                        alt="{{ $user->name }}">
                @else
                    <div
                        class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-2xl font-semibold">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    <p class="text-sm text-gray-600">{{ $user->phone ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex flex-col items-start md:items-end gap-2">
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleStatusMenuShow()"
                        class="px-4 py-1.5 text-xs font-semibold rounded-full transition
                        {{ $user->is_active
                            ? 'bg-green-100 text-green-800 hover:bg-green-200'
                            : 'bg-purple-100 text-purple-700 hover:bg-purple-200' }}">
                        {{ $user->is_active ? __('users.active') : __('users.inactive') }}
                        <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                    </button>

                    <div id="status-menu-show"
                        class="hidden fixed z-50 w-32 bg-white rounded-xl shadow-lg border p-2">
                        <form method="POST" action="{{ route('users.updateStatus', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <button name="is_active" value="1"
                                class="w-full px-3 py-2 text-sm rounded-lg text-left
                                hover:bg-green-100 text-green-800 transition">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ __('users.active') }}
                            </button>

                            <button name="is_active" value="0"
                                class="w-full px-3 py-2 mt-1 text-sm rounded-lg text-left
                                hover:bg-purple-100 text-purple-700 transition">
                                <i class="fas fa-times-circle mr-2"></i>
                                {{ __('users.inactive') }}
                            </button>
                        </form>
                    </div>
                </div>
                @error('is_active')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
                <div class="text-xs text-gray-500">
                    <div>{{ __('users.joined') }}: {{ $user->created_at->format('d M, Y') }}</div>
                    <div>{{ $user->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.orders') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $user->orders_count ?? ($user->orders->count() ?? 0) }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.designs') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $user->designs_count ?? ($user->designs->count() ?? 0) }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-palette text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.recent_designs') }}</p>
                    <div class="text-sm text-gray-700">
                        @if (($user->designs->count() ?? 0) > 0)
                            {{ $user->designs->last()->name ?? __('users.designs') }}
                        @else
                            {{ __('users.no_designs') }}
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-image text-xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Design Preview -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('users.recent_designs') }}</h3>
        @if (($user->designs->count() ?? 0) > 0)
            @php
                $latestDesign = $user->designs->last();
            @endphp
            <a href="{{ route('designs.show', $latestDesign->id) }}"
                class="flex items-center space-x-4 group">
                @if ($latestDesign->images->first())
                    <img src="{{ $latestDesign->images->first()->fullUrl }}"
                        class="w-20 h-20 rounded-lg object-cover border group-hover:opacity-90"
                        title="{{ $latestDesign->title }}">
                @else
                    <div
                        class="w-20 h-20 rounded-lg border bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-800 group-hover:text-purple-700">
                        {{ $latestDesign->name ?? __('users.designs') }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $latestDesign->created_at->format('d M, Y') }}</p>
                </div>
            </a>
        @else
            <div class="flex flex-col items-center justify-center text-gray-500 py-8">
                <i class="fas fa-palette text-4xl mb-3 text-gray-300"></i>
                <p class="text-sm">{{ __('users.no_designs') }}</p>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function toggleStatusMenuShow() {
            const menu = document.getElementById('status-menu-show');
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
