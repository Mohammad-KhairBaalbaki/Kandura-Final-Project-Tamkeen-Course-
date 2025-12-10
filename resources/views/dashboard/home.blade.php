@extends('layouts.dashboard')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

    <!-- Total Orders -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('Total Orders') }}</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] ?? 0 }}</h3>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +12% {{ __('from last month') }}
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-2xl text-blue-500"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('Total Revenue') }}</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +18% {{ __('from last month') }}
                </p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-2xl text-green-500"></i>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('Total Users') }}</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</h3>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> +8% {{ __('from last month') }}
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-2xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Total Designs -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-pink-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('Total Designs') }}</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_perfumes'] ?? 0 }}</h3>
                <p class="text-xs text-gray-600 mt-2">
                    {{ $stats['active_perfumes'] ?? 0 }} {{ __('Active') }}
                </p>
            </div>
            <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center">
                <i class="fas fa-palette text-2xl"></i>
            </div>
        </div>
    </div>

</div>

<!-- Recent Activities & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-clock text-purple-600 mr-2"></i>
                    {{ __('Latest Operations') }}
                </h2>
                {{-- {{ route('orders.index') }} --}}
                <a href="" class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('Order ID') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('Total') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($latest_orders ?? [] as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            {{-- {{ route('orders.show', $order->id) }} --}}
                            <a href="" class="text-purple-600 hover:text-purple-700 font-semibold">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-semibold mr-3">
                                    {{ substr($order->user->first_name, 0, 1) }}{{ substr($order->user->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->user->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-800">{{ number_format($order->total, 2) }} {{ $order->currency->code ?? 'SYP' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __(ucfirst($order->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $order->created_at->format('d M, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>{{ __('No orders yet') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt text-purple-600 mr-2"></i>
            {{ __('Quick Actions') }}
        </h2>
        <div class="space-y-3">

            {{-- {{ route('users.create') }} --}}
            <a href="" class="flex items-center justify-between p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200">
                        <i class="fas fa-user-plus text-blue-600"></i>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-purple-600">{{ __('Add User') }}</span>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600"></i>
            </a>


            {{-- {{ route('packages.create') }} --}}
            <a href="" class="flex items-center justify-between p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200">
                        <i class="fas fa-sliders-h text-lg w-5"></i>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-purple-600">{{ __('Create Design Option') }}</span>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600"></i>
            </a>


            {{-- {{ route('orders.create') }} --}}
            <a href="" class="flex items-center justify-between p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200">
                        <i class="fas fa-ticket-alt text-lg w-5"></i>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-purple-600">{{ __('New Coupon') }}</span>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600"></i>
            </a>





        </div>
    </div>

</div>

<!-- Recent Reviews & Top Selling Designs -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Reviews -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-star text-purple-600 mr-2"></i>
                    {{ __('Recent Reviews') }}
                </h2>
                {{-- {{ route('reviews.index') }} --}}
                <a href="" class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6 space-y-4">
            @forelse($latest_reviews ?? [] as $review)
            <div class="flex items-start space-x-4 pb-4 border-b last:border-b-0">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                    {{ substr($review->user->first_name, 0, 1) }}{{ substr($review->user->last_name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-semibold text-gray-800">{{ $review->user->first_name }} {{ $review->user->last_name }}</h4>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-purple-600 mb-2">{{ $review->perfume->name['en'] ?? $review->perfume->name }}</p>
                    <p class="text-sm text-gray-600">{{ Str::limit($review->body, 100) }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-comment-slash text-4xl mb-3 text-gray-300"></i>
                <p>{{ __('No reviews yet') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Top Selling Designs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-fire text-purple-600 mr-2"></i>
                    {{ __('Top Selling Designs') }}
                </h2>
                {{-- {{ route('perfumes.index') }} --}}
                <a href="" class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6 space-y-4">
            @forelse($top_perfumes ?? [] as $perfume)
            <div class="flex items-center justify-between pb-4 border-b last:border-b-0">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-spray-can text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $perfume->name['en'] ?? $perfume->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $perfume->sku }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-purple-600">{{ $perfume->sales_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ __('Sales') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spray-can text-4xl mb-3 text-gray-300"></i>
                <p>{{ __('No sales data yet') }}</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
