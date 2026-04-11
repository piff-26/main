@extends('layouts.admin')
@section('title', 'Monitor Ticket')

@section('content')
    <div class="w-full mb-8">
        {{-- Header Section --}}
        <div
            class="flex flex-col md:flex-row items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    Check-in Monitor
                </h1>
                <p class="text-gray-500 text-sm">Monitoring aktivitas gate secara real-time.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <select id="filter-event" onchange="filterByEvent(this.value)"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Events</option>
                    @foreach ($events as $id => $name)
                        <option value="{{ $name }}" {{ request('event') == $name ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                <button onclick="window.location.reload()" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors"
                    title="Refresh Monitor">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-[10px] font-black text-green-600 uppercase mb-2">Total Check-in</div>
                <h3 id="stat-checked-in" class="text-4xl font-black text-gray-800">
                    {{ number_format($stats['total_checked_in']) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-[10px] font-black text-orange-600 uppercase mb-2">Remaining</div>
                <h3 id="stat-remaining" class="text-4xl font-black text-gray-800">{{ number_format($stats['remaining']) }}
                </h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-[10px] font-black text-blue-600 uppercase mb-2">Fill Rate</div>
                <h3 id="stat-percent" class="text-4xl font-black text-gray-800">{{ $stats['fill_percentage'] }}%</h3>
            </div>
        </div>

        <h2 class="font-black text-gray-400 uppercase text-[10px] tracking-[0.2em] mb-4">Per Category Statistics</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach ($categoryStats as $cat)
                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-bold text-gray-400 uppercase mb-1">{{ $cat->name }}</div>
                    <div class="flex items-baseline gap-1">
                        {{-- ID unik menggunakan slug kategori --}}
                        <span id="cat-checkin-{{ Str::slug($cat->name) }}"
                            class="text-xl font-black text-gray-700">{{ $cat->checked_in }}</span>
                        <span class="text-xs text-gray-400">/ <span
                                id="cat-total-{{ Str::slug($cat->name) }}">{{ $cat->total }}</span></span>
                    </div>
                    {{-- Progress bar mini per kategori --}}
                    <div class="w-full bg-gray-200 h-1 rounded-full mt-2 overflow-hidden">
                        <div id="cat-progress-{{ Str::slug($cat->name) }}"
                            class="bg-indigo-500 h-full transition-all duration-500"
                            style="width: {{ $cat->total > 0 ? ($cat->checked_in / $cat->total) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chart Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h2 class="font-black text-gray-400 uppercase text-[10px] tracking-[0.2em] mb-8">Check-in Flow (Per Hour)</h2>
            <div class="h-48 flex items-end justify-between gap-3 px-2 border-b border-gray-50">
                @if (isset($chart_data) && count($chart_data) > 0)
                    @foreach ($chart_data as $data)
                        <div class="flex-1 bg-indigo-500/20 hover:bg-indigo-500 transition-all cursor-pointer group relative rounded-t-lg"
                            style="height: {{ $data['percentage'] }}%">
                            <span
                                class="absolute -top-10 left-1/2 -translate-x-1/2 text-[10px] font-bold opacity-0 group-hover:opacity-100 bg-gray-900 text-white px-2 py-1 rounded whitespace-nowrap z-10">
                                {{ $data['count'] }} Tickets
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center">
                        <p class="text-gray-300 text-xs italic font-medium tracking-widest uppercase">Waiting for data
                            activity...</p>
                    </div>
                @endif
            </div>
            <div class="flex justify-between mt-4 text-[10px] text-gray-400 font-black uppercase tracking-widest px-1">
                @if (isset($chart_data))
                    @foreach ($chart_data as $data)
                        <span>{{ $data['hour'] }}</span>
                    @endforeach
                @else
                    <span>08:00</span><span>12:00</span><span>16:00</span><span>20:00</span>
                @endif
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h2 class="font-black text-gray-400 uppercase text-[10px] tracking-[0.2em]">Recent Check-in Log</h2>
                <div class="flex items-center gap-2" id="ws-status">
                    <div class="h-2 w-2 rounded-full bg-yellow-400 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-yellow-400 uppercase tracking-tighter">Connecting...</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.15em]">
                            <th class="px-8 py-5 font-black border-b border-gray-50">Ticket Code</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50">Holder</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50">Event / Category</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50 text-center">Checked-in At</th>
                            <th class="px-8 py-5 font-black border-b border-gray-50 text-right">Checked-in By</th>
                        </tr>
                    </thead>
                    <tbody id="log-tbody" class="divide-y divide-gray-50">
                        @forelse($logs ?? [] as $log)
                            <tr class="hover:bg-indigo-50/30 transition-colors group" data-event="{{ $log->event_name }}">
                                <td class="px-8 py-4">
                                    <span
                                        class="font-mono text-sm font-bold text-indigo-600 tracking-tight group-hover:underline">
                                        #{{ $log->ticket_code }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-sm font-semibold text-gray-800">{{ $log->holder_name }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="block text-xs font-semibold text-gray-700">{{ $log->event_name }}</span>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter bg-gray-100 text-gray-500 border border-gray-200">
                                        {{ $log->category_name }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="text-sm font-medium text-gray-500 tabular-nums">
                                        {{ is_object($log->checked_at) ? $log->checked_at->format('H:i:s') : $log->checked_at }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span
                                        class="text-xs font-bold text-gray-700 bg-white border border-gray-100 px-3 py-1 rounded shadow-sm">
                                        {{ $log->staff_name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-row">
                                <td colspan="5"
                                    class="px-8 py-20 text-center text-gray-400 italic font-medium uppercase tracking-widest text-xs">
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
    <script src="https://cdn.ably.com/lib/ably.min-2.js"></script>
    <script>
        const ablyPublicKey = '{{ env('ABLY_PUBLIC_KEY') }}';
        const ably = new Ably.Realtime(ablyPublicKey);
        const channel = ably.channels.get('public:checkin-monitor');

        channel.subscribe('ticket.checked-in', function(msg) {
            const data = msg.data;
            const activeFilter = document.getElementById('filter-event').value;

            if (!activeFilter || data.event_name === activeFilter) {
                // Update Total Check-in
                const elTotal = document.getElementById('stat-checked-in');
                let newTotal = parseInt(elTotal.innerText.replace(/\./g, '')) + 1;
                elTotal.innerText = newTotal.toLocaleString('id-ID');

                // Update Remaining
                const elRemain = document.getElementById('stat-remaining');
                let newRemain = Math.max(0, parseInt(elRemain.innerText.replace(/\./g, '')) - 1);
                elRemain.innerText = newRemain.toLocaleString('id-ID');

                const catSlug = data.category_name.toLowerCase().replace(/ /g, '-');
                const elCatCheckin = document.getElementById(`cat-checkin-${catSlug}`);
                const elCatTotal = document.getElementById(`cat-total-${catSlug}`);
                const elCatProgress = document.getElementById(`cat-progress-${catSlug}`);

                if (elCatCheckin && elCatTotal) {
                    let newCatCount = parseInt(elCatCheckin.innerText) + 1;
                    elCatCheckin.innerText = newCatCount;

                    // Update mini progress bar
                    let totalCat = parseInt(elCatTotal.innerText);
                    let newPercent = (newCatCount / totalCat) * 100;
                    elCatProgress.style.width = newPercent + '%';
                }
            }

            // Prepend row
            const tbody = document.getElementById('log-tbody');
            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-indigo-50/30 transition-colors group bg-green-50/20';
            tr.dataset.event = data.event_name;
            if (activeFilter && data.event_name !== activeFilter) tr.style.display = 'none';
            tr.innerHTML = `
                <td class="px-8 py-4">
                    <span class="font-mono text-sm font-bold text-indigo-600 tracking-tight">#${data.ticket_code}</span>
                </td>
                <td class="px-8 py-4">
                    <span class="text-sm font-semibold text-gray-800">${data.holder_name}</span>
                </td>
                <td class="px-8 py-4">
                    <span class="block text-xs font-semibold text-gray-700">${data.event_name}</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter bg-gray-100 text-gray-500 border border-gray-200">${data.category_name}</span>
                </td>
                <td class="px-8 py-4 text-center">
                    <span class="text-sm font-medium text-gray-500 tabular-nums">${data.checked_in_at}</span>
                </td>
                <td class="px-8 py-4 text-right">
                    <span class="text-xs font-bold text-gray-700 bg-white border border-gray-100 px-3 py-1 rounded shadow-sm">${data.checked_in_by}</span>
                </td>
            `;
            tbody.insertBefore(tr, tbody.firstChild);
            setTimeout(() => tr.classList.remove('bg-green-50/20'), 3000);
        });

        function filterByEvent(eventName) {
            const url = new URL(window.location.href);

            if (eventName) {
                url.searchParams.set('event', eventName);
            } else {
                url.searchParams.delete('event');
            }

            // Redirect ke URL baru
            window.location.href = url.toString();
        }

        ably.connection.on('connected', () => {
            document.getElementById('ws-status').innerHTML =
                '<div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div><span class="text-[10px] font-bold text-green-500 uppercase tracking-tighter">Live</span>';
        });
        ably.connection.on('disconnected', () => {
            document.getElementById('ws-status').innerHTML =
                '<div class="h-2 w-2 rounded-full bg-red-500"></div><span class="text-[10px] font-bold text-red-400 uppercase tracking-tighter">Disconnected</span>';
        });
    </script>
@endsection
