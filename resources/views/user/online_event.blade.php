@extends('layouts.user')
@section('title', 'Online Event Portal')

@section('content')
<div class="min-h-screen bg-black pt-28 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- <div class="text-center mb-12"> -->
            <!-- <h1 class="text-4xl md:text-5xl font-extrabold text-[#fff] mb-4 tracking-tight">PIFF 2026 ONLINE EVENT PORTAL</h1> -->
            <!-- <p class="text-slate-400 max-w-2xl mx-auto text-lg">Stream the best short films and independent masterpieces from anywhere in the world.</p> -->
        <!-- </div> -->

        @if($hasActivePass)
            {{-- User has active pass --}}
            <div class="space-y-12">
                @php
                    $liveMovie = $myMovies->where('is_live', true)->first();
                @endphp
                
                @if($liveMovie)
                {{-- Featured Player --}}
                <div class="bg-slate-900/50 rounded-3xl border border-slate-700 overflow-hidden shadow-2xl mb-12">
                    <a href="{{ route('user.online_event.watch', $liveMovie->slug) }}" class="aspect-video bg-black relative flex items-center justify-center group cursor-pointer block">
                        @if($liveMovie->thumbnail)
                            <img src="{{ asset('storage/' . $liveMovie->thumbnail) }}" alt="{{ $liveMovie->title }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity duration-500">
                        @else
                            <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" alt="Featured Movie" class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity duration-500">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        
                        <div class="absolute w-20 h-20 bg-[#ff5b1d]/90 rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(255,91,29,0.5)] group-hover:scale-110 transition-transform duration-300 z-10">
                            <i class="fas fa-play text-white text-3xl ml-2"></i>
                        </div>
                        
                        <div class="absolute bottom-0 left-0 p-6 md:p-8 w-full">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
                                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span> Live Screening
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $liveMovie->title }}</h2>
                            <p class="text-slate-300 max-w-3xl line-clamp-2">{{ $liveMovie->description ?? 'Join our live streaming event now.' }}</p>
                        </div>
                    </a>
                </div>
                @endif

                @if($myMovies->count() === 0 && $allMovies->count() === 0)
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <div class="relative mb-8">
                        <div class="w-28 h-28 bg-slate-800/80 rounded-full flex items-center justify-center border border-slate-700 shadow-inner mx-auto">
                            <i class="fas fa-satellite-dish text-5xl text-[#ff5b1d]"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Stay Tuned!</h2>
                    <p class="text-slate-400 text-base md:text-lg max-w-xl leading-relaxed">
                        Stay tuned for upcoming live streams and movies on
                        <span class="text-[#ff5b1d] font-semibold">30 May 2026 at 12:00 PM (UTC+7)</span>
                    </p>
                    <div class="mt-8 inline-flex items-center gap-2 px-4 py-2 bg-slate-800/60 border border-slate-700 rounded-full text-slate-400 text-sm">
                        Your Online Pass is active and ready
                    </div>
                </div>
                @else

                {{-- Search & Filter Bar --}}
                <div class="flex flex-col sm:flex-row gap-3 mb-8">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input id="movie-search" type="text" placeholder="Search films..." autocomplete="off"
                            class="w-full bg-slate-800/70 border border-slate-700 text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#ff5b1d]/60 focus:ring-1 focus:ring-[#ff5b1d]/30 transition">
                    </div>
                    <select id="category-filter"
                        class="bg-slate-800/70 border border-slate-700 text-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#ff5b1d]/60 transition cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Playlist Grid - My Event --}}
                @if($myMovies->count() > 0)
                <div class="mb-10">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-play-circle text-[#ff5b1d]"></i> My Event
                    </h3>
                    <div id="grid-my" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($myMovies as $movie)
                            <a href="{{ route('user.online_event.watch', $movie->slug) }}"
                                class="movie-card bg-slate-900/40 rounded-2xl border border-slate-700/50 overflow-hidden hover:border-[#ff5b1d]/50 transition group relative shadow-lg block"
                                data-title="{{ strtolower($movie->title) }}"
                                data-desc="{{ strtolower($movie->description ?? '') }}"
                                data-cat="{{ $movie->category_id ?? '' }}">
                                <div class="aspect-video relative overflow-hidden bg-slate-800">
                                    <div class="absolute inset-0 flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 backdrop-blur-sm">
                                        <div class="w-12 h-12 bg-[#ff5b1d] rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(255,91,29,0.5)]">
                                            <i class="fas fa-play text-white ml-1"></i>
                                        </div>
                                    </div>
                                    @if($movie->thumbnail)
                                        <img src="{{ asset('storage/' . $movie->thumbnail) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-500"><i class="fas fa-film text-3xl"></i></div>
                                    @endif
                                    @if($movie->is_live)
                                        <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded animate-pulse">LIVE</span>
                                    @endif
                                    @if($movie->category)
                                        <span class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-sm text-[#ff5b1d] text-[10px] font-bold px-2 py-0.5 rounded-full border border-[#ff5b1d]/30">{{ $movie->category->name }}</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="text-white font-semibold mb-1 line-clamp-1">{{ $movie->title }}</h4>
                                    @if($movie->description)
                                        <p class="text-slate-400 text-xs line-clamp-2 leading-snug">{{ $movie->description }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <p id="empty-my" class="hidden text-slate-500 text-sm text-center py-6">No films match your search.</p>
                </div>
                @endif

                {{-- Playlist Grid - All Event --}}
                <div class="pt-8 border-t border-slate-800">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-film text-[#ff5b1d]"></i> All Available Films
                    </h3>
                    <div id="grid-all" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($allMovies as $movie)
                            @php $isAccessible = $myMovies->contains('id', $movie->id); @endphp
                            @if($isAccessible)
                            <a href="{{ route('user.online_event.watch', $movie->slug) }}"
                                class="movie-card bg-slate-900/40 rounded-2xl border border-slate-700/50 overflow-hidden hover:border-slate-500 transition group relative opacity-100 block"
                                data-title="{{ strtolower($movie->title) }}"
                                data-desc="{{ strtolower($movie->description ?? '') }}"
                                data-cat="{{ $movie->category_id ?? '' }}">
                            @else
                            <div class="movie-card bg-slate-900/40 rounded-2xl border border-slate-700/50 overflow-hidden transition relative opacity-50"
                                data-title="{{ strtolower($movie->title) }}"
                                data-desc="{{ strtolower($movie->description ?? '') }}"
                                data-cat="{{ $movie->category_id ?? '' }}">
                            @endif
                                <div class="aspect-video relative overflow-hidden bg-slate-800">
                                    <div class="absolute inset-0 flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 backdrop-blur-sm">
                                        <div class="w-12 h-12 {{ $isAccessible ? 'bg-[#ff5b1d]' : 'bg-slate-600' }} rounded-full flex items-center justify-center shadow-lg">
                                            @if($isAccessible)
                                                <i class="fas fa-play text-white ml-1"></i>
                                            @else
                                                <i class="fas fa-lock text-white"></i>
                                            @endif
                                        </div>
                                    </div>
                                    @if($movie->thumbnail)
                                        <img src="{{ asset('storage/' . $movie->thumbnail) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-500"><i class="fas fa-film text-3xl"></i></div>
                                    @endif
                                    @if(!$isAccessible)
                                        <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm rounded-full p-1.5 border border-slate-600">
                                            <i class="fas fa-lock text-slate-300 text-xs"></i>
                                        </div>
                                    @endif
                                    @if($movie->category)
                                        <span class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-sm text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $isAccessible ? 'text-[#ff5b1d] border-[#ff5b1d]/30' : 'text-slate-400 border-slate-600' }}">{{ $movie->category->name }}</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="text-white font-semibold mb-1 line-clamp-1">{{ $movie->title }}</h4>
                                    @if($movie->description)
                                        <p class="text-slate-400 text-xs line-clamp-2 leading-snug">{{ $movie->description }}</p>
                                    @endif
                                </div>
                            @if($isAccessible)
                            </a>
                            @else
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <p id="empty-all" class="hidden text-slate-500 text-sm text-center py-6">No films match your search.</p>
                </div>
            </div>

                @endif {{-- end movies check --}}
            </div>

        @else
            {{-- User DOES NOT have active pass --}}
            <div class="max-w-4xl mx-auto">

                {{-- Discover What's Streaming Section --}}
                @if($allMovies->count() > 0)
                <div class="mb-16">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-white mb-2">Discover</h3>
                        <p class="text-slate-400">Get an Online Pass today and access these amazing films and live streaming</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($allMovies as $movie)
                            <div class="bg-slate-900/40 rounded-2xl border border-slate-700/50 overflow-hidden hover:border-slate-500 transition group cursor-pointer relative">
                                <div class="aspect-video relative overflow-hidden bg-slate-800">
                                    <div class="absolute inset-0 flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 backdrop-blur-sm">
                                        <div class="w-12 h-12 bg-slate-600 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-lock text-white"></i>
                                        </div>
                                    </div>
                                    @if($movie->thumbnail)
                                        <img src="{{ asset('storage/' . $movie->thumbnail) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-500"><i class="fas fa-film text-3xl"></i></div>
                                    @endif
                                    @if($movie->category)
                                        <span class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-sm text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-full border border-slate-600">{{ $movie->category->name }}</span>
                                    @endif
                                    <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm rounded-full p-1.5 border border-slate-600">
                                        <i class="fas fa-lock text-slate-400 text-xs"></i>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="text-white font-semibold mb-1 line-clamp-1">{{ $movie->title }}</h4>
                                    @if($movie->description)
                                        <p class="text-slate-400 text-xs line-clamp-2 leading-snug">{{ $movie->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif


                <div class="bg-slate-900/60 backdrop-blur-xl rounded-3xl border border-slate-700/60 p-6 md:p-8 relative overflow-hidden shadow-2xl mb-16 flex flex-col md:flex-row items-center gap-8">
                    {{-- Decorative Background Elements --}}
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-32 bg-[#ff5b1d]/20 rounded-full blur-[80px]"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px]"></div>
                    
                    <div class="relative z-10 shrink-0">
                        <div class="w-20 h-20 bg-gradient-to-br from-slate-800 to-slate-900 rounded-full flex items-center justify-center border border-slate-700 shadow-inner">
                            <i class="fas fa-lock text-3xl text-slate-400"></i>
                        </div>
                    </div>
                    
                    <div class="relative z-10 flex-1 text-center md:text-left">
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Access Restricted</h2>
                        <p class="text-slate-400 text-base md:text-lg">You need an active Online Pass to access the streaming portal and watch all curated short films.</p>
                    </div>
                    
                    <div class="relative z-10 shrink-0 flex flex-col sm:flex-row gap-3">
                        @if(session('user_id'))
                            <a href="{{ route('user.ticket') }}" class="px-6 py-3 bg-[#ff5b1d] hover:bg-[#e04a10] text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(255,91,29,0.3)] transform hover:-translate-y-1 tracking-widest flex items-center gap-2 text-sm whitespace-nowrap">
                                <i class="fas fa-ticket-alt"></i> GET ONLINE PASS
                            </a>
                        @else
                            <a href="{{ route('user.login') }}" class="px-6 py-3 bg-slate-100 hover:bg-white text-slate-900 font-bold rounded-xl transition-all tracking-widest flex items-center gap-2 text-sm whitespace-nowrap">
                                <i class="fas fa-sign-in-alt"></i> LOGIN TO CONTINUE
                            </a>
                            <a href="{{ route('user.ticket') }}" class="px-6 py-3 bg-transparent border border-[#ff5b1d] text-[#ff5b1d] hover:bg-[#ff5b1d]/10 font-bold rounded-xl transition-all tracking-widest text-sm whitespace-nowrap text-center">
                                VIEW TICKET
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Feature list --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-slate-800">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-[#ff5b1d]">
                            <i class="fas fa-video text-xl"></i>
                        </div>
                        <h4 class="text-white font-semibold mb-2">High Quality Streaming</h4>
                        <p class="text-slate-500 text-sm">Watch in full HD across all your devices anywhere, anytime.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-[#ff5b1d]">
                            <i class="fas fa-film text-xl"></i>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Exclusive Films</h4>
                        <p class="text-slate-500 text-sm">Access to internationally acclaimed short films selected by experts.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-[#ff5b1d]">
                            <i class="fas fa-comments text-xl"></i>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Live Talkshow</h4>
                        <p class="text-slate-500 text-sm">Join talkshow sessions with directors and film industry professionals.</p>
                    </div>
                </div>


            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('movie-search');
    const categoryFilter = document.getElementById('category-filter');

    function filterMovies() {
        const q = (searchInput?.value || '').toLowerCase().trim();
        const cat = (categoryFilter?.value || '');

        ['grid-my', 'grid-all'].forEach(function(gridId) {
            const grid = document.getElementById(gridId);
            if (!grid) return;
            const cards = grid.querySelectorAll('.movie-card');
            let visible = 0;
            cards.forEach(function(card) {
                const title = card.dataset.title || '';
                const desc = card.dataset.desc || '';
                const cardCat = card.dataset.cat || '';
                const matchQ = !q || title.includes(q) || desc.includes(q);
                const matchCat = !cat || cardCat === cat;
                if (matchQ && matchCat) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });
            const emptyId = gridId === 'grid-my' ? 'empty-my' : 'empty-all';
            const emptyEl = document.getElementById(emptyId);
            if (emptyEl) emptyEl.classList.toggle('hidden', visible > 0);
        });
    }

    searchInput?.addEventListener('input', filterMovies);
    categoryFilter?.addEventListener('change', filterMovies);
</script>
@endpush

@include('partials.content_security')

@endsection
