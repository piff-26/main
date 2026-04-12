@extends('layouts.user')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-3xl mx-auto mb-8 text-center">
            <h2 class="text-3xl font-bold text-white">TRANSAKSI</h2>
            <p class="text-gray-400 mt-2">Ensure your personal information is accurate for E-ticket purposes.</p>

            @if (session('warning'))
                <div
                    class="inline-flex items-center gap-2 bg-orange-400/20 border border-orange-400/50 text-orange-300 rounded-xl px-4 py-3 mt-4 text-sm">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                </div>
            @endif

            <div
                class="flex flex-wrap items-center justify-center gap-2 bg-yellow-400/20 border border-yellow-400/50 text-yellow-300 rounded-xl px-4 py-3 mt-4 text-sm text-center">
                <span><i class="fas fa-clock shrink-0"></i> Complete your registration and payment before
                    <strong>{{ \Carbon\Carbon::parse($expiredAt)->setTimezone('Asia/Jakarta')->format('H:i') }}
                        (UTC+7)</strong>
                    to avoid ticket forfeiture.</span>
                <span class="w-full text-sm text-yellow-300">Your local time: <strong id="expired-local"></strong></span>
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

            const expiredUtc = '{{ \Carbon\Carbon::parse($expiredAt)->utc()->toISOString() }}';
            const d = new Date(expiredUtc);
            document.getElementById('expired-local').textContent = d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                timeZoneName: 'short'
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('transaction-expired', () => {
                Swal.fire({
                    title: 'Transaksi Expired',
                    text: 'Waktu pembayaran telah habis. Silakan buat transaksi baru.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444',
                    background: '#0f172a',
                    color: '#f1f5f9',
                    allowOutsideClick: false,
                }).then(() => {
                    window.location.href = '{{ route('user.ticket') }}';
                });
            });
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
