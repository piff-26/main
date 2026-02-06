<style>
    /* --- base navbar --- */
    .nav-transparent {
        background: transparent;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        transition: all 0.4s ease;
    }

    .nav-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        transition: all 0.4s ease;
    }

    .nav-hidden {
        transform: translateY(-100%);
        transition: transform 0.4s ease;
    }

    /* --- Desktop nav item --- */
    .nav-desktop-item a {
        position: relative;
        font-weight: 600;
        color: rgb(255, 255, 255);
        text-shadow: 0 0 5px color-mix(in srgb, var(--dark-green), transparent 60%);
        padding: 8px 16px;
        display: inline-block;
        transition: color 0.3s ease;
        z-index: 1;
    }

    /* Fill effect (background) */
    .nav-desktop-item a::before {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 100%;
        width: 100%;
        /* background: var(--medium-green); */
        background: rgba(255, 255, 255, 0.2);
        z-index: -1;
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform 0.3s ease;
    }

    /* Underline Effect */
    .nav-desktop-item a::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -4px;
        height: 2px;
        width: 100%;
        /* background: var(--medium-green); */
        background: rgba(255, 255, 255, 0.2);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }

    /* Hover State */
    .nav-desktop-item a:hover::before {
        transform: scaleY(1);
    }

    .nav-desktop-item a:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    .nav-desktop-item a:hover {
        color: #ffffff;
    }


    .nav-desktop-item a[href*="login"],
    .nav-desktop-item a[href*="logout"] {
        background-color: var(--orange);
        color: white;
        border: 2px solid var(--orange);
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(255, 91, 29, 0.4);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-desktop-item a[href*="login"]::before,
    .nav-desktop-item a[href*="logout"]::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: white;
        transition: width 0.3s ease;
        z-index: -1;
    }

    .nav-desktop-item a[href*="login"]::after,
    .nav-desktop-item a[href*="logout"]::after {
        display: none;
    }

    .nav-desktop-item a[href*="login"]:hover,
    .nav-desktop-item a[href*="logout"]:hover {
        color: var(--orange);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 91, 29, 0.6);
    }

    .nav-desktop-item a[href*="login"]:hover::before,
    .nav-desktop-item a[href*="logout"]:hover::before {
        width: 100%;
    }

    .mobile-hamburger-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1004;
        display: block;
        transition: all 0.4s ease;

        /* Default state (logo visible) */
        background: transparent;
    }

    .mobile-hamburger-wrapper img {
        /* Transisi saat logo muncul kembali (Turun dari atas) */
        transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease;
        transform: translateY(0);
        /* Posisi Normal */
        opacity: 1;
    }

    .mobile-hamburger-wrapper.scrolled {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
    }

    /* State saat Menu Terbuka (Logo Hilang ke Atas) */
    .mobile-hamburger-wrapper.menu-active {
        background: transparent !important;
        backdrop-filter: none !important;
        border: none !important;
    }

    .mobile-hamburger-wrapper.menu-active img {
        /* Saat menu buka, logo naik ke atas (-200%) dan hilang */
        transform: translateY(-200%);
        opacity: 0;
        transition: transform 0.4s ease-in, opacity 0.3s ease;
    }

    .mobile-hamburger-wrapper #menuBtn {
        transform: translateY(0) !important;
    }

    .mobile-hamburger-wrapper.menu-active #menuBtn {
        /* Pastikan tidak ikut naik */
        transform: translateY(0);
    }



    .mobile-hamburger-wrapper.nav-hidden {
        transform: translateY(-100%);
    }

    /* --- Hamburger Button Styles --- */
    .ham {
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: transform 600ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .ham.active {
        transform: rotate(180deg) scale(1.1);
    }

    .line {
        fill: none;
        transition: stroke-dasharray 600ms cubic-bezier(0.68, -0.55, 0.265, 1.55),
            stroke-dashoffset 600ms cubic-bezier(0.68, -0.55, 0.265, 1.55),
            stroke-width 400ms ease,
            stroke 400ms ease;
        stroke: white;
        stroke-width: 7.5;
        stroke-linecap: round;
        transform-origin: center;
    }

    .ham6 .top {
        stroke-dasharray: 40 172;
        transition-delay: 0ms;
    }

    .ham6 .middle {
        stroke-dasharray: 40 111;
        transition-delay: 100ms;
    }

    .ham6 .bottom {
        stroke-dasharray: 40 172;
        transition-delay: 200ms;
    }

    .ham6.active .top {
        stroke-dashoffset: -132px;
        stroke-width: 9;
        transition-delay: 200ms;
    }

    .ham6.active .middle {
        stroke-dashoffset: -71px;
        stroke-width: 9;
        transition-delay: 100ms;
    }

    .ham6.active .bottom {
        stroke-dashoffset: -132px;
        stroke-width: 9;
        transition-delay: 0ms;
    }

    /* --- Mobile Menu Overlay --- */
    .mobile-menu-overlay {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1002;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.4s ease, visibility 0.4s ease;
    }

    .mobile-menu-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* --- Mobile Menu --- */
    .nav-mobile-menu {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        width: 83.333333%;
        max-width: 300px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        z-index: 1002;
        transform: translateX(100%);
        transition: transform 0.4s ease;
        padding-top: 6rem;
        overflow-y: auto;
    }

    .nav-mobile-menu.show {
        transform: translateX(0);
    }

    /* Mobile Menu Items */
    .nav-mobile-menu .mobile-nav {
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
        list-style: none;
        text-align: center;
    }

    .nav-mobile-menu .mobile-nav li a {
        display: block;
        padding: 1rem 2rem;
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 1.25rem;
        position: relative;
        transition: padding-left 0.3s ease;
    }

    .nav-mobile-menu .mobile-nav li a span {
        position: relative;
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .nav-mobile-menu .mobile-nav li a span::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: width 0.3s ease;
        z-index: -1;
    }

    .nav-mobile-menu .mobile-nav li a:hover span::before {
        width: 100%;
    }

    .nav-mobile-menu .mobile-nav li a:hover {
        padding-left: 2.5rem;
    }

    .nav-mobile-menu .mobile-nav li a[href*="login"] span {
        background-color: var(--orange);
        color: white;
        border: 2px solid var(--orange);
        box-shadow: 0 4px 15px rgba(255, 91, 29, 0.4);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-mobile-menu .mobile-nav li a[href*="login"] span::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: white;
        transition: width 0.3s ease;
        z-index: -1;
    }

    .nav-mobile-menu .mobile-nav li a[href*="login"]:hover span {
        color: var(--orange);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 91, 29, 0.6);
    }

    .nav-mobile-menu .mobile-nav li a[href*="login"]:hover span::before {
        width: 100%;
    }

    /* Logo dalam menu mobile */
    .nav-mobile-menu .mobile-logo {
        height: 2.5rem;
        margin-bottom: 1rem;
        padding: 0 2rem;
    }

    /* Matikan scroll saat menu terbuka */
    body.menu-open {
        overflow: hidden;
        /* height: 100vh; */
    }

    /* Media Queries */
    @media screen and (orientation: portrait) {
        .nav-desktop {
            display: none;
        }

        .nav-mobile {
            display: flex;
        }

        .logo-desktop {
            display: none;
        }

        .mobile-hamburger-wrapper {
            display: block;
        }
    }

    @media screen and (orientation: landscape) {
        .nav-desktop {
            display: flex;
        }

        .nav-mobile {
            display: none;
        }

        .logo-desktop {
            display: flex;
        }

        .mobile-hamburger-wrapper {
            display: none;
        }

        .nav-mobile-menu {
            display: none;
        }

        .mobile-menu-overlay {
            display: none;
        }
    }

    @media (min-width: 768px) {
        .nav-mobile-menu {
            max-width: 400px;
        }

        .nav-mobile-menu .mobile-nav li a {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.getElementById("navbar");
        const mobileHamburgerWrapper = document.getElementById("mobileHamburgerWrapper");
        const mobileMenu = document.getElementById("mobileMenu");
        const mobileOverlay = document.getElementById("mobileOverlay");
        const menuBtn = document.getElementById("menuBtn");
        const hamSvg = document.getElementById("hamsvg");
        let lastScrollTop = 0;

        window.addEventListener("scroll", function() {
            if (mobileMenu.classList.contains('show')) return;

            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // if (currentScroll > 50) {
            //     navbar.classList.remove("nav-transparent");
            //     navbar.classList.add("nav-glass");
            // } else {
            //     navbar.classList.remove("nav-glass");
            //     navbar.classList.add("nav-transparent");
            // }

            if (currentScroll > lastScrollTop && currentScroll > 100) {
                navbar.classList.add("nav-hidden");
            } else {
                navbar.classList.remove("nav-hidden");
            }

            // Mobile hamburger wrapper effect
            if (currentScroll > 50) {
                mobileHamburgerWrapper.classList.add("scrolled");
            } else {
                mobileHamburgerWrapper.classList.remove("scrolled");
            }

            if (currentScroll > lastScrollTop && currentScroll > 100) {
                mobileHamburgerWrapper.classList.add("nav-hidden");
            } else {
                mobileHamburgerWrapper.classList.remove("nav-hidden");
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });

        // --- Mobile Menu Toggle (UPDATED) ---
        menuBtn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            // Stop Lenis if it exists
            if (window.lenis) {
                window.lenis.stop();
            }

            hamSvg.classList.toggle("active");
            mobileMenu.classList.toggle("show");
            mobileOverlay.classList.toggle("show");
            document.body.classList.toggle("menu-open");

            mobileHamburgerWrapper.classList.toggle("menu-active");

            setTimeout(() => {
                if (window.lenis && !mobileMenu.classList.contains('show')) {
                    window.lenis.start();
                }
            }, 100);

            return false;
        });

        function closeMenu() {
            hamSvg.classList.remove("active");
            mobileMenu.classList.remove("show");
            mobileOverlay.classList.remove("show");
            document.body.classList.remove("menu-open");

            mobileHamburgerWrapper.classList.remove("menu-active");

            if (window.lenis) {
                window.lenis.start();
            }
        }

        mobileOverlay.addEventListener("click", closeMenu);

        document.querySelectorAll("#mobileMenu a").forEach(link => {
            link.addEventListener("click", closeMenu);
        });
    });
