@extends('layouts.dashboard')

@section('title', __('designs.design_details'))
@section('page-title', __('designs.design_details'))

@section('content')

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('designs.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ __('designs.back_to_designs') }}
        </a>
    </div>

    <!-- Design Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $design->getTranslation('name', app()->getLocale()) }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ $design->getTranslation('description', app()->getLocale()) }}
                </p>
            </div>
            <div class="mt-4 lg:mt-0 flex items-center space-x-3">
                @php
                    $statusColors = [
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-gray-100 text-gray-700',
                        'blocked' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <div class="relative inline-block text-left">
                    <button type="button" onclick="toggleStatusMenu()"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition
                        {{ $statusColors[$design->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ __(ucfirst($design->status ?? '')) }}
                        <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                    </button>

                    <div id="status-menu" class="hidden fixed z-50 w-36 bg-white rounded-xl shadow-lg border p-2">
                        <form method="POST" action="{{ route('designs.updateStatus', $design->id) }}">
                            @csrf
                            @method('PUT')

                            <button name="status" value="active"
                                class="w-full px-3 py-2 text-sm rounded-lg text-left
                                hover:bg-green-100 text-green-800 transition">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ __('designs.active') }}
                            </button>

                            <button name="status" value="blocked"
                                class="w-full px-3 py-2 mt-1 text-sm rounded-lg text-left
                                hover:bg-red-100 text-red-800 transition">
                                <i class="fas fa-ban mr-2"></i>
                                {{ __('designs.blocked') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xl font-bold text-gray-900">
                        {{ number_format($design->price ?? 0, 2) }}
                    </div>
                    <div class="text-xs text-gray-500">{{ __('USD') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xl font-bold text-purple-700">
                        {{ $design->sales_count ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-500">{{ __('Sales') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gallery -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-images text-purple-600 mr-2"></i>
                        {{ __('designs.gallery') }}
                    </h3>
                </div>
                <div class="p-6">
                    @if ($design->images->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($design->images as $image)
                                <button type="button" onclick="openGalleryModal()">
                                    <img src="{{ $image->fullUrl }}" alt=""
                                        class="w-full h-56 object-cover rounded-lg border hover:opacity-90 transition">
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-image text-4xl mb-3 text-gray-300"></i>
                            <p>{{ __('designs.no_images') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Creator -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-user text-purple-600 mr-2"></i>
                        {{ __('designs.creator') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        @if ($design->user && $design->user->image && $design->user->image->fullUrl)
                            <img src="{{ $design->user->image->fullUrl }}"
                                class="w-12 h-12 rounded-full object-cover border" alt="{{ $design->user->name }}">
                        @else
                            <div
                                class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-semibold">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $design->user->first_name ?? '' }}
                                {{ $design->user->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="space-y-5 text-sm">
                        <p class="flex items-center text-gray-600">
                            <i class="fas fa-envelope w-5 text-purple-600"></i>
                            <span>{{ $design->user->email ?? 'N/A' }}</span>
                        </p>
                        <p class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-5 text-purple-600"></i>
                            <span>{{ $design->user->phone ?? 'N/A' }}</span>
                        </p>
                        <p class="flex items-center text-gray-600">
                            <i class="fas fa-shopping-bag w-5 text-purple-600"></i>
                            <span>{{ $design->user->ordersCount ?? 0 }} {{ __('Designs') }}</span>
                        </p>
                    </div>
                    <a href="{{ route('users.show', $design->user->id) }}"
                        class="mt-4 block text-center px-4 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition text-sm font-medium">
                        {{ __('View Profile') }}
                    </a>
                </div>
            </div>
            <!-- Design Options -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-sliders-h text-purple-600 mr-2"></i>
                        {{ __('designs.options') }}
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $optionsByType = $design->designOptions->groupBy('type');
                        $optionTypes = [
                            'color' => __('design_options.type_color'),
                            'fabric' => __('design_options.type_fabric'),
                            'dome' => __('design_options.type_dome'),
                            'sleeve' => __('design_options.type_sleeve'),
                        ];
                    @endphp
                    <div class="space-y-3">
                        @foreach ($optionTypes as $typeKey => $typeLabel)
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-semibold text-gray-600 min-w-[70px]">
                                    {{ $typeLabel }}:
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($optionsByType->get($typeKey, collect()) as $option)
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ $option->getTranslation('name', app()->getLocale()) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">-</span>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Measurements -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-ruler-combined text-purple-600 mr-2"></i>
                        {{ __('designs.measurements') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @forelse ($design->measurements as $measurement)
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                {{ $measurement->size }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-400">-</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div id="gallery-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">{{ __('designs.gallery') }}</h3>
                <button type="button" onclick="closeGalleryModal()"
                    class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-1 text-[10px]"></i>
                    Close
                </button>
            </div>
            <div class="p-6">
                @if ($design->images->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($design->images as $image)
                            <img src="{{ $image->fullUrl }}" alt=""
                                class="w-full h-64 object-contain bg-gray-50 rounded-lg border">
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-image text-4xl mb-3 text-gray-300"></i>
                        <p>{{ __('designs.no_images') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleStatusMenu() {
            const menu = document.getElementById('status-menu');
            const button = event.currentTarget;
            const isOpen = !menu.classList.contains('hidden');

            document
                .querySelectorAll('[id^="status-menu"]')
                .forEach(el => el.classList.add('hidden'));

            if (!isOpen) {
                const rect = button.getBoundingClientRect();
                menu.style.top = `${rect.bottom + 8}px`;
                menu.style.left = `${rect.left}px`;
                menu.classList.remove('hidden');
            }
        }

        function openGalleryModal() {
            const modal = document.getElementById('gallery-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeGalleryModal() {
            const modal = document.getElementById('gallery-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('click', event => {
            const modal = document.getElementById('gallery-modal');
            if (modal && event.target === modal) {
                closeGalleryModal();
            }
        });
    </script>
@endpush
