@extends('layouts.admin')
@section('title', 'System Log')

@section('content')
    <div class="w-full mb-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100 mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">System Log</h1>
                <p class="text-gray-500 text-sm mt-0.5">Email, ticket generation, dan check-in activity.</p>
            </div>
            <form method="GET" action="{{ route('admin.log') }}" class="flex flex-wrap gap-2">
                <select name="type" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Types</option>
                    <option value="email"   {{ request('type') === 'email'   ? 'selected' : '' }}>Email</option>
                    <option value="ticket"  {{ request('type') === 'ticket'  ? 'selected' : '' }}>Ticket</option>
                    <option value="checkin" {{ request('type') === 'checkin' ? 'selected' : '' }}>Check-in</option>
                </select>
                <select name="status" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Status</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed"  {{ request('status') === 'failed'  ? 'selected' : '' }}>Failed</option>
                    <option value="info"    {{ request('status') === 'info'    ? 'selected' : '' }}>Info</option>
                </select>
                @if(request('type') || request('status'))
                    <a href="{{ route('admin.log') }}" class="px-3 py-2 text-xs font-bold text-gray-400 hover:text-gray-600 border border-gray-200 rounded-lg bg-white transition">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Summary Cards --}}
        @php
            $total   = \App\Models\SystemLog::count();
            $success = \App\Models\SystemLog::where('status', 'success')->count();
            $failed  = \App\Models\SystemLog::where('status', 'failed')->count();
        @endphp
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Logs</p>
                <p class="text-3xl font-black text-gray-800">{{ number_format($total) }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-green-100 shadow-sm">
                <p class="text-[10px] font-black text-green-500 uppercase tracking-widest mb-1">Success</p>
                <p class="text-3xl font-black text-green-600">{{ number_format($success) }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Failed</p>
                <p class="text-3xl font-black text-red-500">{{ number_format($failed) }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.15em] border-b border-gray-100">
                            <th class="px-6 py-4 font-black">Time</th>
                            <th class="px-6 py-4 font-black">Type</th>
                            <th class="px-6 py-4 font-black">Status</th>
                            <th class="px-6 py-4 font-black">Message</th>
                            <th class="px-6 py-4 font-black">Reference</th>
                            <th class="px-6 py-4 font-black">Context</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors {{ $log->status === 'failed' ? 'bg-red-50/30' : '' }}">
                                <td class="px-6 py-3 text-xs text-gray-400 tabular-nums whitespace-nowrap">
                                    {{ $log->created_at->format('d M, H:i:s') }}
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $typeColor = match($log->type) {
                                            'email'   => 'bg-blue-100 text-blue-600',
                                            'ticket'  => 'bg-purple-100 text-purple-600',
                                            'checkin' => 'bg-green-100 text-green-600',
                                            default   => 'bg-gray-100 text-gray-500',
                                        };
                                        $typeIcon = match($log->type) {
                                            'email'   => 'fa-envelope',
                                            'ticket'  => 'fa-ticket',
                                            'checkin' => 'fa-circle-check',
                                            default   => 'fa-circle-info',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase {{ $typeColor }}">
                                        <i class="fa-solid {{ $typeIcon }}"></i>
                                        {{ $log->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($log->status === 'success')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-green-100 text-green-600">
                                            <i class="fa-solid fa-check"></i> Success
                                        </span>
                                    @elseif ($log->status === 'failed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-red-100 text-red-600">
                                            <i class="fa-solid fa-xmark"></i> Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-gray-100 text-gray-500">
                                            <i class="fa-solid fa-circle-info"></i> Info
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700 max-w-xs">
                                    {{ $log->message }}
                                </td>
                                <td class="px-6 py-3">
                                    @if ($log->reference)
                                        <span class="font-mono text-xs text-indigo-600 font-bold">{{ $log->reference }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @if ($log->context)
                                        <button onclick="this.nextElementSibling.classList.toggle('hidden')"
                                            class="text-[10px] font-bold text-gray-400 hover:text-indigo-500 border border-gray-200 px-2 py-1 rounded transition">
                                            View
                                        </button>
                                        <pre class="hidden mt-1 text-[10px] bg-gray-50 border border-gray-100 rounded p-2 text-gray-600 max-w-xs overflow-auto">{{ json_encode($log->context, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400 text-xs italic uppercase tracking-widest">
                                    No logs recorded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