</script>

<!-- Mobile Hamburger Wrapper -->
<div class="mobile-hamburger-wrapper" id="mobileHamburgerWrapper">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem;">
        
        {{-- LOGO PIFF --}}
        {{-- <img src="{{ asset('assets/logo/logo_pce_tulisan.webp') }}" alt="Logo PCE" style="height: 2rem; width: auto;"> --}}
        <div id="menuBtn"
            style="width: 2.5rem; height: 2.5rem; border: 0; background: transparent; color: rgba(0,0,0,0.5);
                   cursor: pointer; user-select: none; transition: transform 0.4s; display: flex; align-items: center; justify-content: center;">
            <span>
                <svg class="ham ham6 toggle-btn" viewBox="20 20 60 60" id="hamsvg"
                    style="width: 2.5rem; height: 2.5rem; pointer-events: none;">
                    <path class="line top"
                        d="m 30,33 h 40 c 13.100415,0 14.380204,31.80258 6.899646,33.421777 -24.612039,5.327373 9.016154,-52.337577 -12.75751,-30.563913 l -28.284272,28.284272" />
                    <path class="line middle"
                        d="m 70,50 c 0,0 -32.213436,0 -40,0 -7.786564,0 -6.428571,-4.640244 -6.428571,-8.571429 0,-5.895471 6.073743,-11.783399 12.286435,-5.570707 6.212692,6.212692 28.284272,28.284272 28.284272,28.284272" />
                    <path class="line bottom"
                        d="m 69.575405,67.073826 h -40 c -13.100415,0 -14.380204,-31.80258 -6.899646,-33.421777 24.612039,-5.327373 -9.016154,52.337577 12.75751,30.563913 l 28.284272,-28.284272" />
                </svg>
            </span>
        </div>
    </div>
