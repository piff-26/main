@extends('layouts.user')
@section('no_loader', '1')
@section('title', 'Online Pass - ' . $ticket->name)

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-4xl mx-auto mb-10 text-center">
            @if ($ticket->image)
                <img src="{{ asset('storage/' . $ticket->image) }}" alt="{{ $ticket->name }}" class="w-full aspect-[21/9] object-cover rounded-2xl mb-6">
            @endif
            <h2 class="text-3xl font-bold text-white">{{ $ticket->name }}</h2>
            <p class="text-gray-400 mt-2">Get the access to watch all the film and live streaming online at PIFF 2026.</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col-reverse lg:flex-row gap-8">
                
                {{-- Tab Panel --}}
                <div class="lg:w-1/2">
                        {{-- Tab Buttons --}}
                        <div class="flex gap-1 bg-white/5 p-1 rounded-xl mb-4">
                            <!-- <button onclick="switchTab('tab-included')" id="btn-tab-included"
                                class="tab-btn flex-1 py-2 text-sm font-semibold rounded-lg transition text-white bg-white/20">
                                What's Included
                            </button> -->
                            @if ($ticket->description)
                                <button onclick="switchTab('tab-desc')" id="btn-tab-desc"
                                    class="tab-btn flex-1 py-2 text-sm font-semibold rounded-lg transition text-gray-400 hover:text-white">
                                    Description
                                </button>
                            @endif
                            @if ($ticket->tnc)
                                <button onclick="switchTab('tab-tnc')" id="btn-tab-tnc"
                                    class="tab-btn flex-1 py-2 text-sm font-semibold rounded-lg transition text-gray-400 hover:text-white">
                                    Terms and Conditions
                                </button>
                            @endif
                        </div>

                        {{-- Tab Contents --}}
                        <!-- <div id="tab-included" class="tab-content">
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                                @if($ticket->movies->count() > 0)
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($ticket->movies as $movie)
                                            <div class="flex items-start gap-3 bg-black/20 p-3 rounded-xl border border-white/5">
                                                <div class="w-32 shrink-0 aspect-video bg-slate-800 rounded-lg overflow-hidden border border-white/10">
                                                    @if($movie->thumbnail)
                                                        <img src="{{ asset('storage/' . $movie->thumbnail) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-slate-500">
                                                            <i class="fas fa-film"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col justify-center flex-1">
                                                    <h4 class="text-white font-semibold text-sm line-clamp-1" title="{{ $movie->title }}">{{ $movie->title }}</h4>
                                                    @if($movie->category)
                                                        <span class="text-[#ff5b1d] text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $movie->category->name }}</span>
                                                    @endif
                                                    @if($movie->description)
                                                        <p class="text-gray-400 text-xs mt-1.5 line-clamp-2 leading-snug" title="{{ $movie->description }}">{{ $movie->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-400 text-sm text-center py-4">No specific movies listed. Grants access to the general portal.</p>
                                @endif
                            </div>
                        </div> -->

                        @if ($ticket->description)
                            <div id="tab-desc" class="tab-content hidden">
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                                    {{ $ticket->description }}
                                </div>
                            </div>
                        @endif

                        @if ($ticket->tnc)
                            <div id="tab-tnc" class="tab-content hidden">
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                                    {{ $ticket->tnc }}
                                </div>
                            </div>
                        @endif
                    </div>

                {{-- Form Tiket --}}
                <div class="lg:w-1/2">
                    <form action="{{ route('online-pass.checkout.storeStep1', $ticket->slug) }}" method="POST">
                        @csrf

                        {{-- Info Access --}}
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 mb-6 space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-desktop text-[#ff5b1d] w-4 mt-0.5"></i>
                                <span class="text-gray-400 text-sm w-28 shrink-0">Platform</span>
                                <span class="text-white text-sm font-semibold flex-1">PIFF 2026 Online Portal</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-calendar text-[#ff5b1d] w-4 mt-0.5"></i>
                                <span class="text-gray-400 text-sm w-28 shrink-0">Access Opens</span>
                                <span class="text-white text-sm font-semibold flex-1">{{ \Carbon\Carbon::parse($ticket->access_start_date)->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-clock text-[#ff5b1d] w-4 mt-0.5"></i>
                                <span class="text-gray-400 text-sm w-28 shrink-0">Access Closes</span>
                                <span class="text-white text-sm font-semibold flex-1">{{ \Carbon\Carbon::parse($ticket->access_end_date)->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>

                        @if (session('error'))
                            <div class="bg-red-500/20 border border-red-400/50 text-red-300 rounded-xl px-4 py-3 mb-6 text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="space-y-4 mb-8">
                            <div class="bg-white/10 border border-white/20 rounded-2xl p-5 flex flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-white font-bold text-lg">{{ $ticket->name }}</h3>
                                    <p class="text-[#ff5b1d] font-bold mt-2">IDR {{ number_format($ticket->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="hidden" name="qty" id="qty-{{ $ticket->id }}" value="1">
                                    <span class="text-white font-bold px-4 py-2 bg-slate-700 rounded-lg">1 Pass</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            @if (session('user_id'))
                                @if (isset($hasPurchased) && $hasPurchased)
                                    <button type="button" disabled
                                        class="w-full py-4 bg-gray-600 text-gray-400 font-bold rounded-xl cursor-not-allowed opacity-60 tracking-widest mb-4">
                                        PURCHASE PASS <i class="fas fa-check ml-2"></i>
                                    </button>
                                    <a href="{{ route('user.online_event') }}" class="w-full block py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)] transform hover:-translate-y-1 tracking-widest">
                                        <i class="fas fa-play mr-2"></i> MY PASS
                                    </a>
                                @else
                                    <button type="submit" id="btn-submit"
                                        class="w-full py-4 bg-[#ff5b1d] hover:bg-[#e04a10] text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(255,91,29,0.3)] transform hover:-translate-y-1 tracking-widest">
                                        <span id="btn-text">PURCHASE PASS <i class="fas fa-arrow-right ml-2"></i></span>
                                        <span id="btn-spinner" class="hidden">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                                        </span>
                                    </button>
                                @endif
                            @else
                                <button type="button" disabled
                                    class="w-full py-4 bg-gray-600 text-gray-400 font-bold rounded-xl cursor-not-allowed opacity-60 tracking-widest">
                                    PURCHASE PASS <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                <p class="text-red-400 text-sm mt-3">
                                    <a href="{{ route('user.login') }}" class="underline hover:text-red-300 transition">Login</a> to purchase online pass.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
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
            document.getElementById(activeId)?.classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + activeId);
            if (activeBtn) {
                activeBtn.classList.add('text-white', 'bg-white/20');
                activeBtn.classList.remove('text-gray-400');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const firstBtn = document.querySelector('.tab-btn');
            if (firstBtn) {
                const tabId = firstBtn.getAttribute('onclick').match(/'([^']+)'/)[1];
                switchTab(tabId);
            }
        });
    </script>
@endsection
