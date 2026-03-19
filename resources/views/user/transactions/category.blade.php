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

            <form action="{{ route('checkout.storeStep1', $event->slug) }}" method="POST" id="ticketForm">
                @csrf

                <div class="bg-white/10 border border-white/20 rounded-2xl mb-6 overflow-hidden">
                    <div class="px-6 pt-6 pb-2">
                        <h5 class="text-white font-semibold text-lg">Pilih Tiket</h5>
                        <p class="text-gray-400 text-sm mt-1">Atur jumlah tiket per kategori. Maksimal 5 tiket per kategori.</p>
                    </div>
                    <div class="px-6 pb-6 space-y-3 mt-2">

                        @foreach ($event->ticketCategories as $category)
                            @php
                                $isSoldOut = $category->quota !== null && $category->sold_count >= $category->quota;
                                $remaining = $category->quota !== null ? $category->quota - $category->sold_count : null;
                            @endphp

                            <div class="flex items-center justify-between p-4 rounded-xl border-2 transition-all
                                {{ $isSoldOut ? 'border-white/10 bg-white/5 opacity-60' : 'border-white/20 bg-white/5 hover:border-yellow-400/50' }}">
                                <div>
                                    <span class="text-white font-bold text-base block">{{ $category->name }}</span>
                                    <span class="text-yellow-400 font-semibold text-sm">Rp {{ number_format($category->price, 0, ',', '.') }}</span>
                                    @if ($remaining !== null && !$isSoldOut)
                                        <span class="text-gray-400 text-xs block mt-0.5">Sisa {{ $remaining }} tiket</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($isSoldOut)
                                        <span class="bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Habis</span>
                                    @else
                                        <button type="button" onclick="changeQty('{{ $category->id }}', -1)"
                                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold transition flex items-center justify-center">−</button>

                                        <span id="display_{{ $category->id }}" class="text-white font-bold w-4 text-center">0</span>

                                        <input type="hidden" name="items[{{ $category->id }}]" id="qty_{{ $category->id }}" value="0">

                                        <button type="button" onclick="changeQty('{{ $category->id }}', 1)"
                                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold transition flex items-center justify-center">+</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @error('items')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

                {{-- Summary --}}
                <div id="summary" class="bg-white/10 border border-white/20 rounded-2xl mb-6 px-6 py-4 hidden">
                    <h5 class="text-white font-semibold mb-3">Ringkasan</h5>
                    <div id="summaryItems" class="space-y-1 text-sm text-gray-300"></div>
                    <div class="border-t border-white/10 mt-3 pt-3 flex justify-between">
                        <span class="text-white font-bold">Total</span>
                        <span class="text-yellow-400 font-bold" id="summaryTotal">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-yellow-400 hover:bg-yellow-300 text-black font-bold text-lg py-4 rounded-2xl transition-all hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    Lanjut Isi Biodata Diri <i class="fas fa-arrow-right ml-2"></i>
                </button>

            </form>
        </div>
    </div>

    <script>
        const prices = {
            @foreach ($event->ticketCategories as $category)
                '{{ $category->id }}': {{ $category->price }},
            @endforeach
        };

        const names = {
            @foreach ($event->ticketCategories as $category)
                '{{ $category->id }}': '{{ $category->name }}',
            @endforeach
        };

        const maxQty = 5;

        function changeQty(id, delta) {
            const input = document.getElementById('qty_' + id);
            const display = document.getElementById('display_' + id);
            let val = parseInt(input.value) + delta;
            val = Math.max(0, Math.min(maxQty, val));
            input.value = val;
            display.textContent = val;
            updateSummary();
        }

        function updateSummary() {
            const summaryDiv = document.getElementById('summary');
            const summaryItems = document.getElementById('summaryItems');
            const summaryTotal = document.getElementById('summaryTotal');
            const submitBtn = document.getElementById('submitBtn');

            let total = 0;
            let hasItem = false;
            let html = '';

            for (const id in prices) {
                const input = document.getElementById('qty_' + id);
                if (!input) continue;
                const qty = parseInt(input.value);
                if (qty > 0) {
                    hasItem = true;
                    const subtotal = qty * prices[id];
                    total += subtotal;
                    html += `<div class="flex justify-between"><span>${names[id]} x${qty}</span><span>Rp ${subtotal.toLocaleString('id-ID')}</span></div>`;
                }
            }

            summaryDiv.classList.toggle('hidden', !hasItem);
            summaryItems.innerHTML = html;
            summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            submitBtn.disabled = !hasItem;
        }
    </script>
@endsection
