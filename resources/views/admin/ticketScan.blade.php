@extends('layouts.admin')
@section('title', 'Ticket Scanner')

@section('content')
    <div class="w-full mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-qrcode text-indigo-600"></i> Ticket Scanner
                </h1>
                <p class="text-gray-500 text-sm">Arahkan kamera ke QR Code untuk check-in otomatis.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Kolom Kiri: Scanner & Manual Input --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Scan QR Code</h3>
                    <span id="scanner-status" class="flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Scanner Active
                    </span>
                </div>
                
                <div id="reader" class="overflow-hidden rounded-xl border-0 bg-black shadow-inner"></div>
                
                <div class="mt-4 flex items-center justify-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center gap-1"><i class="fas fa-lightbulb text-yellow-400"></i> Pastikan cahaya terang</div>
                    <div class="flex items-center gap-1"><i class="fas fa-expand text-blue-400"></i> QR Code harus di tengah</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold mb-3 text-gray-500 uppercase tracking-wider">Input Manual</h3>
                <div class="flex gap-2">
                    <input type="text" id="manual-code" placeholder="Contoh: TICKET-001" 
                        class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all font-mono">
                    <button onclick="processManual()" class="bg-gray-800 text-white px-6 py-3 rounded-xl hover:bg-black transition-all font-bold">
                        CHECK
                    </button>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status & Info --}}
        <div class="space-y-6">
            {{-- Status Card (Visual Utama) --}}
            <div id="status-card" class="hidden p-8 rounded-2xl border-2 flex flex-col items-center text-center transition-all duration-300">
                <div id="status-icon" class="text-6xl mb-4"></div>
                <h2 id="status-title" class="text-2xl font-bold mb-2"></h2>
                <p id="status-message" class="text-sm font-medium mb-4"></p>
                
                {{-- Auto Check-in Loaders --}}
                <div id="auto-processing" class="hidden w-full items-center justify-center gap-2 p-3 bg-white/50 rounded-lg font-bold animate-pulse text-sm">
                    <i class="fas fa-spinner fa-spin"></i> SYNCING TO SERVER...
                </div>
                <div id="checkin-success-msg" class="hidden w-full items-center justify-center gap-2 p-3 bg-green-600 text-white rounded-lg font-bold text-sm shadow-lg">
                    <i class="fas fa-check-double"></i> CHECK-IN SUCCESSFUL
                </div>
            </div>

            {{-- Ticket Detail Card --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" id="info-card">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Ticket Detail</h3>
                    <span id="info-status" class="px-3 py-1 rounded-full text-[10px] font-black uppercase border tracking-widest">-</span>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Buyer Name</p>
                            <p id="info-name" class="text-lg font-semibold text-gray-800 truncate">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-bold">Category</p>
                            <p id="info-category" class="text-lg font-bold text-indigo-600">-</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Event</p>
                        <p id="info-event" class="text-gray-700 font-medium">-</p>
                    </div>

                    <div id="checked-info" class="hidden p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-blue-600 font-bold uppercase">Last Activity</span>
                            <span class="text-xs text-blue-800 font-mono">Gate 1 • 12:45</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #reader video { 
            object-fit: cover !important; 
            transform: scaleX(-1) !important; 
            -webkit-transform: scaleX(-1) !important;
        }
        .bg-success-animate { animation: successPulse 0.5s ease-in-out; }
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
    </style>
@endsection

@section('script')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader", { verbose: false, disableFlip: true });
        const config = { fps: 20, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

        // Dummy Data untuk testing
        const dummyData = {
            "TICKET-001": { name: "Budi Santoso", event: "Music Festival 2024", category: "VIP", status: "valid" },
            "TICKET-002": { name: "Siti Aminah", event: "Music Festival 2024", category: "Reguler", status: "checked-in" },
            "TICKET-003": { name: "Andi Wijaya", event: "Music Festival 2024", category: "VVIP", status: "canceled" }
        };

        const onScanSuccess = (decodedText) => {
            html5QrCode.pause(); // Stop scan sementara biar gak double read
            processTicket(decodedText);
        };

        function processManual() {
            const code = document.getElementById('manual-code').value.toUpperCase().trim();
            if(code) {
                html5QrCode.pause();
                processTicket(code);
                document.getElementById('manual-code').value = '';
            }
        }

        const processTicket = (code) => {
            const ticket = dummyData[code];
            const autoProc = document.getElementById('auto-processing');
            const successMsg = document.getElementById('checkin-success-msg');
            
            // Sembunyikan pesan sukses sebelumnya jika ada
            successMsg.classList.add('hidden');
            autoProc.classList.add('hidden');
            
            if (!ticket) {
                showStatus("invalid");
                resetInfo();
                resumeScanner(3000);
            } else {
                updateInfo(ticket);
                
                if (ticket.status === 'checked-in') {
                    showStatus("already");
                    resumeScanner(3000);
                } else if (ticket.status === 'canceled') {
                    showStatus("canceled");
                    resumeScanner(3000);
                } else {
                    // JIKA VALID -> JALANKAN AUTO CHECK-IN
                    showStatus("valid");
                    autoProc.classList.remove('hidden', 'flex'); 
                    autoProc.classList.add('flex');

                    // Simulasi delay kirim ke server (1 detik)
                    setTimeout(() => {
                        autoProc.classList.remove('flex');
                        autoProc.classList.add('hidden');
                        successMsg.classList.remove('hidden', 'flex');
                        successMsg.classList.add('flex');
                        
                        // Update status di dummy (lokal) agar tidak bisa di-scan lagi
                        dummyData[code].status = 'checked-in';
                        updateInfo(dummyData[code]);
                        
                        // Flash animasi sukses
                        document.getElementById('status-card').classList.add('bg-success-animate');
                        
                        resumeScanner(2500); // Stand-by lagi
                    }, 1200);
                }
            }
        };

        function showStatus(type) {
            const card = document.getElementById('status-card');
            const icon = document.getElementById('status-icon');
            const title = document.getElementById('status-title');
            const msg = document.getElementById('status-message');

            card.classList.remove('hidden');
            
            const states = {
                invalid: { 
                    class: 'bg-red-50 border-red-500 text-red-700', 
                    icon: '🎫', title: 'TICKET NOT FOUND', msg: 'Kode tiket tidak terdaftar di sistem kami.' 
                },
                already: { 
                    class: 'bg-yellow-50 border-yellow-500 text-yellow-700', 
                    icon: '🚫', title: 'ALREADY CHECKED-IN', msg: 'Tiket ini sudah dipindai sebelumnya.' 
                },
                canceled: { 
                    class: 'bg-gray-100 border-gray-400 text-gray-600', 
                    icon: '🛑', title: 'TICKET CANCELED', msg: 'Tiket ini telah dibatalkan oleh admin.' 
                },
                valid: { 
                    class: 'bg-green-50 border-green-500 text-green-700', 
                    icon: '✨', title: 'TICKET VALID', msg: 'Harap tunggu, memproses check-in...' 
                }
            };

            const s = states[type];
            card.className = `p-8 rounded-2xl border-2 flex flex-col items-center text-center transition-all ${s.class}`;
            icon.innerText = s.icon;
            title.innerText = s.title;
            msg.innerText = s.msg;
        }

        function updateInfo(data) {
            document.getElementById('info-event').innerText = data.event;
            document.getElementById('info-name').innerText = data.name;
            document.getElementById('info-category').innerText = data.category;
            
            const statusEl = document.getElementById('info-status');
            const checkedInfo = document.getElementById('checked-info');
            
            statusEl.innerText = data.status;
            
            if(data.status === 'valid') {
                statusEl.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700 border-green-200 tracking-widest";
                checkedInfo.classList.add('hidden');
            } else if(data.status === 'checked-in') {
                statusEl.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase bg-yellow-100 text-yellow-700 border-yellow-200 tracking-widest";
                checkedInfo.classList.remove('hidden');
            } else {
                statusEl.className = "px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-700 border-red-200 tracking-widest";
                checkedInfo.classList.add('hidden');
            }
        }

        function resetInfo() {
            document.getElementById('info-event').innerText = "-";
            document.getElementById('info-name').innerText = "-";
            document.getElementById('info-category').innerText = "-";
            document.getElementById('info-status').innerText = "-";
            document.getElementById('info-status').className = "px-3 py-1 rounded-full text-[10px] font-black uppercase bg-gray-100 border-gray-200 tracking-widest";
            document.getElementById('checked-info').classList.add('hidden');
        }

        function resumeScanner(delay) {
            setTimeout(() => { 
                html5QrCode.resume(); 
                document.getElementById('status-card').classList.add('hidden');
                document.getElementById('status-card').classList.remove('bg-success-animate');
            }, delay);
        }

        // Start Camera
        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
    </script>
@endsection