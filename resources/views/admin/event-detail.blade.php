@extends('layouts.admin')
@section('title', 'Event Detail - ' . $event->name)

@section('content')

{{-- Header --}}
<div class="w-full mb-6">
    <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.event') }}" class="hover:text-blue-500 transition">Manage Events</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-700 font-medium">Event Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $event->name }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-2">
                <span><i class="fas fa-calendar mr-1" style="color:#27b4f7"></i>{{ $event->event_date->format('d M Y') }}</span>
                <span><i class="fas fa-clock mr-1" style="color:#27b4f7"></i>{{ $event->start_time->format('H:i') }} - {{ $event->end_time ? $event->end_time->format('H:i') : '-' }}</span>
                <span><i class="fas fa-map-marker-alt mr-1" style="color:#27b4f7"></i>{{ $event->location }}</span>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.event.export-excel', $event->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-sm transition shadow-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('admin.event') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

{{-- Overall Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:#eff9ff">
                <i class="fas fa-money-bill-wave" style="color:#27b4f7"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</span>
        </div>
        <div class="text-2xl font-extrabold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-50">
                <i class="fas fa-ticket-alt text-green-500"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tickets Sold</span>
        </div>
        <div class="text-2xl font-extrabold text-gray-800">{{ $totalTicketsSold }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-purple-50">
                <i class="fas fa-receipt text-purple-500"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transactions</span>
        </div>
        <div class="text-2xl font-extrabold text-gray-800">{{ $totalTransactions }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-yellow-50">
                <i class="fas fa-check-double text-yellow-500"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Check-ins</span>
        </div>
        <div class="text-2xl font-extrabold text-gray-800">{{ $totalCheckins }}</div>
    </div>
</div>

{{-- Ticket Categories --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
        <i class="fas fa-tags" style="color:#27b4f7"></i> Ticket Categories
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($categoryStats as $cat)
        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition bg-gray-50/50">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="font-bold text-gray-800 text-base">{{ $cat['name'] }}</h3>
                    <span class="text-sm font-semibold" style="color:#27b4f7">Rp {{ number_format($cat['price'], 0, ',', '.') }}</span>
                </div>
                @php $pct = $cat['quota'] > 0 ? round(($cat['sold_count'] / $cat['quota']) * 100, 1) : 0; @endphp
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $pct >= 100 ? 'bg-red-100 text-red-700' : ($pct >= 75 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                    {{ $pct }}%
                </span>
            </div>

            {{-- Progress bar sold --}}
            <div class="mb-1 flex justify-between text-xs text-gray-500">
                <span>Sold: {{ $cat['sold_count'] }} / {{ $cat['quota'] }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="h-2 rounded-full transition-all duration-500 {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 75 ? 'bg-yellow-400' : 'bg-green-400') }}"
                     style="width:{{ min($pct, 100) }}%"></div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm">
                <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                    <div class="text-xs text-gray-400 mb-0.5">Revenue</div>
                    <div class="font-bold text-gray-800 text-xs">Rp {{ number_format($cat['revenue'], 0, ',', '.') }}</div>
                </div>
                <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                    <div class="text-xs text-gray-400 mb-0.5">Tickets Sold</div>
                    <div class="font-bold text-gray-800">{{ $cat['tickets_sold'] }}</div>
                </div>
                <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                    <div class="text-xs text-gray-400 mb-0.5">Transactions</div>
                    <div class="font-bold text-gray-800">{{ $cat['transactions'] }}</div>
                </div>
                <div class="bg-white rounded-lg p-2 text-center border border-gray-100">
                    <div class="text-xs text-gray-400 mb-0.5">Check-ins</div>
                    <div class="font-bold text-gray-800">{{ $cat['checkins'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Tickets Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-list-ul" style="color:#27b4f7"></i> All Tickets
            <span id="ticketCount" class="text-xs font-normal text-gray-400 ml-1"></span>
        </h2>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Filter status --}}
            <select id="filterStatus"
                    class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white text-gray-700">
                <option value="">All Status</option>
                <option value="checked_in">Checked In</option>
                <option value="not_checked_in">Not Checked In</option>
            </select>
            {{-- Filter kategori --}}
            <select id="filterCategory"
                    class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white text-gray-700">
                <option value="">All Categories</option>
                @foreach($categoryStats as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
            </select>
            {{-- Search --}}
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchTicket" placeholder="Search name / code..."
                       class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-52">
            </div>
            {{-- Export JS --}}
            <button id="btnExportExcel"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-sm transition shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="ticketsTable">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap cursor-pointer select-none" data-col="ticket_code">
                        Ticket Code <i class="fas fa-sort ml-1 text-gray-300"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap cursor-pointer select-none" data-col="holder_name">
                        Holder Name <i class="fas fa-sort ml-1 text-gray-300"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap cursor-pointer select-none" data-col="category_name">
                        Category <i class="fas fa-sort ml-1 text-gray-300"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap cursor-pointer select-none" data-col="buyer_name">
                        Buyer <i class="fas fa-sort ml-1 text-gray-300"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap cursor-pointer select-none" data-col="is_checked_in">
                        Status <i class="fas fa-sort ml-1 text-gray-300"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Checked In At</th>
                </tr>
            </thead>
            <tbody id="ticketsBody" class="divide-y divide-gray-100">
                {{-- Diisi via JS --}}
            </tbody>
        </table>
        <div id="ticketsEmpty" class="hidden text-center py-12 text-gray-400">
            <i class="fas fa-ticket-alt text-4xl mb-3 block"></i>
            <p class="text-sm">No tickets found.</p>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
        <span class="text-sm text-gray-500" id="paginationInfo"></span>
        <div class="flex gap-1" id="paginationBtns"></div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
const allTickets = {!! json_encode($tickets->values()) !!};

// State
let filtered = [...allTickets];
let sortCol  = null;
let sortDir  = 1; // 1 = asc, -1 = desc
let page     = 1;
const pageSize = 20;

// --- FILTER & SEARCH ---
function applyFilters() {
    const status   = document.getElementById('filterStatus').value;
    const category = document.getElementById('filterCategory').value;
    const search   = document.getElementById('searchTicket').value.toLowerCase().trim();

    filtered = allTickets.filter(t => {
        if (status === 'checked_in'     && !t.is_checked_in) return false;
        if (status === 'not_checked_in' &&  t.is_checked_in) return false;
        if (category && String(t.category_id) !== String(category)) return false;
        if (search) {
            const haystack = [t.ticket_code, t.holder_name, t.buyer_name, t.buyer_email, t.invoice_code, t.category_name]
                .join(' ').toLowerCase();
            if (!haystack.includes(search)) return false;
        }
        return true;
    });

    if (sortCol) applySort(false);
    page = 1;
    render();
}

// --- SORT ---
function applySort(toggle = true) {
    if (!sortCol) return;
    filtered.sort((a, b) => {
        let va = a[sortCol], vb = b[sortCol];
        if (typeof va === 'boolean') { va = va ? 1 : 0; vb = vb ? 1 : 0; }
        else { va = String(va ?? '').toLowerCase(); vb = String(vb ?? '').toLowerCase(); }
        if (va < vb) return -1 * sortDir;
        if (va > vb) return  1 * sortDir;
        return 0;
    });
}

// --- RENDER TABLE ---
function render() {
    const tbody = document.getElementById('ticketsBody');
    const empty = document.getElementById('ticketsEmpty');
    const total = filtered.length;

    document.getElementById('ticketCount').textContent = `(${total} tiket)`;

    if (total === 0) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
        document.getElementById('paginationInfo').textContent = '';
        document.getElementById('paginationBtns').innerHTML = '';
        return;
    }
    empty.classList.add('hidden');

    const start  = (page - 1) * pageSize;
    const slice  = filtered.slice(start, start + pageSize);
    const pages  = Math.ceil(total / pageSize);

    tbody.innerHTML = slice.map((t, i) => {
        const rowNum = start + i + 1;
        const checkinBadge = t.is_checked_in
            ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700"><i class="fas fa-check-circle"></i> Checked In</span>`
            : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500"><i class="fas fa-times-circle"></i> Not Checked In</span>`;

        return `<tr class="hover:bg-blue-50/30 transition">
            <td class="px-4 py-3 text-gray-400 text-xs">${rowNum}</td>
            <td class="px-4 py-3 font-mono text-gray-800 text-xs whitespace-nowrap">${esc(t.ticket_code)}</td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">${esc(t.holder_name)}</td>
            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                <span class="px-2 py-0.5 text-xs rounded-full font-medium" style="background:#eff9ff;color:#0369a1">${esc(t.category_name)}</span>
            </td>
            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                <div class="font-medium">${esc(t.buyer_name)}</div>
                <div class="text-xs text-gray-400">${esc(t.buyer_email)}</div>
            </td>
            <td class="px-4 py-3 font-mono text-xs text-gray-500 whitespace-nowrap">
                <a href="/admin/transaction/detail/${esc(t.invoice_code)}" class="hover:text-blue-500 transition" target="_blank">${esc(t.invoice_code)}</a>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">${checkinBadge}</td>
            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">${t.checked_in_at ?? '-'}</td>
        </tr>`;
    }).join('');

    // Pagination info
    document.getElementById('paginationInfo').textContent =
        `Showing ${start + 1}–${Math.min(start + pageSize, total)} of ${total}`;

    // Pagination buttons
    const btnContainer = document.getElementById('paginationBtns');
    let btns = '';
    const range = paginationRange(page, pages);
    range.forEach(p => {
        if (p === '...') {
            btns += `<span class="px-2 py-1 text-gray-400 text-sm">…</span>`;
        } else {
            btns += `<button onclick="goPage(${p})"
                class="px-3 py-1 rounded-lg text-sm font-semibold transition ${p === page ? 'text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'}"
                style="${p === page ? 'background:#27b4f7' : ''}">${p}</button>`;
        }
    });
    btnContainer.innerHTML =
        `<button onclick="goPage(${page - 1})" ${page === 1 ? 'disabled' : ''} class="px-3 py-1 rounded-lg text-sm text-gray-500 hover:bg-gray-100 disabled:opacity-30"><i class="fas fa-chevron-left"></i></button>`
        + btns +
        `<button onclick="goPage(${page + 1})" ${page === pages ? 'disabled' : ''} class="px-3 py-1 rounded-lg text-sm text-gray-500 hover:bg-gray-100 disabled:opacity-30"><i class="fas fa-chevron-right"></i></button>`;
}

function goPage(p) {
    const pages = Math.ceil(filtered.length / pageSize);
    if (p < 1 || p > pages) return;
    page = p;
    render();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function paginationRange(current, total) {
    if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
    const r = [1];
    if (current > 3) r.push('...');
    for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) r.push(i);
    if (current < total - 2) r.push('...');
    r.push(total);
    return r;
}

function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// --- SORT HEADERS ---
document.querySelectorAll('th[data-col]').forEach(th => {
    th.addEventListener('click', () => {
        const col = th.dataset.col;
        if (sortCol === col) {
            sortDir = -sortDir;
        } else {
            sortCol = col;
            sortDir = 1;
        }
        // Reset icons
        document.querySelectorAll('th[data-col] i').forEach(i => i.className = 'fas fa-sort ml-1 text-gray-300');
        th.querySelector('i').className = `fas fa-sort-${sortDir === 1 ? 'up' : 'down'} ml-1 text-blue-400`;
        applySort();
        page = 1;
        render();
    });
});

// --- EVENT LISTENERS ---
document.getElementById('filterStatus').addEventListener('change',   applyFilters);
document.getElementById('filterCategory').addEventListener('change', applyFilters);
document.getElementById('searchTicket').addEventListener('input',    applyFilters);

// --- EXPORT EXCEL ---
document.getElementById('btnExportExcel').addEventListener('click', () => {
    const data = [
        ['No', 'Ticket Code', 'Holder Name', 'Category', 'Buyer Name', 'Email', 'Invoice', 'Status', 'Checked In At'],
        ...filtered.map((t, i) => [
            i + 1,
            t.ticket_code,
            t.holder_name,
            t.category_name,
            t.buyer_name,
            t.buyer_email,
            t.invoice_code,
            t.is_checked_in ? 'Checked In' : 'Not Checked In',
            t.checked_in_at ?? '-',
        ])
    ];

    const ws = XLSX.utils.aoa_to_sheet(data);
    // Column widths
    ws['!cols'] = [4,20,24,14,24,28,18,16,22].map(w => ({wch: w}));
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Tickets');
    XLSX.writeFile(wb, `Tickets_{{ str_replace(' ', '_', $event->name) }}_${new Date().toISOString().slice(0,10)}.xlsx`);
});

// Init
render();
</script>
@endsection
