@extends('layouts.user')
@section('title', 'Event Ticket')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-10 text-center">
            <h2 class="text-3xl font-bold text-white">Our Event</h2>
            <p class="text-gray-400 mt-2">Choose your ticket category and secure your seat now.</p>
        </div>

        <div class="max-w-4xl mx-auto">

            @if ($events->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-white font-semibold">No event available</p>
                    <p class="text-gray-400 text-sm mt-1">Currently there are no events available.</p>
                </div>
            @else
                <div class="flex flex-col gap-6">
                    @foreach ($events as $event)
                        <div
                            class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden flex flex-col md:flex-row hover:border-yellow-400/50 transition-all group">
                            <div class="overflow-hidden w-full md:w-96 shrink-0">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://ui-avatars.com/api/?name=' . urlencode($event->name) . '&background=111&color=fec401&size=512' }}"
                                    alt="{{ $event->name }}"
                                    class="w-full h-full aspect-video md:aspect-auto object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4 md:p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-lg md:text-xl mb-2">{{ $event->name }}</h3>
                                    <p class="text-gray-400 text-sm line-clamp-3">
                                        {{ $event->description ?? 'Order your ticket now and be part of the excitement for the ' . $event->name . '.' }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-white/10">
                                    <a href="{{ route('checkout.step1', $event->slug) }}"
                                        class="inline-flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-sm px-6 py-3 rounded-xl transition-all hover:-translate-y-0.5 active:translate-y-0 w-full md:w-auto">
                                        View Event <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Available Online Passes Section --}}
            @php
                $onlineTickets = \App\Models\OnlineTicket::where('is_active', true)->get();
            @endphp
            @if($onlineTickets->count() > 0)
                <div class="mt-12 text-center">
                    <h2 class="text-3xl font-bold text-white mb-6">Online Event Pass</h2>
                </div>
                <div class="flex flex-col gap-6">
                    @foreach ($onlineTickets as $ticket)
                        <div class="bg-white/10 border border-[#ff5b1d]/30 rounded-2xl overflow-hidden flex flex-col md:flex-row hover:border-[#ff5b1d]/60 transition-all group relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#ff5b1d]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            @if($ticket->image)
                            <div class="overflow-hidden w-full md:w-96 shrink-0 relative z-10">
                                <img src="{{ asset('storage/' . $ticket->image) }}"
                                    alt="{{ $ticket->name }}"
                                    class="w-full h-full aspect-video md:aspect-auto object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            @endif

                            <div class="p-6 md:p-8 flex-1 flex flex-col justify-between relative z-10">
                                <div>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#ff5b1d]/20 text-[#ff5b1d] text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
                                        <i class="fas fa-play-circle"></i> Digital Access
                                    </div>
                                    <h3 class="text-white font-bold text-2xl mb-2">{{ $ticket->name }}</h3>
                                    <p class="text-gray-400 text-sm line-clamp-3 mb-2">
                                        {{ $ticket->description ?? 'Dapatkan akses untuk menonton seluruh karya film di PIFF 2026 secara online.' }}
                                    </p>
                                    <p class="text-yellow-400 font-bold">IDR {{ number_format($ticket->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-6 pt-6 border-t border-white/10">
                                    <a href="{{ route('online-pass.step1', $ticket->slug) }}"
                                        class="inline-flex items-center justify-center gap-2 bg-[#ff5b1d] hover:bg-[#e04a10] text-white font-bold text-sm px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-[#ff5b1d]/20 hover:shadow-[#ff5b1d]/40 w-full md:w-auto transform hover:-translate-y-0.5">
                                        View Details <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
