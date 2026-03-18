@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-base font-semibold tracking-wide text-blue-600 uppercase">Discover</h2>
            <h1 class="mt-2 text-4xl font-extrabold text-gray-900 sm:text-5xl">
                Daftar Event Tersedia
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                Pilih event favoritmu dan amankan tiketnya sekarang sebelum kehabisan.
            </p>
        </div>

        @if($events->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada event</h3>
                <p class="mt-1 text-sm text-gray-500">Saat ini belum ada event yang membuka penjualan tiket.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:gap-x-8">
                
                @foreach($events as $event)
                    <div class="group relative bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">
                        
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 overflow-hidden">
                            {{-- Ganti src di bawah ini jika punya sistem upload poster event --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($event->name) }}&background=0D8ABC&color=fff&size=512" 
                                 alt="{{ $event->name }}" 
                                 class="w-full h-48 object-cover object-center group-hover:opacity-75 transition-opacity">
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ $event->name }}
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                                    Pesan tiketmu sekarang dan jadilah bagian dari keseruan {{ $event->name }}.
                                </p>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('checkout.step1', $event->slug) }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Beli Tiket
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