@extends('layouts.dashboard')

@section('title', __('Profile'))
@section('page-title', __('Profile'))

@section('content')

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Profile') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('Account information and permissions') }}</p>
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
                <span
                    class="px-4 py-1.5 text-xs font-semibold rounded-full
                    {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-700' }}">
                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                </span>
                <div class="text-xs text-gray-500">
                    <div>{{ __('Joined') }}: {{ $user->created_at->format('d M, Y') }}</div>
                    <div>{{ $user->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">


        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('Roles') }}</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($user->roles as $role)
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-500">-</span>
                        @endforelse
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-id-badge text-xl text-green-600"></i>
                </div>
            </div>
        </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('Permissions') }}</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $user->getAllPermissions()->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shield-alt text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>
    </div>

    <!-- Permissions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('Permissions') }}</h3>
        <div class="flex flex-wrap gap-2">
            @forelse ($user->getAllPermissions() as $permission)
                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                    {{ $permission->name }}
                </span>
            @empty
                <span class="text-sm text-gray-500">{{ __('No permissions assigned') }}</span>
            @endforelse
        </div>
    </div>

@endsection
