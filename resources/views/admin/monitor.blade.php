@extends('layouts.admin')
@section('title', 'Monitor Ticket')

@section('content')
    <div class="w-full mb-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    Check-in Monitor
                </h1>
                <p class="text-gray-500 text-sm">Monitoring aktivitas gate secara real-time.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <button onclick="window.location.reload()" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Refresh Monitor">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <span class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg text-xs font-bold border border-indigo-100 uppercase tracking-widest">
                    {{ $gate_name ?? 'All Gates' }}
                </span>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Checked-in --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg text-sm italic font-bold uppercase tracking-tighter text-[10px]">Total Check-in</div>
                </div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight">{{ number_format($stats['total_checked_in'] ?? 0) }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-medium italic uppercase tracking-wider">Attendees already in</p>
            </div>

            {{-- Remaining --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-lg text-sm italic font-bold uppercase tracking-tighter text-[10px]">Remaining</div>
                </div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight">{{ number_format($stats['remaining'] ?? 0) }}</h3>
                <p class="text-xs text-gray-400 mt-2 font-medium italic uppercase tracking-wider">From {{ number_format($stats['capacity'] ?? 0) }} capacity</p>
            </div>

            {{-- Avg per Hour --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg text-sm italic font-bold uppercase tracking-tighter text-[10px]">Per Hour</div>
                </div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight">{{ $stats['avg_per_hour'] ?? 0 }}</h3>
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-indigo-600 h-full transition-all duration-1000" style="width: {{ $stats['fill_percentage'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h2 class="font-black text-gray-400 uppercase text-[10px] tracking-[0.2em] mb-8">Check-in Flow (Per Hour)</h2>
            <div class="h-48 flex items-end justify-between gap-3 px-2 border-b border-gray-50">
                @if(isset($chart_data) && count($chart_data) > 0)
                    @foreach($chart_data as $data)
                        <div class="flex-1 bg-indigo-500/20 hover:bg-indigo-500 transition-all cursor-pointer group relative rounded-t-lg" 
                             style="height: {{ $data['percentage'] }}%">
                            <span class="absolute -top-10 left-1/2 -translate-x-1/2 text-[10px] font-bold opacity-0 group-hover:opacity-100 bg-gray-900 text-white px-2 py-1 rounded whitespace-nowrap z-10">
                                {{ $data['count'] }} Tickets
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center">
                        <p class="text-gray-300 text-xs italic font-medium tracking-widest uppercase">Waiting for data activity...</p>
                    </div>
                @endif
            </div>
            <div class="flex justify-between mt-4 text-[10px] text-gray-400 font-black uppercase tracking-widest px-1">
                @if(isset($chart_data))
                    @foreach($chart_data as $data) <span>{{ $data['hour'] }}</span> @endforeach
                @else
                    <span>08:00</span><span>12:00</span><span>16:00</span><span>20:00</span>
                @endif
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h2 class="font-black text-gray-400 uppercase text-[10px] tracking-[0.2em]">Recent Check-in Log</h2>
                <div class="flex items-center gap-2">
                     <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                     <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Live Updates Enabled</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.15em]">
                            <th class="px-8 py-5 font-black border-b border-gray-50">Ticket Code</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50">Category</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50 text-center">Checked-in At</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50 text-right">Checked-in By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs ?? [] as $log)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-8 py-4">
                                    <span class="font-mono text-sm font-bold text-indigo-600 tracking-tight group-hover:underline">
                                        #{{ $log->ticket_code }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter bg-gray-100 text-gray-600 border border-gray-200 group-hover:bg-white transition-colors">
                                        {{ $log->category_name }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="text-sm font-medium text-gray-500 tabular-nums">
                                        {{ is_object($log->checked_at) ? $log->checked_at->format('H:i:s') : $log->checked_at }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-xs font-bold text-gray-700 bg-white border border-gray-100 px-3 py-1 rounded shadow-sm">
                                        {{ $log->staff_name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-gray-400 italic font-medium uppercase tracking-widest text-xs">
                                    No data recorded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Halaman ini siap menerima update via Livewire atau Pusher.
        console.log('Monitor ready for production.');
    </script>
@endsection