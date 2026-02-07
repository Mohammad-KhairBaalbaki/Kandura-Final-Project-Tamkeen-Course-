@extends('layouts.dashboard')

@section('title', __('designs.designs'))
@section('page-title', __('designs.designs_management'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ __('designs.all_designs') }}</h2>
            <p class="text-xs text-gray-600 mt-1">{{ __('designs.manage_designs') }}</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('designs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <!-- Name -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.name') }}
                </label>
                <input type="text" name="name" value="{{ request('name') }}"
                    placeholder="{{ __('designs.search_by_name') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.description') }}
                </label>
                <input type="text" name="description" value="{{ request('description') }}"
                    placeholder="{{ __('designs.search_by_description') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Creator -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.creator') }}
                </label>
                <input type="text" name="user_name" value="{{ request('user_name') }}"
                    placeholder="{{ __('designs.search_by_creator') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Min Price -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.min_price') }}
                </label>
                <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Max Price -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.max_price') }}
                </label>
                <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Design Options Name -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.design_options_name') }}
                </label>
                <input type="text" name="design_options_name" value="{{ request('design_options_name') }}"
                    placeholder="{{ __('designs.search_by_design_options') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Design Options by Type -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('design_options.type_color') }}
                </label>
                <input type="text" name="design_options_color" value="{{ request('design_options_color') }}"
                    placeholder="{{ __('designs.search_by_design_options') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('design_options.type_fabric') }}
                </label>
                <input type="text" name="design_options_fabric" value="{{ request('design_options_fabric') }}"
                    placeholder="{{ __('designs.search_by_design_options') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('design_options.type_dome') }}
                </label>
                <input type="text" name="design_options_dome" value="{{ request('design_options_dome') }}"
                    placeholder="{{ __('designs.search_by_design_options') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('design_options.type_sleeve') }}
                </label>
                <input type="text" name="design_options_sleeve" value="{{ request('design_options_sleeve') }}"
                    placeholder="{{ __('designs.search_by_design_options') }}"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <!-- Measurements -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.measurements') }}
                </label>
                @php
                    $selectedMeasurements = array_map('strval', (array) request('measurements', []));
                @endphp
                <div class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-purple-500 focus-within:border-transparent transition">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($measurements as $measurement)
                            <label class="inline-flex items-center gap-2 text-xs text-gray-700">
                                <input type="checkbox" name="measurements[]" value="{{ $measurement->id }}"
                                    class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                    {{ in_array((string) $measurement->id, $selectedMeasurements, true) ? 'checked' : '' }}>
                                <span>{{ $measurement->size }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <!-- Status -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ __('designs.status') }}
                </label>
                <select name="status"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">{{ __('designs.all_status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ __('designs.active') }}
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        {{ __('designs.inactive') }}
                    </option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>
                        {{ __('designs.blocked') }}
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2 md:col-span-5">
                <button type="submit"
                    class="px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('designs.search') }}
                </button>
                <a href="{{ route('designs.index') }}"
                    class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Designs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($designs as $design)
            @php
                $statusColors = [
                    'active' => 'bg-green-100 text-green-800',
                    'inactive' => 'bg-gray-100 text-gray-700',
                    'blocked' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <a href="{{ route('designs.show', $design->id) }}"
                class="group block bg-white rounded-2xl shadow-md rounded-2xl hover:shadow-lg transition">
                <div class="relative overflow-hidden rounded-2xl">
                    @if ($design->images->first())
                        <img src="{{ $design->images->first()->fullUrl }}" alt=""
                            class="w-full h-52 object-cover group-hover:scale-[1.02] transition">
                    @else
                        <div class="w-full h-52 bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-3xl"></i>
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug">
                        {{ $design->getTranslation('name', app()->getLocale()) }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                        {{ $design->getTranslation('description', app()->getLocale()) }}
                    </p>

                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="text-lg font-bold text-gray-900">
                            {{ number_format($design->price ?? 0, 2) }}
                            <span class="text-[11px] text-gray-500">{{ __('USD') }}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $design->created_at->format('d M, Y') }}</span>
                    </div>

                    <div class="mt-3 flex items-center justify-between text-xs text-gray-600">
                        <div class="flex items-center">
                            @if ($design->user && $design->user->image && $design->user->image->fullUrl)
                                <img src="{{ $design->user->image->fullUrl }}"
                                    class="w-5 h-5 rounded-full object-cover border mr-2"
                                    alt="{{ $design->user->name }}">
                            @else
                                <div class="w-5 h-5 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-[10px]"></i>
                                </div>
                            @endif
                            <span class="line-clamp-1">{{ $design->user->name ?? '-' }}</span>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full
                            {{ $design->user && $design->user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $design->user && $design->user->is_active ? __('users.active') : __('users.inactive') }}
                        </span>
                    </div>

                    @php
                        $optionsByType = $design->designOptions->groupBy('type');
                        $optionTypes = [
                            'color' => __('design_options.type_color'),
                            'fabric' => __('design_options.type_fabric'),
                            'dome' => __('design_options.type_dome'),
                            'sleeve' => __('design_options.type_sleeve'),
                        ];
                    @endphp
                    <div class="mt-3 space-y-2 text-[11px] text-gray-600">
                        @foreach ($optionTypes as $typeKey => $typeLabel)
                            <div class="flex items-start gap-2">
                                <span class="font-semibold text-gray-500 min-w-[48px]">{{ $typeLabel }}:</span>
                                <span class="line-clamp-2">
                                    @forelse ($optionsByType->get($typeKey, collect()) as $option)
                                        {{ $option->getTranslation('name', app()->getLocale()) }}@if (! $loop->last), @endif
                                    @empty
                                        -
                                    @endforelse
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <div class="text-[11px] font-semibold text-gray-500 mb-1">
                            {{ __('designs.measurements') }}
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @forelse ($design->measurements as $measurement)
                                <span class="px-2 py-0.5 text-[10px] rounded-full bg-gray-100 text-gray-700">
                                    {{ $measurement->size }}
                                </span>
                            @empty
                                <span class="text-[10px] text-gray-400">-</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="relative inline-block text-left w-full">
                            <button type="button" onclick="toggleStatusMenu(event, {{ $design->id }})"
                                class="w-full px-4 py-1.5 text-xs font-semibold rounded-full transition
        {{ $design->status === 'active'
            ? 'bg-green-100 text-green-800 hover:bg-green-200'
            : ($design->status === 'blocked'
                ? 'bg-red-100 text-red-800 hover:bg-red-200'
                : 'bg-purple-100 text-purple-700 hover:bg-purple-200') }}">
                                {{ $design->status === 'active'
                                    ? __('designs.active')
                                    : ($design->status === 'blocked'
                                        ? __('designs.blocked')
                                        : __('designs.inactive')) }}
                                <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                            </button>

                            <div id="status-menu-{{ $design->id }}"
                                class="hidden absolute left-0 top-full z-10 mt-2 w-36 bg-white rounded-xl shadow-lg border p-2">
                                <form method="POST" action="{{ route('designs.updateStatus', $design->id) }}"
                                    onclick="event.stopPropagation()">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" name="status" value="active"
                                        class="w-full px-3 py-2 text-sm rounded-lg text-left
                hover:bg-green-100 text-green-800 transition">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ __('designs.active') }}
                                    </button>



                                    <button type="submit" name="status" value="blocked"
                                        class="w-full px-3 py-2 mt-1 text-sm rounded-lg text-left
                hover:bg-red-100 text-red-700 transition">
                                        <i class="fas fa-ban mr-2"></i>
                                        {{ __('designs.blocked') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-md p-10 text-center text-gray-500">
                <i class="fas fa-palette text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-semibold">{{ __('designs.no_designs_found') }}</p>
                <p class="text-sm mt-2">{{ __('designs.try_adjusting_search') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($designs->hasPages())
        <div class="mt-6">
            {{ $designs->links() }}
        </div>
    @endif

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
                menu.classList.add('z-50');
                menu.classList.remove('hidden');
            }
        }
    </script>
@endpush
