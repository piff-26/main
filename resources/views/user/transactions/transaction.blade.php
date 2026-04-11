@extends('layouts.user')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-3xl mx-auto mb-8 text-center">
            <h2 class="text-3xl font-bold text-white">TRANSAKSI</h2>
            <p class="text-gray-400 mt-2">Pastikan data diri Anda benar untuk keperluan E-Ticket.</p>

            @if (session('warning'))
                <div
                    class="inline-flex items-center gap-2 bg-orange-400/20 border border-orange-400/50 text-orange-300 rounded-xl px-4 py-3 mt-4 text-sm">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                </div>
            @endif

            <div
                class="flex flex-wrap items-center justify-center gap-2 bg-yellow-400/20 border border-yellow-400/50 text-yellow-300 rounded-xl px-4 py-3 mt-4 text-sm text-center">
                <span><i class="fas fa-clock shrink-0"></i> Selesaikan pengisian dan pembayaran sebelum
                    <strong>{{ \Carbon\Carbon::parse($expiredAt)->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</strong>
                    agar kuota tiket tidak hangus.</span>
            </div>

            <div class="mt-4">
            </div>
        </div>

        <div class="max-w-3xl mx-auto">
            @livewire('checkout-biodata', ['invoice_code' => $invoiceCode])
        </div>

    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lenis !== 'undefined') lenis.destroy();
        });

        function confirmCancel() {
            Swal.fire({
                title: 'Batalkan Transaksi?',
                text: 'Transaksi akan dibatalkan dan kuota tiket dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#475569',
                background: '#0f172a',
                color: '#f1f5f9',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('cancel-transaction');
                }
            });
        }
    </script>
@endsection
