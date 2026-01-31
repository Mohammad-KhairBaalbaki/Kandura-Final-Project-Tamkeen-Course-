@extends('layouts.dashboard')

@section('title', __('Roles'))
@section('page-title', __('Roles'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('All Roles') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('Manage roles and permissions') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('roles.create') }}"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Add Role') }}
            </a>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('Role') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('Permissions') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('Users') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($roles as $role)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold text-gray-800">{{ $role->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-700">
                                    {{ $role->permissions->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-700">
                                    {{ $role->users_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('roles.show', $role->id) }}"
                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                        title="{{ __('View') }}">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                        class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition"
                                        title="{{ __('Edit') }}">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @if (($role->users_count ?? 0) == 0)
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                title="{{ __('Delete') }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-user-shield text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('No roles found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $roles->links() }}
            </div>
        @endif
    </div>

@endsection
