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
                            class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden flex flex-row hover:border-yellow-400/50 transition-all group">
                            <div class="overflow-hidden w-72 shrink-0">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://ui-avatars.com/api/?name=' . urlencode($event->name) . '&background=111&color=fec401&size=512' }}"
                                    alt="{{ $event->name }}"
                                    class="w-full h-full object-cover aspect-video group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-xl mb-2">{{ $event->name }}</h3>
                                    <p class="text-gray-400 text-sm line-clamp-3">
                                        {{ $event->description ?? 'Pesan tiketmu sekarang dan jadilah bagian dari keseruan ' . $event->name . '.' }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-white/10">
                                    <a href="{{ route('checkout.step1', $event->slug) }}"
                                        class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-sm px-6 py-3 rounded-xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                                        View Event <i class="fas fa-arrow-right"></i>
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
