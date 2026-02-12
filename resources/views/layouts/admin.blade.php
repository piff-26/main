<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PIFF 2026 | @yield('title', $title ?? 'Welcome')</title>
    {{-- <link rel="shortcut icon" href="{{ asset('assets/logo/logo_pce_polos.png') }}" type="image/x-icon"> --}}

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

    {{-- Datatables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');
        @import url("https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap");

        .swal2-confirm {
            background-color: #3085d6 !important;
            color: white !important;
        }

        .swal2-cancel {
            background-color: #d33 !important;
            color: white !important;
        }

        #sidenav-8 {
            background-color: white !important;
        }

        * {
            font-family: 'Lexend', sans-serif;
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

        /* Warna utama */
        :root {
            --primary-white: #fef7f7;
            --black: #000;
            --blue: #27b4f7;
            --yellow: #fec401;
            --red: #ff362d;
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
    </style>

    {{-- Panggilnya pakai push ygy --}}
    @stack('styles')
    @yield('head')
</head>

<body>

    @if (request()->is('admin/login') || request()->is('admin/auth/google'))
        @yield('content')
    @else
        @include('components.sidebar')
        <div class="ml-0 md:ml-60 px-3 md:px-8 py-2 md:py-3">
            <div class="mt-3">
                @yield('content')
            </div>
        </div>
    @endif


    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- TW Elements JS --}}
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>

    {{-- Toastify --}}
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    {{-- Datatables --}}
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Buat rally --}}
    {{-- Ably --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/ably@1.2.36"></script> --}}

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
            Toastify({
                text: "{{ session('success') }}",
                duration: 4000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                className: "toast-success",
                style: {
                    background: "#fff",
                }
            }).showToast();
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
