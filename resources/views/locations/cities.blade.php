@extends('layouts.dashboard')

@section('title', __('locations.city_orders_distribution'))
@section('page-title', __('locations.city_orders_distribution'))

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ __('locations.orders_by_city') }}</h2>
                    <p class="text-sm text-gray-600">
                        {{ __('locations.total_orders') }}: <span class="font-semibold">{{ $totalOrders }}</span>
                    </p>
                </div>
            </div>
            <div class="h-96 flex items-center justify-center">
                <canvas id="citiesChart" class="max-w-[340px] max-h-[340px]"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-10">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('locations.city_breakdown') }}</h3>
            <div class="space-y-3">
                @forelse ($labels as $index => $label)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full"
                                style="background-color: {{ $colors[$index] ?? '#E5E7EB' }};"></span>
                            <span class="text-gray-700">{{ $label }}</span>
                        </div>
                        <div class="text-gray-600">
                            {{ $counts[$index] }} ({{ $percentages[$index] }}%)
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 text-center py-8">
                        {{ __('locations.no_city_orders') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const cityLabels = @json($labels);
        const cityPercentages = @json($percentages);
        const cityCounts = @json($counts);
        const colors = @json($colors);

        const ctx = document.getElementById('citiesChart');
        if (ctx && cityLabels.length > 0) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: cityLabels,
                    datasets: [{
                        data: cityPercentages,
                        backgroundColor: colors,
                        radius: '85%',
                        hoverOffset: 6,
                        borderColor: '#fff',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 8
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const count = cityCounts[index] ?? 0;
                                    const percent = context.parsed ?? 0;
                                    return `${context.label}: ${count} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
