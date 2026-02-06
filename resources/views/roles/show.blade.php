@extends('layouts.dashboard')

@section('title', __('roles.role_details'))
@section('page-title', __('roles.role_details'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('roles.role_details') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('roles.view_role_help') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('roles.all_roles') }}
            </a>
        </div>
    </div>

    <!-- Role Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $role->name }}</h3>
                <p class="text-sm text-gray-600">{{ __('roles.users') }}: {{ $role->users->count() }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('roles.edit', $role->id) }}"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i>
                    {{ __('roles.edit') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">{{ __('roles.permissions') }}</h3>
            <span class="text-xs text-gray-500">
                {{ $role->permissions->count() }} {{ __('roles.total') }}
            </span>
        </div>
        @if ($role->permissions->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($role->permissions as $permission)
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                        <span class="text-sm font-semibold text-gray-800">{{ $permission->name }}</span>
                        <span class="text-[10px] uppercase tracking-wider text-gray-500">
                            {{ __('roles.permission') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm text-gray-500">{{ __('roles.no_permissions') }}</div>
        @endif
    </div>

@endsection
