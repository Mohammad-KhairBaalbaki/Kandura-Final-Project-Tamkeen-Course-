@extends('layouts.dashboard')

@section('title', __('admins.deleted_admins'))
@section('page-title', __('admins.deleted_admins'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('admins.deleted_admins') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('admins.manage_deleted_admins') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admins.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('admins.back_to_admins') }}
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admins.trashed') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    {{ __('admins.search_users') }}
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('admins.search_by_name_email_phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('admins.search') }}
                </button>
                <a href="{{ route('admins.trashed') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Deleted Admins Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('admins.user') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('admins.contact') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('admins.deleted_at') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('admins.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <!-- User Info -->
                            <td class="px-6 py-4 text-center">
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
                            <td class="px-6 py-4 text-center">
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

                            <!-- Deleted At -->
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div>{{ $user->deleted_at?->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $user->deleted_at?->diffForHumans() }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <form method="POST" action="{{ route('admins.restore', $user->id) }}"
                                        onsubmit="return confirm('{{ __('admins.restore_confirm') }}')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="p-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition"
                                            title="{{ __('admins.restore') }}">
                                            <i class="fas fa-undo text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-users text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('admins.no_deleted_admins') }}</p>
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
