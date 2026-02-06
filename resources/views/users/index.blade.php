@extends('layouts.dashboard')

@section('title', __('users.users'))
@section('page-title', __('users.users_management'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('users.all_users') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('users.manage_users') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.total_users') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.active_users') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['active_users'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.new_this_month') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['new_users'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('users.blocked_users') }}</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['blocked_users'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-slash text-xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    {{ __('users.search_users') }}
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('users.search_by_name_email_phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('users.status') }}
                </label>
                <select name="is_active"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('users.all_status') }}</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>{{ __('users.active') }}
                    </option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>{{ __('users.inactive') }}
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('users.search') }}
                </button>
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.user') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.contact') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.orders') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.designs') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.recent_designs') }}
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.status') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.joined') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('users.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <!-- User Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if ($user->image && $user->image->fullUrl)
                                        <img src="{{ $user->image->fullUrl }}"
                                            class="w-10 h-10 rounded-full object-cover border flex-shrink-0"
                                            alt="{{ $user->name }}">
                                    @else
                                        <div
                                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 flex-shrink-0">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-gray-800 flex items-center">
                                        <i class="fas fa-envelope text-purple-600 mr-2 text-xs"></i>
                                        {{ $user->email }}
                                    </p>
                                    <p class="text-gray-600 flex items-center mt-1">
                                        <i class="fas fa-phone text-purple-600 mr-2 text-xs"></i>
                                        {{ $user->phone ?? 'N/A' }}
                                    </p>
                                </div>
                            </td>

                            <!-- Orders Count -->
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    <i class="fas fa-shopping-bag mr-1 text-xs"></i>
                                    {{ $user->orders_count ?? 0 }}
                                </span>
                            </td>

                            <!-- Designs Count -->
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    <i class="fas fa-palette text-xs"></i>
                                    {{ $user->designs->count() ?? 0 }}
                                </span>
                            </td>
                            {{-- Recent design --}}
                            <td class="px-6 py-4 text-center">
                                @if ($user->designs->count())
                                    <div class="flex justify-center space-x-2">
                                        @if ($user->designs->last()?->images?->first())
                                            <img src="{{ $user->designs->last()->images->first()->fullUrl }}"
                                                class="w-10 h-10 rounded-lg object-cover border"
                                                title="{{ $user->designs->last()->title }}">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-lg border bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">
                                        {{ __('users.no_designs') }}
                                    </span>
                                @endif
                            </td>


                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <div class="relative inline-block text-left">
                                    <button type="button" onclick="toggleStatusMenu({{ $user->id }})"
                                        class="px-4 py-1.5 text-xs font-semibold rounded-full transition
        {{ $user->is_active
            ? 'bg-green-100 text-green-800 hover:bg-green-200'
            : 'bg-purple-100 text-purple-700 hover:bg-purple-200' }}">
                                        {{ $user->is_active ? __('users.active') : __('users.inactive') }}
                                        <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                                    </button>

                                    <div id="status-menu-{{ $user->id }}"
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

                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div>{{ $user->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    {{-- {{ route('users.show', $user->id) }} --}}
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                        title="{{ __('users.view') }}">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-users text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('users.no_users_found') }}</p>
                                    <p class="text-sm mt-2">{{ __('users.try_adjusting_search') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
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

        function deleteUser(userId) {
            if (confirm('{{ __('users.are_you_sure_delete_user') }}')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/users/${userId}';

                // CSRF token
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';
                form.appendChild(csrfField);

                // Method spoofing for DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
