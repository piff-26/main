@extends('layouts.user')
@section('title', 'Pilih Tiket - ' . $event->name)

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-10">
            @if ($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}"
                    class="w-full aspect-[21/9] object-cover rounded-2xl mb-6">
            @endif
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white">{{ $event->name }}</h2>
                {{-- <p class="text-gray-400 mt-2">{{ $event->description ?? 'Pilih kategori tiket dan amankan tempatmu sekarang.' }}</p> --}}
            </div>
        </div>

        <div class="max-w-6xl mx-auto">

            @if ($event->ticketCategories->isEmpty())
                <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-500 mb-4 block"></i>
                    <p class="text-white font-semibold">Belum ada tiket tersedia</p>
                    <p class="text-gray-400 text-sm mt-1">Saat ini belum ada kategori tiket yang tersedia untuk event ini.
                    </p>
                </div>
            @else
                <div
                    class="flex flex-col-reverse {{ $event->seat_map_image || $event->description || $event->tnc ? 'lg:flex-row' : '' }} gap-8">

                    {{-- Tab Panel --}}
                    @if ($event->seat_map_image || $event->description || $event->tnc)
                        <div class="lg:w-1/2">
                            {{-- Tab Buttons --}}
                            <div class="flex gap-1 bg-white/5 p-1 rounded-xl mb-4">
                                @if ($event->seat_map_image)
                                    <button onclick="switchTab('tab-seatmap')" id="btn-tab-seatmap"
                                        class="tab-btn flex-1 py-2 text-sm font-semibold rounded-lg transition text-white bg-white/20">
                                        Seat Map
                                    </button>
                                @endif
                                @if ($event->description)
                                    <button onclick="switchTab('tab-desc')" id="btn-tab-desc"
                                        class="tab-btn flex-1 py-2 text-sm font-semibold rounded-lg transition {{ !$event->seat_map_image ? 'text-white bg-white/20' : 'text-gray-400 hover:text-white' }}">
                                        Description
                                    </button>
                                @endif
                                @if ($event->tnc)
                                    <button onclick="switchTab('tab-tnc')" id="btn-tab-tnc"
                                        class="tab-btn flex-1 py-2 text-sm font-semibold rounded-lg transition {{ !$event->seat_map_image && !$event->description ? 'text-white bg-white/20' : 'text-gray-400 hover:text-white' }}">
                                        Terms and Conditions
                                    </button>
                                @endif
                            </div>

                            {{-- Tab Contents --}}
                            @if ($event->seat_map_image)
                                <div id="tab-seatmap" class="tab-content">
                                    <img src="{{ asset('storage/' . $event->seat_map_image) }}"
                                        alt="Seat Map {{ $event->name }}"
                                        class="w-full rounded-2xl border border-white/10">
                                </div>
                            @endif

                            @if ($event->description)
                                <div id="tab-desc" class="tab-content hidden">
                                    <div
                                        class="bg-white/5 border border-white/10 rounded-2xl p-5 text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                                        {{ $event->description }}
                                    </div>
                                </div>
                            @endif

                            @if ($event->tnc)
                                <div id="tab-tnc" class="tab-content hidden">
                                    <div
                                        class="bg-white/5 border border-white/10 rounded-2xl p-5 text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                                        {{ $event->tnc }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Form Tiket --}}
                    <div
                        class="{{ $event->seat_map_image || $event->description || $event->tnc ? 'lg:w-1/2' : 'w-full' }}">
                        <form action="{{ route('checkout.storeStep1', $event->slug) }}" method="POST">
                            @csrf

                            {{-- Info Event --}}
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 mb-6 space-y-3">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-marker-alt text-yellow-400 w-4 mt-0.5"></i>
                                    <span class="text-gray-400 text-sm w-20 shrink-0">Location</span>
                                    <span class="text-white text-sm font-semibold flex-1">{{ $event->location }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-calendar text-yellow-400 w-4 mt-0.5"></i>
                                    <span class="text-gray-400 text-sm w-20 shrink-0">Date</span>
                                    <span
                                        class="text-white text-sm font-semibold flex-1">{{ $event->event_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-clock text-yellow-400 w-4 mt-0.5"></i>
                                    <span class="text-gray-400 text-sm w-20 shrink-0">Time</span>
                                    <span
                                        class="text-white text-sm font-semibold flex-1">{{ $event->start_time->format('H:i') }}{{ $event->end_time ? ' – ' . $event->end_time->format('H:i') : '' }}
                                        WIB</span>
                                </div>
                            </div>

                            @if (session('error'))
                                <div
                                    class="bg-red-500/20 border border-red-400/50 text-red-300 rounded-xl px-4 py-3 mb-6 text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="space-y-4 mb-8">
                                @foreach ($event->ticketCategories as $category)
                                    <div
                                        class="bg-white/10 border border-white/20 rounded-2xl p-5 flex flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <h3 class="text-white font-bold text-lg">{{ $category->name }}</h3>
                                            @if ($category->description)
                                                <p class="text-gray-400 text-sm mt-1">{{ $category->description }}</p>
                                            @endif
                                            <p class="text-yellow-400 font-bold mt-2">Rp
                                                {{ number_format($category->price, 0, ',', '.') }}</p>
                                            @if ($category->quota !== null)
                                                @php $remaining = max(0, $category->quota - $category->sold_count); @endphp
                                                @if ($remaining === 0)
                                                    <p class="text-red-400 text-xs mt-1 font-semibold">Sold Out</p>
                                                @else
                                                    <p class="text-green-400 text-xs mt-1">Available</p>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="changeQty({{ $category->id }}, -1)"
                                                class="w-9 h-9 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-bold text-lg transition">−</button>
                                            <span id="qty-display-{{ $category->id }}"
                                                class="text-white font-bold w-6 text-center">0</span>
                                            <input type="hidden" name="items[{{ $category->id }}]"
                                                id="qty-{{ $category->id }}" value="0">
                                            <button type="button" onclick="changeQty({{ $category->id }}, 1)"
                                                {{ $category->quota !== null && $category->sold_count >= $category->quota ? 'disabled' : '' }}
                                                class="w-9 h-9 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-bold text-lg transition disabled:opacity-40 disabled:cursor-not-allowed">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center">
                                @if (session('user_id'))
                                    <button type="submit" id="btn-submit"
                                        class="px-10 py-4 bg-yellow-400 hover:bg-yellow-300 text-black font-bold rounded-xl transition-all hover:-translate-y-0.5">
                                        <span id="btn-text">Buy Ticket <i class="fas fa-arrow-right ml-2"></i></span>
                                        <span id="btn-spinner" class="hidden">
                                            <svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z">
                                                </path>
                                            </svg>Processing...
                                        </span>
                                    </button>
                                @else
                                    <button type="button" disabled
                                        class="px-10 py-4 bg-gray-600 text-gray-400 font-bold rounded-xl cursor-not-allowed opacity-60">
                                        Buy Ticket <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                    <p class="text-red-400 text-sm mt-3">
                                        <a href="{{ route('user.login') }}"
                                            class="underline hover:text-red-300 transition">Login</a> to buy tickets.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                </div>
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
            val = Math.max(0, Math.min(10, val));
            input.value = val;
            display.textContent = val;
        }

        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit');
            document.getElementById('btn-text').classList.add('hidden');
            document.getElementById('btn-spinner').classList.remove('hidden');
            btn.disabled = true;
        });

        function switchTab(activeId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-white', 'bg-white/20');
                btn.classList.add('text-gray-400');
            });
            document.getElementById(activeId).classList.remove('hidden');
            const btnId = 'btn-' + activeId;
            const activeBtn = document.getElementById(btnId);
            if (activeBtn) {
                activeBtn.classList.add('text-white', 'bg-white/20');
                activeBtn.classList.remove('text-gray-400');
            }
        }
    </script>
@endsection
