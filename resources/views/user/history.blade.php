@extends('layouts.user')
@section('title', 'Riwayat Transaksi')

@php use App\Enums\TransactionStatusEnum; @endphp

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-8">
            <h2 class="text-3xl font-bold text-white">Riwayat Transaksi</h2>
        </div>

        <div class="max-w-4xl mx-auto">

            @if ($transactions->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-ticket-alt text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-gray-400 text-base">Kamu belum memiliki transaksi.</p>
                    <a href="{{ route('user.home') }}"
                        class="inline-block mt-4 bg-yellow-400 hover:bg-yellow-300 text-black font-bold px-6 py-2.5 rounded-full transition">
                        Beli Tiket Sekarang
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($transactions as $transaction)
                        @php
                            $status = $transaction->transaction_status;
                            $isPaid = $status === TransactionStatusEnum::PAID->value;
                            $isPending = $status === TransactionStatusEnum::PENDING->value;
                            $isFailed = $status === TransactionStatusEnum::FAILED->value;
                        @endphp

                        <div
                            class="bg-white/10 border rounded-2xl overflow-hidden transition
                            {{ $isPaid ? 'border-green-500/30 hover:border-green-400/50' : '' }}
                            {{ $isPending ? 'border-yellow-500/30 hover:border-yellow-400/50' : '' }}
                            {{ $isFailed ? 'border-red-500/30 hover:border-red-400/50' : '' }}">

                            {{-- Header --}}
                            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $transaction->invoice_code }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">
                                        {{ $transaction->created_at->format('d M Y, H:i') }} WIB</p>
                                </div>

                                @if ($isPaid)
                                    <span
                                        class="bg-green-500/20 border border-green-500/50 text-green-400 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>Lunas
                                    </span>
                                @elseif ($isPending)
                                    <span
                                        class="bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                                    </span>
                                @elseif ($isFailed)
                                    <span
                                        class="bg-red-500/20 border border-red-500/50 text-red-400 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i>Ditolak
                                    </span>
                                @endif
                            </div>

                            {{-- Body: PAID → tampilkan tiket --}}
                            @if ($isPaid && $transaction->tickets->isNotEmpty())
                                <div class="px-6 py-4">
                                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Detail
                                        Tiket</p>
                                    <div class="space-y-2">
                                        @foreach ($transaction->tickets as $ticket)
                                            <div
                                                class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                                                <div>
                                                    <p class="text-white text-sm font-semibold">
                                                        {{ $ticket->ticketCategory->name }}</p>
                                                    <p class="text-gray-500 text-xs font-mono">{{ $ticket->ticket_code }}
                                                    </p>
                                                </div>
                                                <span
                                                    class="text-gray-400 text-xs">{{ $ticket->ticketCategory->event->name ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Body: PENDING → info menunggu verifikasi + bukti bayar --}}
                            @elseif ($isPending)
                                <div class="px-6 py-4">
                                    <div
                                        class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4 flex items-start gap-3">
                                        <i class="fas fa-info-circle text-yellow-400 mt-0.5"></i>
                                        <div>
                                            <p class="text-yellow-300 text-sm font-semibold">Pembayaran sedang diverifikasi
                                            </p>
                                            <p class="text-yellow-300/70 text-xs mt-1">Tim kami sedang memeriksa bukti
                                                pembayaran Anda. Proses verifikasi biasanya 1x24 jam.</p>
                                        </div>
                                    </div>

                                    {{-- Item tiket yang dipesan --}}
                                    @if ($transaction->transactionItems->isNotEmpty())
                                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mt-4 mb-2">
                                            Tiket Dipesan</p>
                                        <div class="space-y-2">
                                            @foreach ($transaction->transactionItems as $item)
                                                <div
                                                    class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                                                    <div>
                                                        <p class="text-white text-sm font-semibold">
                                                            {{ $item->ticketCategory->name }}</p>
                                                        <p class="text-gray-500 text-xs">{{ $item->quantity }}x tiket</p>
                                                    </div>
                                                    <span class="text-gray-400 text-sm">Rp
                                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Body: FAILED → alasan penolakan --}}
                            @elseif ($isFailed)
                                <div class="px-6 py-4">
                                    <div
                                        class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 flex items-start gap-3">
                                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                                        <div>
                                            <p class="text-red-300 text-sm font-semibold">Pembayaran ditolak</p>
                                            @if ($transaction->rejection_reason)
                                                <p class="text-red-300/70 text-xs mt-1">
                                                    {{ $transaction->rejection_reason }}</p>
                                            @endif
                                            <p class="text-gray-500 text-xs mt-2">Kuota tiket Anda telah dikembalikan.
                                                Silakan lakukan pembelian ulang.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Footer --}}
                            <div class="flex items-center justify-between px-6 py-4 border-t border-white/10">
                                <div>
                                    <p class="text-gray-400 text-xs">Total</p>
                                    <p class="text-yellow-400 font-bold text-lg">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                </div>

                                @if ($isPaid)
                                    @php
                                        $event = $transaction->tickets->first()?->ticketCategory?->event;
                                        if ($event) {
                                            $startDt = \Carbon\Carbon::parse(
                                                $event->event_date->format('Y-m-d') .
                                                    ' ' .
                                                    $event->start_time->format('H:i:s'),
                                            )->setTimezone('Asia/Jakarta');
                                            $endDt = $event->end_time
                                                ? \Carbon\Carbon::parse(
                                                    $event->event_date->format('Y-m-d') .
                                                        ' ' .
                                                        $event->end_time->format('H:i:s'),
                                                )->setTimezone('Asia/Jakarta')
                                                : $startDt->copy()->addHours(2);
                                            $gcalUrl =
                                                'https://calendar.google.com/calendar/render?action=TEMPLATE' .
                                                '&text=' .
                                                urlencode($event->name) .
                                                '&dates=' .
                                                $startDt->format('Ymd\THis') .
                                                '/' .
                                                $endDt->format('Ymd\THis') .
                                                '&location=' .
                                                urlencode($event->location) .
                                                '&details=' .
                                                urlencode('Tiket: ' . $transaction->invoice_code);
                                        }
                                    @endphp
                                    <div class="flex flex-col items-center gap-2">
                                        @if (isset($gcalUrl))
                                            <a href="{{ $gcalUrl }}" target="_blank"
                                                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2.5 rounded-full transition font-semibold">
                                                <i class="fas fa-calendar-plus"></i>
                                                Add to Calendar
                                            </a>
                                        @endif
                                        <a href="{{ route('ticket.download', $transaction->invoice_code) }}"
                                            class="flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-sm px-5 py-2.5 rounded-full transition hover:-translate-y-0.5 active:translate-y-0">
                                            <i class="fas fa-file-pdf"></i>
                                            Download E-Ticket
                                        </a>
                                    </div>
                                @elseif ($isPending)
                                    <span
                                        class="flex items-center gap-2 bg-yellow-500/20 text-yellow-400 text-sm px-5 py-2.5 rounded-full font-semibold">
                                        <i class="fas fa-hourglass-half"></i>
                                        Menunggu Verifikasi
                                    </span>
                                @elseif ($isFailed)
                                    <a href="{{ route('user.ticket') }}"
                                        class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm px-5 py-2.5 rounded-full transition font-semibold">
                                        <i class="fas fa-redo"></i>
                                        Beli Ulang
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
