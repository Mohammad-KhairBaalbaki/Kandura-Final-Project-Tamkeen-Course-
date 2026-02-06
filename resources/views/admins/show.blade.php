@extends('layouts.dashboard')

@section('title', __('admins.user_details'))
@section('page-title', __('admins.user_details'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('admins.user_details') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('admins.manage_users_permissions') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admins.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('admins.all_users') }}
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
                        {{ $user->is_active ? __('admins.active') : __('admins.inactive') }}
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
                                {{ __('admins.active') }}
                            </button>

                            <button name="is_active" value="0"
                                class="w-full px-3 py-2 mt-1 text-sm rounded-lg text-left
                                hover:bg-purple-100 text-purple-700 transition">
                                <i class="fas fa-times-circle mr-2"></i>
                                {{ __('admins.inactive') }}
                            </button>
                        </form>
                    </div>
                </div>
                @error('is_active')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
                <div class="text-xs text-gray-500">
                    <div>{{ __('admins.joined') }}: {{ $user->created_at->format('d M, Y') }}</div>
                    <div>{{ $user->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
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
