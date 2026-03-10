@extends('layouts.admin')
@section('title', 'Insight')

@section('content')
    <div class="w-full mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Insight Dashboard
                </h1>
                <p class="text-gray-500 text-sm">Analisis distribusi pembeli dan sumber informasi.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- City Distribution Chart --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">City Distribution</h2>
            <p class="text-sm text-gray-500 mb-6">Top kota dengan jumlah pembeli terbanyak.</p>
            <div class="relative h-[300px]">
                {{-- Canvas untuk Chart.js --}}
                <canvas id="cityChart"></canvas>
            </div>
        </div>

        {{-- Source Info Progress Bars --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Source Info</h2>
            <p class="text-sm text-gray-500 mb-6">Bagaimana audiens mengetahui event ini?</p>
            
            <div class="space-y-6">
                @forelse($sources ?? [] as $source)
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ $source->name }}</span>
                        <span class="text-sm font-semibold text-indigo-600">{{ $source->percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-1000" 
                             style="width: {{ $source->percentage }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">{{ number_format($source->total) }} Orang</p>
                </div>
                @empty
                {{-- Placeholder jika data kosong --}}
                <div class="text-center py-10">
                    <p class="text-gray-400 italic text-sm">Belum ada data sumber informasi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('cityChart').getContext('2d');
            
            // Ambil data dari Controller (Atau gunakan dummy jika variabel kosong)
            const labels = {!! json_encode($city_labels ?? ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Malang']) !!};
            const dataValues = {!! json_encode($city_values ?? [0, 0, 0, 0, 0]) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Pembeli',
                        data: dataValues,
                        backgroundColor: '#6366f1',
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: { stepSize: 20 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endsection