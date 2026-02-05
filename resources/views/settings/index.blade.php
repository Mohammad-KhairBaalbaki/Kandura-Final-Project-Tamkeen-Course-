@extends('layouts.dashboard')

@section('title', __('Settings'))
@section('page-title', __('Settings'))

@section('content')

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Settings') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('settings.manage_notifications') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">{{ __('settings.manage_notifications') }}</h3>
            <span class="text-xs text-gray-500">{{ __('settings.push_only') }}</span>
        </div>

        @if ($permissions->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-bell text-4xl mb-3 text-gray-300"></i>
                <p>{{ __('settings.no_notification_permissions') }}</p>
            </div>
        @else
            <form method="POST" action="{{ route('settings.notifications.update') }}">
                @csrf
                <div class="space-y-4">
                    @foreach ($permissions as $permission)
                        @php
                            $enabled = $preferences->get($permission->id)?->enabled ?? true;
                        @endphp
                        <div class="flex items-center justify-between p-4 border rounded-lg">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800">{{ $permission->name }}</p>
                                <p class="text-xs text-gray-500">{{ __('settings.permission_label') }}</p>
                            </div>

                            <input type="hidden" name="permissions[]" value="{{ $permission->id }}">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enabled_permissions[]" value="{{ $permission->id }}"
                                    class="sr-only peer" {{ $enabled ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:bg-green-500">
                                </div>
                                <span
                                    class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                        {{ __('settings.save_changes') }}
                    </button>
                </div>
            </form>
        @endif
    </div>

@endsection
