@extends('layouts.admin')
@section('title', 'Ticket Scanner')

@section('content')
    <div class="w-full mb-8">
        <div class="bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-qrcode text-indigo-600"></i> Ticket Scanner
            </h1>
            <p class="text-gray-500 text-sm mt-1">Scan QR Code atau input manual kode tiket, lalu klik CHECK untuk check-in.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Kolom Kiri: Scanner & Input --}}
        <div class="space-y-6">
            {{-- QR Scanner --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Scan QR Code</h3>
                    <span class="flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Scanner Active
                    </span>
                </div>
                <div id="reader" class="overflow-hidden rounded-xl border-0 bg-black shadow-inner"></div>
                <p class="text-xs text-gray-400 text-center mt-3">Scan akan otomatis mengisi input di bawah</p>
            </div>

            {{-- Input Manual --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold mb-3 text-gray-500 uppercase tracking-wider">Kode Tiket</h3>
                <div class="flex gap-2">
                    <input type="text" id="ticket-code-input"
                        placeholder="INV-SILVER-J7CTN-XOD"
                        class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all font-mono uppercase"
                        onkeydown="if(event.key==='Enter') lookupTicket()">
                    <button onclick="lookupTicket()"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition-all font-bold">
                        <i class="fas fa-search mr-1"></i> CARI
                    </button>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Info Tiket & Tombol Check-in --}}
        <div class="space-y-6">
            {{-- Ticket Info Card --}}
            <div id="ticket-info-card" class="bg-white p-6 rounded-2xl shadow-sm border-2 border-gray-100 transition-all duration-300">
                <div class="flex justify-between items-center mb-5 border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Info Tiket</h3>
                    <span id="info-status-badge" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-400">
                        Belum ada tiket
                    </span>
                </div>

                <div class="space-y-4" id="ticket-details">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nama Pemegang</p>
                            <p id="info-holder" class="text-lg font-semibold text-gray-800">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Kategori</p>
                            <p id="info-category" class="text-lg font-bold text-indigo-600">-</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Event</p>
                        <p id="info-event" class="text-gray-700 font-medium">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Kode Tiket</p>
                        <p id="info-code" class="text-gray-700 font-mono text-sm">-</p>
                    </div>
                    <div id="info-checkedin-at" class="hidden">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Checked-in At</p>
                        <p id="info-checkedin-time" class="text-gray-700 font-medium text-sm">-</p>
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1 mt-3">Checked-in By</p>
                        <p id="info-checkedin-by" class="text-gray-700 font-medium text-sm">-</p>
                    </div>
                </div>

                {{-- Tombol CHECK --}}
                <div class="mt-6" id="checkin-btn-wrapper">
                    <button id="btn-checkin" onclick="doCheckIn()" class="hidden w-full py-4 rounded-xl font-bold text-white text-lg tracking-wider transition-all bg-green-500 hover:bg-green-600 shadow-lg hover:shadow-green-200">
                        <i class="fas fa-check-circle mr-2"></i> CHECK-IN
                    </button>
                </div>

                {{-- Result Message --}}
                <div id="result-msg" class="hidden mt-4 p-4 rounded-xl text-center font-semibold text-sm"></div>
            </div>
        </div>
    </div>

    <style>
        #reader video {
            object-fit: cover !important;
            transform: scaleX(-1) !important;
        }
    </style>
@endsection

@section('script')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let currentTicketCode = null;

    // QR Scanner — scan mengisi input field, tidak langsung checkin
    const html5QrCode = new Html5Qrcode("reader", { verbose: false });
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            html5QrCode.pause();
            const input = document.getElementById('ticket-code-input');
            input.value = decodedText.trim().toUpperCase();
            lookupTicket();
            setTimeout(() => html5QrCode.resume(), 3000);
        }
    );

    function lookupTicket() {
        const code = document.getElementById('ticket-code-input').value.trim().toUpperCase().replace(/\s+/g, '');
        if (!code) return;
        document.getElementById('ticket-code-input').value = code;

        resetResult();

        $.ajax({
            url: `/admin/ticket/lookup/${code}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(data) {
                currentTicketCode = data.ticket_code;
                document.getElementById('info-holder').innerText   = data.holder_name;
                document.getElementById('info-category').innerText = data.category;
                document.getElementById('info-event').innerText    = data.event;
                document.getElementById('info-code').innerText     = data.ticket_code;

                const badge      = document.getElementById('info-status-badge');
                const card       = document.getElementById('ticket-info-card');
                const btnCheckin = document.getElementById('btn-checkin');
                const checkedAt  = document.getElementById('info-checkedin-at');

                if (data.status === 'valid') {
                    badge.className    = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-green-100 text-green-700';
                    badge.innerText    = 'Valid';
                    card.className     = 'bg-white p-6 rounded-2xl shadow-sm border-2 border-green-400 transition-all duration-300';
                    btnCheckin.classList.remove('hidden');
                    checkedAt.classList.add('hidden');

                } else if (data.status === 'checked_in') {
                    badge.className    = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-yellow-100 text-yellow-700';
                    badge.innerText    = 'Sudah Check-in';
                    card.className     = 'bg-white p-6 rounded-2xl shadow-sm border-2 border-yellow-400 transition-all duration-300';
                    btnCheckin.classList.add('hidden');
                    checkedAt.classList.remove('hidden');
                    document.getElementById('info-checkedin-time').innerText = data.checked_in_at ?? '-';
                    document.getElementById('info-checkedin-by').innerText   = data.checked_in_by ?? '-';

                } else if (data.status === 'canceled') {
                    badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700';
                    badge.innerText = 'Dibatalkan';
                    card.className  = 'bg-white p-6 rounded-2xl shadow-sm border-2 border-red-400 transition-all duration-300';
                    btnCheckin.classList.add('hidden');
                }
            },
            error: function(xhr) {
                currentTicketCode = null;
                const msg = xhr.status === 401 ? 'Sesi admin habis, silakan login ulang.' : 'Kode tiket tidak ditemukan di sistem.';
                document.getElementById('info-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700';
                document.getElementById('info-status-badge').innerText = xhr.status === 401 ? 'Unauthorized' : 'Tidak Ditemukan';
                document.getElementById('ticket-info-card').className  = 'bg-white p-6 rounded-2xl shadow-sm border-2 border-red-400 transition-all duration-300';
                document.getElementById('btn-checkin').classList.add('hidden');
                showResult('error', msg);
            }
        });
    }

    function doCheckIn() {
        if (!currentTicketCode) return;

        $.ajax({
            url: `/admin/checkin/${currentTicketCode}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(data) {
                document.getElementById('btn-checkin').classList.add('hidden');
                document.getElementById('info-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-green-600 text-white';
                document.getElementById('info-status-badge').innerText = 'Check-in Berhasil!';
                document.getElementById('ticket-info-card').className  = 'bg-white p-6 rounded-2xl shadow-sm border-2 border-green-500 transition-all duration-300';
                showResult('success', '✅ ' + data.message);
                document.getElementById('ticket-code-input').value = '';
                currentTicketCode = null;
            },
            error: function(xhr) {
                showResult('error', '❌ ' + (xhr.responseJSON?.message ?? 'Gagal melakukan check-in.'));
            }
        });
    }

    function showResult(type, msg) {
        const el = document.getElementById('result-msg');
        el.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
        if (type === 'success') {
            el.classList.add('bg-green-100', 'text-green-800');
        } else {
            el.classList.add('bg-red-100', 'text-red-800');
        }
        el.innerText = msg;
        el.classList.remove('hidden');
    }

    function resetResult() {
        document.getElementById('result-msg').classList.add('hidden');
        document.getElementById('btn-checkin').classList.add('hidden');
        document.getElementById('info-holder').innerText   = '-';
        document.getElementById('info-category').innerText = '-';
        document.getElementById('info-event').innerText    = '-';
        document.getElementById('info-code').innerText     = '-';
        document.getElementById('info-status-badge').className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-400';
        document.getElementById('info-status-badge').innerText = 'Mencari...';
        document.getElementById('ticket-info-card').className  = 'bg-white p-6 rounded-2xl shadow-sm border-2 border-gray-100 transition-all duration-300';
        document.getElementById('info-checkedin-at').classList.add('hidden');
    }
</script>
@endsection
