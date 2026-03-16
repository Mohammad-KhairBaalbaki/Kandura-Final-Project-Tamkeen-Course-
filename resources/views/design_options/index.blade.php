@extends('layouts.dashboard')

@section('title', __('design_options.design_options'))
@section('page-title', __('design_options.design_options_management'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('design_options.all_design_options') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('design_options.manage_design_options') }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center gap-2">
            <a href="{{ route('design_options.trashed') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium shadow-sm">
                <i class="fas fa-trash-restore mr-2"></i>
                {{ __('design_options.view_deleted') }}
            </a>
            <a href="{{ route('design_options.create') }}"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                {{ __('design_options.add_new_design_option') }}
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('design_options.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    {{ __('design_options.search_design_options') }}
                </label>
                <input type="text" name="name" value="{{ request('name') }}"
                    placeholder="{{ __('design_options.search_by_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('design_options.type') }}
                </label>
                <select name="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('design_options.all_types') }}</option>
                    <option value="color" {{ request('type') === 'color' ? 'selected' : '' }}>
                        {{ __('design_options.type_color') }}
                    </option>
                    <option value="dome" {{ request('type') === 'dome' ? 'selected' : '' }}>
                        {{ __('design_options.type_dome') }}
                    </option>
                    <option value="fabric" {{ request('type') === 'fabric' ? 'selected' : '' }}>
                        {{ __('design_options.type_fabric') }}
                    </option>
                    <option value="sleeve" {{ request('type') === 'sleeve' ? 'selected' : '' }}>
                        {{ __('design_options.type_sleeve') }}
                    </option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>
                    {{ __('design_options.status') }}
                </label>
                <select name="is_active"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('design_options.all_status') }}</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>
                        {{ __('design_options.active') }}
                    </option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>
                        {{ __('design_options.inactive') }}
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2 md:col-span-4">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('design_options.search') }}
                </button>
                <a href="{{ route('design_options.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Design Options Table -->
    <div class="bg-white rounded-lg shadow-md overflow-visible">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('design_options.name_en') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('design_options.name_ar') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('design_options.type') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('design_options.status') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('design_options.created') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">
                            {{ __('design_options.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($designOptions as $designOption)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-800">
                                    {{ $designOption->getTranslation('name', 'en') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-800">
                                    {{ $designOption->getTranslation('name', 'ar') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-800">
                                    {{ __('design_options.type_' . $designOption->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="relative inline-block text-left">
                                    <button type="button" onclick="toggleStatusMenu(event, {{ $designOption->id }})"
                                        class="px-4 py-1.5 text-xs font-semibold rounded-full transition
        {{ $designOption->is_active
            ? 'bg-green-100 text-green-800 hover:bg-green-200'
            : 'bg-purple-100 text-purple-700 hover:bg-purple-200' }}">
                                        {{ $designOption->is_active ? __('design_options.active') : __('design_options.inactive') }}
                                        <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                                    </button>

                                    <div id="status-menu-{{ $designOption->id }}"
                                        class="hidden absolute right-0 top-full z-50 mt-2 w-32 bg-white rounded-xl shadow-lg border p-2">
                                        <form method="POST" action="{{ route('design_options.updateStatus', $designOption->id) }}">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" name="is_active" value="1"
                                                class="w-full px-3 py-2 text-sm rounded-lg text-left
                hover:bg-green-100 text-green-800 transition">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                {{ __('design_options.active') }}
                                            </button>

                                            <button type="submit" name="is_active" value="0"
                                                class="w-full px-3 py-2 mt-1 text-sm rounded-lg text-left
                hover:bg-purple-100 text-purple-700 transition">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                {{ __('design_options.inactive') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div>{{ $designOption->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $designOption->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('design_options.edit', $designOption->id) }}"
                                        class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition"
                                        title="{{ __('design_options.edit') }}">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form method="POST" action="{{ route('design_options.destroy', $designOption->id) }}"
                                        onsubmit="return confirm('{{ __('design_options.delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                            title="{{ __('design_options.delete') }}">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-sliders-h text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">{{ __('design_options.no_design_options_found') }}</p>
                                    <p class="text-sm mt-2">{{ __('design_options.try_adjusting_search') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($designOptions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $designOptions->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function toggleStatusMenu(event, id) {
            event.preventDefault();
            event.stopPropagation();
            const menu = document.getElementById(`status-menu-${id}`);
            const isOpen = !menu.classList.contains('hidden');
            document
                .querySelectorAll('[id^="status-menu-"]')
                .forEach(el => el.classList.add('hidden'));

            if (!isOpen) {
                menu.classList.remove('hidden');
            }
        }
    </script>
@endpush
