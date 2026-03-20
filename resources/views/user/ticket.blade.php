@extends('layouts.user')
@section('title', 'Beli Tiket')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-10 text-center">
            <h2 class="text-3xl font-bold text-white">Daftar Event</h2>
            <p class="text-gray-400 mt-2">Pilih event favoritmu dan amankan tiketnya sekarang sebelum kehabisan.</p>
        </div>

        <div class="max-w-4xl mx-auto">

            @if ($events->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-white font-semibold">Belum ada event</p>
                    <p class="text-gray-400 text-sm mt-1">Saat ini belum ada event yang membuka penjualan tiket.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($events as $event)
                        <div class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden flex flex-col hover:border-yellow-400/50 transition-all group">
                            <div class="overflow-hidden">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://ui-avatars.com/api/?name=' . urlencode($event->name) . '&background=111&color=fec401&size=512' }}"
                                    alt="{{ $event->name }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-white font-bold text-lg mb-1">{{ $event->name }}</h3>
                                    <p class="text-gray-400 text-sm line-clamp-2">
                                        {{ $event->description ?? 'Pesan tiketmu sekarang dan jadilah bagian dari keseruan ' . $event->name . '.' }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-white/10">
                                    <a href="{{ route('checkout.step1', $event->slug) }}"
                                        class="w-full flex justify-center items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-sm py-3 rounded-xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                                        Beli Tiket <i class="fas fa-arrow-right"></i>
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
