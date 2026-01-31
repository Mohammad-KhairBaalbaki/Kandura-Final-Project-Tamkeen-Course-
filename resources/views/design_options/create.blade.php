@extends('layouts.dashboard')

@section('title', __('design_options.add_new_design_option'))
@section('page-title', __('design_options.add_new_design_option'))

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('design_options.add_new_design_option') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('design_options.design_option_details') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('design_options.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('design_options.all_design_options') }}
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
        <form method="POST" action="{{ route('design_options.store') }}">
            @csrf
            @method('POST')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('design_options.type') }}
                    </label>
                    <select name="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="">{{ __('design_options.select_type') }}</option>
                        <option value="color" {{ old('type') === 'color' ? 'selected' : '' }}>
                            {{ __('design_options.type_color') }}
                        </option>
                        <option value="dome" {{ old('type') === 'dome' ? 'selected' : '' }}>
                            {{ __('design_options.type_dome') }}
                        </option>
                        <option value="fabric" {{ old('type') === 'fabric' ? 'selected' : '' }}>
                            {{ __('design_options.type_fabric') }}
                        </option>
                        <option value="sleeve" {{ old('type') === 'sleeve' ? 'selected' : '' }}>
                            {{ __('design_options.type_sleeve') }}
                        </option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('design_options.status') }}
                    </label>
                    <select name="is_active"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>
                            {{ __('design_options.active') }}
                        </option>
                        <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>
                            {{ __('design_options.inactive') }}
                        </option>
                    </select>
                    @error('is_active')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name EN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('design_options.name_en') }}
                    </label>
                    <input type="text" name="name[en]" value="{{ old('name.en') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('name.en')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name AR -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('design_options.name_ar') }}
                    </label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('name.ar')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6 space-x-3">
                <a href="{{ route('design_options.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    {{ __('design_options.cancel') }}
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('design_options.add') }}
                </button>
            </div>
        </form>
    </div>

@endsection
