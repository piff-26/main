@extends('layouts.user')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-2xl mx-auto mb-8 text-center">
            <h2 class="text-3xl font-bold text-white">{{ $event->name }}</h2>
            <p class="text-gray-400 mt-2">Pilih kategori dan jumlah tiket yang ingin dibeli.</p>
        </div>

        <div class="max-w-2xl mx-auto">

            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-300 rounded-lg px-4 py-3 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.storeStep1', $event->slug) }}" method="POST">
                @csrf

                {{-- Pilih Kategori --}}
                <div class="bg-white/10 border border-white/20 rounded-2xl mb-6 overflow-hidden">
                    <div class="px-6 pt-6 pb-2">
                        <h5 class="text-white font-semibold text-lg">1. Pilih Kategori Tiket</h5>
                    </div>
                    <div class="px-6 pb-6 space-y-3">

                        @foreach ($event->ticketCategories as $category)
                            @php
                                $isSoldOut = $category->quota !== null && $category->sold_count >= $category->quota;
                            @endphp

                            <label
                                class="flex items-center justify-between p-4 rounded-xl border-2 transition-all
                            {{ $isSoldOut
                                ? 'border-white/10 bg-white/5 cursor-not-allowed opacity-60'
                                : 'border-yellow-400/50 bg-white/5 cursor-pointer hover:border-yellow-400 hover:bg-yellow-400/10' }}">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="category_id" id="cat_{{ $category->id }}"
                                        value="{{ $category->id }}" {{ $isSoldOut ? 'disabled' : '' }}
                                        class="accent-yellow-400 w-4 h-4" required>
                                    <div>
                                        <span class="text-white font-bold text-base block">{{ $category->name }}</span>
                                        <span class="text-yellow-400 font-semibold text-sm">Rp
                                            {{ number_format($category->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div>
                                    @if ($isSoldOut)
                                        <span
                                            class="bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Habis
                                            Terjual</span>
                                    @else
                                        <span
                                            class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Tersedia</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach

                        @error('category_id')
                            <span class="text-red-400 text-sm flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror

                    </div>
                </div>

                {{-- Jumlah Tiket --}}
                <div class="bg-white/10 border border-white/20 rounded-2xl mb-6 overflow-hidden">
                    <div class="px-6 pt-6 pb-2">
                        <h5 class="text-white font-semibold text-lg">2. Jumlah Tiket</h5>
                    </div>
                    <div class="px-6 pb-6">
                        <select name="qty"
                            class="w-full bg-white/10 border border-white/20 text-white rounded-xl px-4 py-3 text-base focus:outline-none focus:border-yellow-400 transition"
                            required>
                            <option value="" class="bg-black">-- Pilih Jumlah Pembelian --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" class="bg-black">{{ $i }} Tiket</option>
                            @endfor
                        </select>
                        <small class="text-gray-400 text-sm mt-2 block">*Maksimal pembelian 5 tiket per transaksi.</small>

                        @error('qty')
                            <span class="text-red-400 text-sm flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-lg py-4 rounded-2xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                    Lanjut Isi Biodata Diri <i class="fas fa-arrow-right ml-2"></i>
                </button>

            </form>
        </div>
    </div>
@endsection
