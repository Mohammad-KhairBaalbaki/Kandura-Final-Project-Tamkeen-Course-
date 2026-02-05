@extends('layouts.dashboard')

@section('title', __('Edit Role'))
@section('page-title', __('Edit Role'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Edit Role') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('Update role details and permissions') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('All Roles') }}
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Role Name') }}
                    </label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Permissions') }}
                    </label>
                    @php
                        $selectedPermissions = old('permissions', $role->permissions->pluck('name')->toArray());
                    @endphp

                    <div class="space-y-4">
                        @if ($groupUsers->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('User Management') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupUsers as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($groupOrders->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('Order Management') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupOrders as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($groupDesigns->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('Design Management') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupDesigns as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($groupDesignOptions->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('Design Options Management') }}
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupDesignOptions as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($groupCoupons->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('Coupon Management') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupCoupons as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        

                        @if ($groupWallets->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('Wallet Management') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupWallets as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($groupNotifications->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">
                                    {{ __('notifications.notifications') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($groupNotifications as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($otherPermissions->isNotEmpty())
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">{{ __('Other Permissions') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($otherPermissions as $permission)
                                        <label
                                            class="flex items-start gap-3 p-2 rounded-lg border border-gray-100 hover:bg-gray-50">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="mt-1"
                                                {{ in_array($permission->name, $selectedPermissions, true) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    @error('permissions')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('permissions.*')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="button" id="reset-permissions"
                        class="mt-3 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-xs font-medium">
                        <i class="fas fa-undo mr-1"></i>
                        {{ __('Reset Permissions') }}
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6 space-x-3">
                <a href="{{ route('roles.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const resetBtn = document.getElementById('reset-permissions');
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            if (!resetBtn || !checkboxes.length) return;
            resetBtn.addEventListener('click', () => {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        });
    </script>
@endpush
