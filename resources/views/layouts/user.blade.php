<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ $title }}</title> --}}
    <title>PIFF 2026 | @yield('title', $title ?? 'Welcome')</title>
    <link rel="icon" href="{{ asset('assets/logo/logo_browser_piff.png') }}" type="image/x-icon" />

    {{-- TailwindCSS --}}
    <script src="https://cdn.tailwindcss.com/3.4.5"></script>

    {{-- TW Elements CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Toastify --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    {{-- AOS CSS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Datatables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />

    {{-- Lenis --}}
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.13/dist/lenis.css">

    {{-- Font Utama --}}


    {{-- Panggilnya pakai push ygy --}}
    @stack('styles')
    @yield('head')

    <style>
        .swal2-confirm {
            background-color: #3085d6 !important;
            color: white !important;
        }

        .swal2-cancel {
            background-color: #d33 !important;
            color: white !important;
        }

        .toastify {
            padding: 16px 20px;
            color: #1f2937;
            display: inline-block;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: white;
            position: fixed;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            max-width: calc(100% - 20px);
            z-index: 2147483647;
            overflow: hidden;
            border: 1px solid #f3f4f6;
        }

        @media (min-width: 640px) {
            .toastify {
                max-width: 400px;
            }
        }

        .toastify::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            width: 100%;
            animation: toast-progress 4000ms linear forwards;
        }

        .toast-success::after {
            background-color: #4b4949;
        }

        @keyframes toast-progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .toastify:hover::after {
            animation-play-state: paused;
        }

        /* Font */

        /* Warna utama */
        :root {
            --primary-white: #fef7f7;
            --black: #000;
            --blue: #27b4f7;
            --yellow: #fec401;
            --red: #ff362d;
        }

        /* Reset margin/padding untuk mobile */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        html {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Font */
        /* Inter */
        @font-face {
            font-family: 'InterRegular';
            src: url('{{ asset('assets/fonts/Inter_28pt-Regular.ttf') }}') format('truetype');
        }

        .font-inter-regular {
            font-family: 'InterRegular', sans-serif;
        }

        @font-face {
            font-family: 'InterLight';
            src: url('{{ asset('assets/fonts/Inter_18pt-Light.ttf') }}') format('truetype');
        }

        .font-inter-light {
            font-family: 'InterLight', sans-serif;
        }

        @font-face {
            font-family: 'InterBold';
            src: url('{{ asset('assets/fonts/Inter_18pt-Bold.ttf') }}') format('truetype');
        }

        .font-inter-bold {
            font-family: 'InterBold', sans-serif;
        }

        @font-face {
            font-family: 'InterSemiBold';
            src: url('{{ asset('assets/fonts/Inter_28pt-SemiBold.ttf') }}') format('truetype');
        }

        .font-inter-semibold {
            font-family: 'InterSemiBold', sans-serif;
        }

        /* Montech */

        @font-face {
            font-family: 'MontechBlack';
            src: url('{{ asset('assets/fonts/MONTECHV02-Black.ttf') }}') format('truetype');
        }

        .font-montech-black {
            font-family: 'MontechBlack', sans-serif;
        }

        @font-face {
            font-family: 'MontechBold';
            src: url('{{ asset('assets/fonts/MONTECHV02-Bold.ttf') }}') format('truetype');
        }

        .font-montech-bold {
            font-family: 'MontechBold', sans-serif;
        }

        @font-face {
            font-family: 'MontechMedium';
            src: url('{{ asset('assets/fonts/MONTECHV02-Medium.ttf') }}') format('truetype');
        }

        .font-montech-medium {
            font-family: 'MontechMedium', sans-serif;
        }

        /* Custom Scrollbar */
    </style>
</head>

<body>

    {{-- @include('components.loader') --}}
    @include('components.navbar')


    @yield('content')

    @include('components.footer')

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- AOS JS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- GSAP + Plugins --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/Flip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/MotionPathPlugin.min.js"></script>
    <script src="https://unpkg.com/split-type"></script>

    {{-- TW Elements JS --}}
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>

    {{-- Toastify --}}
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    {{-- Parallax --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js"></script> --}}

    {{-- Lenis --}}
    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>

    {{-- SwiperJS --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> --}}

    {{-- Datatables --}}
    {{-- <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script> --}}

    {{-- Buat rally --}}
    {{-- Ably --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/ably@1.2.36"></script> --}}

    {{-- init global --}}
    <script>
        gsap.registerPlugin(ScrollTrigger);
        AOS.init();
        document.body.classList.add('overflow-x-hidden');

        // Lenis setup dengan kontrol global
        const lenis = new Lenis();
        let lenisActive = true;

        function raf(time) {
            if (lenisActive) {
                lenis.raf(time);
            }
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        // Global functions untuk kontrol Lenis
        // (antisipasi buat timeline)
        window.disableLenis = function() {
            lenisActive = false;
            lenis.stop();
        };

        window.enableLenis = function() {
            lenisActive = true;
            lenis.start();
        };
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
                background: '#0f172a',
                color: '#f1f5f9',
                customClass: {
                    popup: 'rounded-2xl border border-slate-700/50',
                    title: 'text-xl font-bold',
                    content: 'text-slate-300',
                    confirmButton: 'rounded-full px-6 py-2.5 font-semibold'
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#ef4444',
                background: '#0f172a',
                color: '#f1f5f9',
                customClass: {
                    popup: 'rounded-2xl border border-slate-700/50',
                    title: 'text-xl font-bold',
                    content: 'text-slate-300',
                    confirmButton: 'rounded-full px-6 py-2.5 font-semibold'
                }
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#ff5b1d',
                background: '#0f172a',
                color: '#f1f5f9',
                customClass: {
                    popup: 'rounded-2xl border border-slate-700/50',
                    title: 'text-xl font-bold',
                    content: 'text-slate-300',
                    confirmButton: 'rounded-full px-6 py-2.5 font-semibold'
                }
            });
        </script>
    @endif

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                fontFamily: {
                    lexend: ["Lexend", "sans-serif"],
                    sans: ["Open Sans", "sans-serif"],
                    body: ["Open Sans", "sans-serif"],
                    mono: ["ui-monospace", "monospace"],
                },
            },
            corePlugins: {
                preflight: false,
            },
        };
    </script>

    {{-- Panggilnya pakai push ygy --}}
    @stack('scripts')

    {{-- Ini pakai include  --}}
    @yield('script')
</body>

</html>
