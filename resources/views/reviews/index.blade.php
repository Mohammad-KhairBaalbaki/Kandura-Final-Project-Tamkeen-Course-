@extends('layouts.dashboard')

@section('title', __('Reviews'))
@section('page-title', __('Reviews'))

@section('content')

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Reviews') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('reviews.manage_reviews') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">{{ __('reviews.all_reviews') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                            {{ __('reviews.order') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                            {{ __('reviews.user') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                            {{ __('reviews.rating') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                            {{ __('reviews.comment') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                            {{ __('reviews.date') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                            {{ __('reviews.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('orders.show', $review->order_id) }}"
                                    class="text-purple-600 hover:text-purple-700 font-semibold">
                                    #{{ $review->order->num ?? $review->order_id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $review->user->name ?? __('N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-yellow-600">
                                    {{ $review->rating }}/5
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $review->comment }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $review->created_at->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('orders.show', $review->order_id) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 hover:bg-purple-200 transition">
                                    {{ __('reviews.view_order') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-star text-4xl mb-3 text-gray-300"></i>
                                <p>{{ __('reviews.no_reviews') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif

@endsection
