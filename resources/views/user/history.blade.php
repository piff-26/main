@extends('layouts.user')
@section('title', 'Transaction History')

@php use App\Enums\TransactionStatusEnum; @endphp

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-8">
            <h2 class="text-3xl font-bold text-white">Transaction History</h2>
        </div>

        <div class="max-w-4xl mx-auto">

            @if ($transactions->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-ticket-alt text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-gray-400 text-base">You have no transactions yet.</p>
                    <a href="{{ route('user.ticket') }}"
                        class="inline-block mt-4 bg-yellow-400 hover:bg-yellow-300 text-black font-bold px-6 py-2.5 rounded-full transition">
                        Buy Tickets Now
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
                                        <i class="fas fa-check-circle mr-1"></i>Paid
                                    </span>
                                @elseif ($isPending)
                                    <span
                                        class="bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>Pending Verification
                                    </span>
                                @elseif ($isFailed)
                                    <span
                                        class="bg-red-500/20 border border-red-500/50 text-red-400 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i>Rejected
                                    </span>
                                @endif
                            </div>

                            {{-- Body: PAID --}}
                            {{-- Body: PAID --}}
                            @if ($isPaid)
                                <div class="px-6 py-4">
                                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Order Details</p>
                                    <div class="space-y-2">
                                        @if($transaction->tickets->isNotEmpty())
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
                                        @endif
                                        @foreach ($transaction->transactionItems as $item)
                                            @if ($item->online_ticket_id && $item->onlineTicket)
                                                <div
                                                    class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                                                    <div>
                                                        <p class="text-white text-sm font-semibold">
                                                            Online Pass - {{ $item->onlineTicket->name }}</p>
                                                        <p class="text-gray-500 text-xs font-mono">Digital Access (Terkoneksi Akun)
                                                        </p>
                                                    </div>
                                                    <a href="{{ route('user.online_event') }}"
                                                        class="text-[#ff5b1d] hover:text-[#e04a10] text-xs font-bold transition">Watch Now &rarr;</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Body: PENDING --}}
                            @elseif ($isPending)
                                <div class="px-6 py-4">
                                    <div
                                        class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4 flex items-start gap-3">
                                        <i class="fas fa-info-circle text-yellow-400 mt-0.5"></i>
                                        <div>
                                            <p class="text-yellow-300 text-sm font-semibold">Payment is being verified</p>
                                            <p class="text-yellow-300/70 text-xs mt-1">Our team is reviewing your payment
                                                proof. Verification usually takes up to 24 hours.</p>
                                        </div>
                                    </div>

                                    @if ($transaction->transactionItems->isNotEmpty())
                                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mt-4 mb-2">
                                            Order Summary</p>
                                        <div class="space-y-2">
                                            @foreach ($transaction->transactionItems as $item)
                                                <div
                                                    class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2.5">
                                                    <div>
                                                        <p class="text-white text-sm font-semibold">
                                                            {{ $item->ticketCategory ? $item->ticketCategory->name : ('Online Pass - ' . ($item->onlineTicket->name ?? '')) }}</p>
                                                        <p class="text-gray-500 text-xs">{{ $item->quantity }}x {{ $item->ticket_category_id ? 'ticket' : 'pass' }}</p>
                                                    </div>
                                                    <span class="text-gray-400 text-sm">IDR
                                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Body: FAILED --}}
                            @elseif ($isFailed)
                                <div class="px-6 py-4">
                                    <div
                                        class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 flex items-start gap-3">
                                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                                        <div>
                                            <p class="text-red-300 text-sm font-semibold">Payment rejected</p>
                                            @if ($transaction->rejection_reason)
                                                <p class="text-red-300/70 text-xs mt-1">
                                                    {{ $transaction->rejection_reason }}</p>
                                            @endif
                                            <p class="text-gray-500 text-xs mt-2">To have a new ticket, please make a new
                                                purchase.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Footer --}}
                            <div class="flex items-center justify-between px-6 py-4 border-t border-white/10">
                                <div>
                                    @if ($transaction->voucher)
                                        <p class="text-gray-500 text-xs">Voucher: <span
                                                class="text-green-400 font-semibold">{{ $transaction->voucher->code }}</span>
                                        </p>
                                        <p class="text-gray-500 text-xs">Discount: <span
                                                class="text-green-400 font-semibold">- IDR
                                                {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span></p>
                                    @endif
                                    <p class="text-gray-400 text-xs">Total</p>
                                    <p class="text-yellow-400 font-bold text-lg">IDR
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
                                                urlencode('Ticket: ' . $transaction->invoice_code);
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
                                            {{ $transaction->tickets->isNotEmpty() ? 'Download E-Ticket' : 'Download Invoice' }}
                                        </a>
                                    </div>
                                @elseif ($isPending)
                                    <span
                                        class="flex items-center gap-2 bg-yellow-500/20 text-yellow-400 text-sm px-5 py-2.5 rounded-full font-semibold">
                                        <i class="fas fa-hourglass-half"></i>
                                        Pending Verification
                                    </span>
                                @elseif ($isFailed)
                                    <a href="{{ route('user.ticket') }}"
                                        class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm px-5 py-2.5 rounded-full transition font-semibold">
                                        <i class="fas fa-redo"></i>
                                        Buy Again
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