</div>

<!-- Desktop Navbar -->
<div id="navbar"
    class="nav-glass fixed top-0 left-0 right-0 z-50 flex w-full items-center justify-between py-4 px-4 transition duration-500">

    <!-- Logo (Desktop Only) -->
    <div class="logo-desktop items-center space-x-3 hidden">
        {{-- <img src="{{ asset('assets/logo/logo_pce_tulisan.webp') }}" alt="Logo PIFF"
            class="h-[32px] w-auto object-contain"> --}}
    </div>

    <!-- Desktop Navbar -->
    <div class="nav-desktop hidden">
        <ul class="flex flex-row gap-x-8 items-center">
            @php
                $navItems = [
                    ['text' => 'ABOUT US', 'href' => route('user.home') . '#about'],
                    ['text' => 'TOR', 'href' => route('user.home') . '#tor'],
                    ['text' => 'TIMELINE', 'href' => route('user.home') . '#timeline'],
                    ['text' => 'FAQ', 'href' => route('user.home') . '#faq'],
                    ['text' => 'CONTACT', 'href' => route('user.home') . '#contact'],
                    ['text' => 'OUR SPONSOR', 'href' => route('user.home') . '#sponsor'],
                ];
            @endphp

            @foreach ($navItems as $item)
                <li class="nav-desktop-item font-lato">
                    <a href="{{ $item['href'] }}" class="text-base">{{ $item['text'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileOverlay"></div>

<!-- Mobile Menu (Sidebar dari Kanan) -->
<div class="nav-mobile-menu" id="mobileMenu">
    <ul class="mobile-nav">
        @foreach ($navItems as $item)
            <li>
                <a href="{{ $item['href'] }}" class="font-lato">
                    <span>{{ $item['text'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
