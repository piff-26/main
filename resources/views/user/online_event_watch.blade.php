@extends('layouts.user')
@section('title', $movie->title . ' - Online Event Portal')

@push('styles')
<style>
    /* ── Fullscreen: make wrapper fill screen as column ── */
    #player-wrapper:fullscreen,
    #player-wrapper:-webkit-full-screen,
    #player-wrapper:-moz-full-screen {
        display: flex !important;
        flex-direction: column !important;
        width: 100vw !important;
        height: 100vh !important;
        border-radius: 0 !important;
        margin: 0 !important;
        overflow: visible !important;
        background: #000 !important;
    }
    /* ── Video takes remaining space, drop the aspect-ratio constraint ── */
    #player-wrapper:fullscreen #video-container,
    #player-wrapper:-webkit-full-screen #video-container,
    #player-wrapper:-moz-full-screen #video-container {
        flex: 1 1 auto !important;
        min-height: 0 !important;
        aspect-ratio: unset !important;
    }
    /* ── Controls stay fixed height at bottom ── */
    #player-wrapper:fullscreen #vod-controls,
    #player-wrapper:-webkit-full-screen #vod-controls,
    #player-wrapper:-moz-full-screen #vod-controls,
    #player-wrapper:fullscreen #live-controls,
    #player-wrapper:-webkit-full-screen #live-controls,
    #player-wrapper:-moz-full-screen #live-controls {
        flex-shrink: 0 !important;
    }
    #progress-bar-container:hover #progress-thumb { opacity: 1; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-black pt-28 pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('user.online_event') }}" class="text-slate-400 hover:text-[#ff5b1d] transition flex items-center gap-2 font-medium">
                <i class="fas fa-arrow-left"></i> Back to Portal
            </a>
            @if($movie->category)
                <span class="px-3 py-1 bg-[#ff5b1d]/20 text-[#ff5b1d] rounded-full text-xs font-bold uppercase tracking-wider border border-[#ff5b1d]/30">{{ $movie->category->name }}</span>
            @endif
        </div>

        {{-- Video Player Container --}}
        <div id="player-wrapper" class="bg-slate-900/60 rounded-3xl overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] border border-slate-700/50 mb-8">
            <div id="video-container" class="aspect-video relative bg-black">
                @if($movie->video_url)
                    @php
                        $rawUrl = $movie->video_url;
                        $ytVideoId = null;
                        $vimeoEmbedUrl = null;

                        if (str_contains($rawUrl, 'youtube.com/watch?v=')) {
                            parse_str(parse_url($rawUrl, PHP_URL_QUERY), $qs);
                            $ytVideoId = $qs['v'] ?? null;
                        } elseif (str_contains($rawUrl, 'youtu.be/')) {
                            $ytVideoId = explode('?', basename(parse_url($rawUrl, PHP_URL_PATH)))[0];
                        } elseif (str_contains($rawUrl, 'youtube.com/live/')) {
                            $ytVideoId = explode('?', trim(str_replace('/live/', '/', parse_url($rawUrl, PHP_URL_PATH)), '/'))[0];
                        } elseif (str_contains($rawUrl, 'vimeo.com/') && !str_contains($rawUrl, 'player.vimeo.com')) {
                            $vimeoId = substr(parse_url($rawUrl, PHP_URL_PATH), 1);
                            $vimeoEmbedUrl = 'https://player.vimeo.com/video/' . $vimeoId . '?title=0&byline=0&portrait=0&dnt=1';
                        }
                        $isYt = $ytVideoId !== null;
                        $isVimeo = $vimeoEmbedUrl !== null;
                        // Fallback: plain embed URL for unknown platforms
                        $embedUrl = $isYt ? 'yt' : ($isVimeo ? $vimeoEmbedUrl : $rawUrl);
                    @endphp

                    @if($isYt)
                        {{-- YouTube: use a div placeholder so YT.Player builds the iframe --}}
                        <div id="video-iframe" class="absolute inset-0 w-full h-full"></div>
                    @elseif($isVimeo)
                        <iframe id="video-iframe" src="{{ $vimeoEmbedUrl }}" class="absolute inset-0 w-full h-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen oncontextmenu="return false;"></iframe>
                    @else
                        <iframe id="video-iframe" src="{{ $rawUrl }}" class="absolute inset-0 w-full h-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen oncontextmenu="return false;"></iframe>
                    @endif

                    @if($movie->is_live)
                        {{-- Full Security Shield: Block ALL clicks on the iframe for LIVE STREAM --}}
                        <div class="absolute inset-0 w-full h-full z-50 bg-black/0 cursor-default" title="Protected Content" oncontextmenu="return false;"></div>
                    @else
                        {{-- Full Shield for VOD: all clicks blocked, custom controls outside iframe --}}
                        <div class="absolute inset-0 w-full h-full z-50 bg-black/0 cursor-default" title="Protected Content" oncontextmenu="return false;"></div>
                    @endif
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-500 bg-slate-900">
                        <i class="fas fa-video-slash text-5xl mb-4 text-slate-600"></i>
                        <p class="font-medium tracking-wide">Video Coming Soon</p>
                    </div>
                @endif
            </div>
            
            {{-- Custom Controls Bar --}}
            @if($movie->video_url)
            @if($movie->is_live)
            {{-- Live: Simple play/pause --}}
            <div class="bg-slate-900 border-t border-slate-700 px-6 py-4 flex items-center justify-center gap-4">
                <button id="custom-play-btn" class="bg-[#ff5b1d] hover:bg-[#e04a10] text-white px-8 py-2.5 rounded-full font-bold transition flex items-center gap-2 shadow-[0_0_15px_rgba(255,91,29,0.3)]">
                    <i class="fas fa-play"></i> PLAY
                </button>
                <button id="custom-pause-btn" class="bg-slate-700 hover:bg-slate-600 text-white px-8 py-2.5 rounded-full font-bold transition flex items-center gap-2">
                    <i class="fas fa-pause"></i> PAUSE
                </button>
                <button id="fullscreen-btn" class="w-10 h-10 bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white rounded-full flex items-center justify-center transition" title="Fullscreen">
                    <i class="fas fa-expand text-sm"></i>
                </button>
            </div>
            @else
            {{-- VOD: Full custom controls --}}
            <div id="vod-controls" class="bg-slate-900 border-t border-slate-700 px-5 py-3 select-none">
                {{-- Progress Bar --}}
                <div class="flex items-center gap-3 mb-3">
                    <span id="time-current" class="text-slate-400 text-xs font-mono w-10 text-right shrink-0">0:00</span>
                    <div class="relative flex-1 h-1.5 bg-slate-700 rounded-full cursor-pointer group" id="progress-bar-container">
                        <div id="progress-buffered" class="absolute inset-y-0 left-0 bg-slate-600 rounded-full" style="width:0%"></div>
                        <div id="progress-fill" class="absolute inset-y-0 left-0 bg-[#ff5b1d] rounded-full transition-[width] duration-300" style="width:0%"></div>
                        <div id="progress-thumb" class="absolute top-1/2 -translate-y-1/2 w-3.5 h-3.5 bg-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity -translate-x-1/2" style="left:0%"></div>
                        <input id="progress-range" type="range" min="0" max="100" value="0" step="0.1"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        >
                    </div>
                    <span id="time-total" class="text-slate-400 text-xs font-mono w-10 shrink-0">0:00</span>
                </div>
                {{-- Buttons Row --}}
                <div class="flex items-center gap-3">
                    <button id="custom-play-btn" class="w-9 h-9 bg-[#ff5b1d] hover:bg-[#e04a10] text-white rounded-full flex items-center justify-center shadow transition shrink-0" title="Play">
                        <i class="fas fa-play text-sm ml-0.5"></i>
                    </button>
                    <button id="custom-pause-btn" class="w-9 h-9 bg-slate-700 hover:bg-slate-600 text-white rounded-full flex items-center justify-center transition shrink-0" title="Pause">
                        <i class="fas fa-pause text-sm"></i>
                    </button>
                    {{-- Volume --}}
                    <div class="flex items-center gap-2 ml-2">
                        <button id="mute-btn" class="text-slate-400 hover:text-white transition" title="Mute">
                            <i class="fas fa-volume-up text-sm"></i>
                        </button>
                        <input id="volume-range" type="range" min="0" max="100" value="100"
                            class="w-20 accent-[#ff5b1d] cursor-pointer"
                        >
                    </div>
                    <div class="ml-auto flex items-center gap-3">
                        <button id="fullscreen-btn" class="text-slate-400 hover:text-white transition" title="Fullscreen">
                            <i class="fas fa-expand text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endif
            
            <div class="p-6 md:p-8 md:pt-10">
                <div class="flex items-center gap-3 mb-2">
                    @if($movie->is_live)
                        <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded animate-pulse tracking-widest uppercase">Live</span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-6">{{ $movie->title }}</h1>
                
                @if($movie->description)
                    <div class="text-slate-300 text-base md:text-lg leading-relaxed whitespace-pre-line">
                        {{ $movie->description }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const isYouTube = {{ isset($isYt) && $isYt ? 'true' : 'false' }};
    const isVimeo = {{ isset($isVimeo) && $isVimeo ? 'true' : 'false' }};
    const ytVideoId = '{{ isset($ytVideoId) ? $ytVideoId : '' }}';
    const isLive = {{ $movie->is_live ? 'true' : 'false' }};

    let ytPlayer = null;
    let vimeoPlayer = null;
    let progressInterval = null;

    // ─── Format seconds → m:ss ──────────────────────────────────────────────
    function fmtTime(s) {
        s = Math.floor(s || 0);
        const m = Math.floor(s / 60);
        return m + ':' + String(s % 60).padStart(2, '0');
    }

    // ─── Update scrubber UI ──────────────────────────────────────────────────
    function updateProgress(current, duration) {
        if (!duration) return;
        const pct = (current / duration) * 100;
        const fill = document.getElementById('progress-fill');
        const thumb = document.getElementById('progress-thumb');
        const range = document.getElementById('progress-range');
        if (fill) fill.style.width = pct + '%';
        if (thumb) thumb.style.left = pct + '%';
        if (range) range.value = (current / duration) * 100;
        const cur = document.getElementById('time-current');
        const tot = document.getElementById('time-total');
        if (cur) cur.textContent = fmtTime(current);
        if (tot) tot.textContent = fmtTime(duration);
    }

    // ─── YouTube Setup ───────────────────────────────────────────────────────
    if (isYouTube && ytVideoId) {
        const ytScript = document.createElement('script');
        ytScript.src = 'https://www.youtube.com/iframe_api';
        document.head.appendChild(ytScript);
    }

    function onYouTubeIframeAPIReady() {
        ytPlayer = new YT.Player('video-iframe', {
            videoId: ytVideoId,
            playerVars: {
                modestbranding: 1,
                rel: 0,
                controls: 0,
                disablekb: 1,
                iv_load_policy: 3,
                origin: window.location.origin,
                enablejsapi: 1,
            },
            events: {
                onReady: function() {
                    console.log('YT Player ready ✓');
                    if (!isLive) {
                        progressInterval = setInterval(function() {
                            if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function') {
                                updateProgress(ytPlayer.getCurrentTime(), ytPlayer.getDuration());
                            }
                        }, 500);
                    }
                },
                onError: function(e) { console.warn('YT error:', e.data); }
            }
        });
    }

    // ─── Vimeo Setup ────────────────────────────────────────────────────────
    if (isVimeo) {
        const vScript = document.createElement('script');
        vScript.src = 'https://player.vimeo.com/api/player.js';
        vScript.onload = function() {
            vimeoPlayer = new Vimeo.Player(document.getElementById('video-iframe'));
            if (!isLive) {
                vimeoPlayer.on('timeupdate', function(data) {
                    updateProgress(data.seconds, data.duration);
                });
            }
        };
        document.head.appendChild(vScript);
    }

    // ─── Scrubber seek ───────────────────────────────────────────────────────
    document.getElementById('progress-range')?.addEventListener('input', function() {
        const pct = parseFloat(this.value) / 100;
        if (isYouTube && ytPlayer && typeof ytPlayer.getDuration === 'function') {
            ytPlayer.seekTo(pct * ytPlayer.getDuration(), true);
        } else if (isVimeo && vimeoPlayer) {
            vimeoPlayer.getDuration().then(function(d) { vimeoPlayer.setCurrentTime(pct * d); });
        }
    });

    // ─── Volume ──────────────────────────────────────────────────────────────
    document.getElementById('volume-range')?.addEventListener('input', function() {
        const vol = parseInt(this.value);
        if (isYouTube && ytPlayer) ytPlayer.setVolume(vol);
        else if (isVimeo && vimeoPlayer) vimeoPlayer.setVolume(vol / 100);
        const muteBtn = document.getElementById('mute-btn');
        if (muteBtn) muteBtn.innerHTML = vol === 0
            ? '<i class="fas fa-volume-mute text-sm"></i>'
            : '<i class="fas fa-volume-up text-sm"></i>';
    });

    document.getElementById('mute-btn')?.addEventListener('click', function() {
        const range = document.getElementById('volume-range');
        const isMuted = (isYouTube && ytPlayer && ytPlayer.isMuted()) || (range && parseInt(range.value) === 0);
        if (isMuted) {
            if (range) range.value = 100;
            if (isYouTube && ytPlayer) { ytPlayer.unMute(); ytPlayer.setVolume(100); }
            else if (isVimeo && vimeoPlayer) vimeoPlayer.setVolume(1);
            this.innerHTML = '<i class="fas fa-volume-up text-sm"></i>';
        } else {
            if (range) range.value = 0;
            if (isYouTube && ytPlayer) ytPlayer.mute();
            else if (isVimeo && vimeoPlayer) vimeoPlayer.setVolume(0);
            this.innerHTML = '<i class="fas fa-volume-mute text-sm"></i>';
        }
    });

    // ─── Fullscreen ──────────────────────────────────────────────────────────
    document.getElementById('fullscreen-btn')?.addEventListener('click', function() {
        const wrapper = document.getElementById('player-wrapper');
        if (!wrapper) return;
        if (!document.fullscreenElement) {
            wrapper.requestFullscreen().catch(function(e) {
                console.warn('Fullscreen error:', e);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', function() {
        const btn = document.getElementById('fullscreen-btn');
        const wrapper = document.getElementById('player-wrapper');
        const videoContainer = document.getElementById('video-container');
        if (!btn) return;
        if (document.fullscreenElement) {
            btn.innerHTML = '<i class="fas fa-compress text-sm"></i>';
            btn.title = 'Exit Fullscreen';
            if (wrapper) {
                wrapper.style.cssText = 'display:flex;flex-direction:column;height:100%;border-radius:0;margin:0;';
            }
            if (videoContainer) videoContainer.style.flex = '1';
        } else {
            btn.innerHTML = '<i class="fas fa-expand text-sm"></i>';
            btn.title = 'Fullscreen';
            if (wrapper) wrapper.style.cssText = '';
            if (videoContainer) videoContainer.style.flex = '';
        }
    });

    // ─── Play / Pause ────────────────────────────────────────────────────────
    document.getElementById('custom-play-btn')?.addEventListener('click', function() {
        if (isYouTube && ytPlayer && typeof ytPlayer.playVideo === 'function') ytPlayer.playVideo();
        else if (isVimeo && vimeoPlayer) vimeoPlayer.play();
    });

    document.getElementById('custom-pause-btn')?.addEventListener('click', function() {
        if (isYouTube && ytPlayer && typeof ytPlayer.pauseVideo === 'function') ytPlayer.pauseVideo();
        else if (isVimeo && vimeoPlayer) vimeoPlayer.pause();
    });
</script>
@endpush

@include('partials.content_security')

@endsection
