@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Selesaikan Pembayaran</h2>
            <p class="mt-2 text-sm text-gray-600">Langkah 4 dari 4: Pilih metode pembayaran Anda via Midtrans.</p>
        </div>

        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Batas Waktu Pembayaran</h3>
                    <p class="text-sm text-red-700 mt-1">
                        Selesaikan pembayaran sebelum <strong>{{ \Carbon\Carbon::parse($transaction->expired_at)->format('d M Y, H:i') }} WIB</strong> atau pesanan otomatis dibatalkan.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Detail Tagihan</h3>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Menunggu Pembayaran
                </span>
            </div>
            
            <div class="px-6 py-5">
                <dl class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Nomor Invoice</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $transaction->invoice_code }}</dd>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Nama Pemesan</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $transaction->buyer_name }}</dd>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <dt class="text-base font-bold text-gray-900">Total Pembayaran</dt>
                        <dd class="text-2xl font-extrabold text-blue-600">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="text-center">
            <button id="pay-button" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 border border-transparent rounded-lg shadow-md text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Bayar Sekarang
            </button>
            <p class="mt-4 text-sm text-gray-500 flex items-center justify-center">
                <svg class="h-4 w-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                Secured by Midtrans
            </p>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ env('MIDTRANS_IS_PRODUCTION') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // Panggil Snap Popup dengan Snap Token dari Controller
        snap.pay('{{ $snapToken }}', {
            // Callback jika pembayaran sukses
            onSuccess: function (result) {
                // Redirect user ke halaman riwayat transaksi 
                window.location.href = "{{ route('user.tickets') }}"; // Sesuaikan nama route history
            },
            
            // Callback jika user pilih metode bayar tapi belum bayar (Pending, cth: Transfer Bank)
            onPending: function (result) {
                alert("Menunggu pembayaran Anda!");
                window.location.reload();
            },
            
            // Callback jika pembayaran gagal
            onError: function (result) {
                alert("Pembayaran gagal! Silakan coba lagi.");
                window.location.reload();
            },
            
            // Callback jika user menutup popup sebelum bayar
            onClose: function () {
                alert('Anda menutup popup sebelum menyelesaikan pembayaran. Segera bayar sebelum waktu habis!');
            }
        });
    };
</script>
@endsection