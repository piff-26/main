@extends('layouts.user')
@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-8">
            <h2 class="text-3xl font-bold text-white">Riwayat Transaksi</h2>
            {{-- <p class="text-gray-400 mt-1">Tiket yang sudah kamu beli akan muncul di sini.</p> --}}
        </div>

        <div class="max-w-4xl mx-auto">

            @if ($transactions->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-ticket-alt text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-gray-400 text-base">Kamu belum memiliki transaksi yang berhasil.</p>
                    <a href="{{ route('user.home') }}"
                        class="inline-block mt-4 bg-yellow-400 hover:bg-yellow-300 text-black font-bold px-6 py-2.5 rounded-full transition">
                        Beli Tiket Sekarang
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($transactions as $transaction)
                        <div
                            class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden hover:border-yellow-400/50 transition">

                            {{-- Header --}}
                            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $transaction->invoice_code }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">
                                        {{ $transaction->created_at->format('d M Y, H:i') }} WIB</p>
                                </div>
                                <span
                                    class="bg-green-500/20 border border-green-500/50 text-green-400 text-xs font-semibold px-3 py-1 rounded-full">
                                    Lunas
                                </span>
                            </div>

                            {{-- Tiket --}}
                            <div class="px-6 py-4">
                                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Detail Tiket
                                </p>
                                <div class="space-y-2">
                                    @foreach ($transaction->tickets as $ticket)
                                        <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                                            <div>
                                                <p class="text-white text-sm font-semibold">
                                                    {{ $ticket->ticketCategory->name }}</p>
                                                <p class="text-gray-500 text-xs">{{ $ticket->ticket_code }}</p>
                                            </div>
                                            <span class="text-gray-400 text-xs">
                                                {{ $ticket->ticketCategory->event->name ?? '-' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="flex items-center justify-between px-6 py-4 border-t border-white/10">
                                <div>
                                    <p class="text-gray-400 text-xs">Total Bayar</p>
                                    <p class="text-yellow-400 font-bold text-lg">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('ticket.download', $transaction->invoice_code) }}"
                                    class="flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-sm px-5 py-2.5 rounded-full transition hover:-translate-y-0.5 active:translate-y-0">
                                    <i class="fas fa-file-pdf"></i>
                                    Download E-Ticket
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
