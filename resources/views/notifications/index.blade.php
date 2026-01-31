@extends('layouts.dashboard')

@section('title', __('notifications.notifications'))
@section('page-title', __('notifications.notifications'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('notifications.notifications') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('notifications.manage_notifications') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between">
            <div class="text-sm text-gray-600">{{ __('notifications.select_notifications') }}</div>
            <button type="submit" form="notifications-read-form"
                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                <i class="fas fa-check mr-2"></i>
                {{ __('notifications.mark_selected_read') }}
            </button>
        </div>
        <div class="overflow-x-auto">
            <form method="POST" action="{{ route('notifications.markReadBulk') }}" id="notifications-read-form">
                @csrf
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                <input type="checkbox" id="select-all-notifications" class="h-4 w-4">
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                {{ __('notifications.title') }}
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                {{ __('notifications.message') }}
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                {{ __('notifications.status') }}
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                {{ __('notifications.date') }}
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                                {{ __('notifications.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($notifications as $notification)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="notification_ids[]"
                                        value="{{ $notification->id }}"
                                        class="h-4 w-4 notification-select"
                                        {{ $notification->read_at ? 'disabled' : '' }}>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="text-sm {{ $notification->read_at ? 'text-gray-800' : 'font-semibold text-gray-900' }}">
                                        {{ $notification->data['title'] ?? __('notifications.notification') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ $notification->data['body'] ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($notification->read_at)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                            {{ __('notifications.read') }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                            {{ __('notifications.unread') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    <div>{{ $notification->created_at->format('d M, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition text-xs font-medium">
                                            {{ __('notifications.view') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-semibold">{{ __('notifications.no_notifications') }}</p>
                                        <p class="text-sm mt-2">{{ __('notifications.try_adjusting') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Pagination -->
        @if ($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        const selectAllNotifications = document.getElementById('select-all-notifications');
        const notificationCheckboxes = document.querySelectorAll('.notification-select');
        const readForm = document.getElementById('notifications-read-form');

        if (selectAllNotifications) {
            selectAllNotifications.addEventListener('change', () => {
                notificationCheckboxes.forEach(cb => {
                    if (!cb.disabled) cb.checked = selectAllNotifications.checked;
                });
            });
        }

        if (readForm) {
            readForm.addEventListener('submit', (event) => {
                const anyChecked = Array.from(notificationCheckboxes).some(cb => cb.checked);
                if (!anyChecked) {
                    event.preventDefault();
                    alert("{{ __('notifications.select_at_least_one') }}");
                }
            });
        }
    </script>
@endpush
