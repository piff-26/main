@extends('layouts.user')
@section('title', 'Tiket - ' . $event->name)

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-10 text-center">
            <h2 class="text-3xl font-bold text-white">{{ $event->name }}</h2>
            <p class="text-gray-400 mt-2">{{ $event->description ?? 'Pilih kategori tiket dan amankan tempatmu sekarang.' }}</p>
        </div>

        <div class="max-w-4xl mx-auto">

            @if ($event->ticketCategories->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-white font-semibold">Belum ada tiket tersedia</p>
                    <p class="text-gray-400 text-sm mt-1">Saat ini belum ada kategori tiket yang tersedia untuk event ini.</p>
                </div>
            @else
                <form action="{{ route('checkout.storeStep1', $event->slug) }}" method="POST">
                    @csrf

                    @if (session('error'))
                        <div class="bg-red-500/20 border border-red-400/50 text-red-300 rounded-xl px-4 py-3 mb-6 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-4 mb-8">
                        @foreach ($event->ticketCategories as $category)
                            <div class="bg-white/10 border border-white/20 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-white font-bold text-lg">{{ $category->name }}</h3>
                                    @if ($category->description)
                                        <p class="text-gray-400 text-sm mt-1">{{ $category->description }}</p>
                                    @endif
                                    <p class="text-yellow-400 font-bold mt-2">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                    @if ($category->quota !== null)
                                        <p class="text-gray-500 text-xs mt-1">Sisa: {{ max(0, $category->quota - $category->sold_count) }} tiket</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="changeQty({{ $category->id }}, -1)"
                                        class="w-9 h-9 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-bold text-lg transition">−</button>
                                    <span id="qty-display-{{ $category->id }}" class="text-white font-bold w-6 text-center">0</span>
                                    <input type="hidden" name="items[{{ $category->id }}]" id="qty-{{ $category->id }}" value="0">
                                    <button type="button" onclick="changeQty({{ $category->id }}, 1)"
                                        class="w-9 h-9 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-bold text-lg transition">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center">
                        <button type="submit"
                            class="px-10 py-4 bg-yellow-400 hover:bg-yellow-300 text-black font-bold rounded-xl transition-all hover:-translate-y-0.5">
                            Lanjut ke Biodata <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
@endsection

@section('script')
    <script>
        function changeQty(id, delta) {
            const input = document.getElementById('qty-' + id);
            const display = document.getElementById('qty-display-' + id);
            let val = parseInt(input.value) + delta;
            val = Math.max(0, Math.min(5, val));
            input.value = val;
            display.textContent = val;
        }
    </script>
@endsection
