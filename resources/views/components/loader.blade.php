<div id="cinema-loader"
    class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden pointer-events-none">

    <div id="black-curtain" class="absolute inset-0 z-0 bg-black will-change-opacity"></div>

    <div id="logo-wrapper" class="relative z-10 w-full max-w-lg px-6 will-change-transform"
        style="transform-origin: 16.3% 35%;">

        <svg id="main-logo" viewBox="0 0 916 336" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path class="logo-path" id="target-triangle"
                d="M112.27 79.66L186.72 113.77L112.31 154.64V91.59V91.4474L112.27 79.66Z" fill="white" />
            <path class="logo-path"
                d="M0 0V335.65H915.12V0H0ZM231.38 160.38C223.58 173.19 212.61 183.08 198.48 190.06C184.36 197.04 167.88 200.53 149.08 200.53H112.31V283.24H45.19V37.47H150.74C169.31 37.47 185.54 41.05 199.39 48.2C213.24 55.36 223.99 65.43 231.63 78.46C239.27 91.5 243.1 102.7 243.1 120.18C243.1 137.66 239.2 147.57 231.38 160.38ZM379.89 283.24H312.76V37.47H379.89V283.24ZM625.04 92.24H516.7V145.02H615.48V198.12H516.7V283.24H449.55V37.47H625.04V92.24ZM870.18 92.24H761.84V145.02H860.61V198.12H761.84V283.24H694.71V37.47H870.18V92.24Z"
                fill="white" />
        </svg>

        <div id="loading-text"
            class="absolute -bottom-16 left-0 right-0 text-center text-neutral-500 font-mono text-[10px] tracking-[0.6em] animate-pulse">
            ROLLING THE FILM...
        </div>
    </div>

    <div id="flashlight-overlay" class="absolute inset-0 z-20 will-change-[background, opacity]"
        style="
            --x: 50%;
            --y: 50%;
            --r: 850px;
            background: radial-gradient(circle var(--r) at var(--x) var(--y), transparent 0%, rgba(0,0,0,0.98) 35%, black 100%);
            animation: spotlight-sequence 4s cubic-bezier(0.45, 0, 0.55, 1) forwards;
        ">
    </div>
</div>

<style>
    /* --- animasi glow pulse --- */
    @keyframes pulse-glow {

        0%,
        100% {
            /* Keadaan Redup (Glow tipis) */
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5)) drop-shadow(0 0 15px rgba(255, 255, 255, 0.2));
        }

        50% {
            /* Keadaan Terang (Glow tebal & luas) */
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 1.0)) drop-shadow(0 0 40px rgba(255, 255, 255, 0.7)) drop-shadow(0 0 80px rgba(255, 255, 255, 0.4));
        }
    }

    /* Terapkan animasi ke SVG utama */
    #main-logo {
        /* Durasi 3 detik, berulang selamanya, gerakannya halus (ease-in-out) */
        animation: pulse-glow 3s ease-in-out infinite;
    }

    /* Saat zoom aktif, matikan animasi glow agar transisi ke garis lebih mulus */
    .zoom-active #main-logo {
        animation: none;
        filter: none;
        /* Hapus filter drop-shadow saat jadi garis */
        transition: filter 0.5s ease;
    }


    /* CSS Flashlight (Radius dikembalikan ke 850px/250px agar kontras) */
    @property --x {
        syntax: '<percentage>';
        inherits: false;
        initial-value: 50%;
    }

    @property --y {
        syntax: '<percentage>';
        inherits: false;
        initial-value: 50%;
    }

    @property --r {
        syntax: '<length>';
        inherits: false;
        initial-value: 850px;
    }

    @keyframes spotlight-sequence {
        0% {
            --x: 50%;
            --y: 50%;
            --r: 850px;
        }

        25% {
            --x: 75%;
            --y: 55%;
            --r: 850px;
        }

        50% {
            --x: 25%;
            --y: 40%;
            --r: 850px;
        }

        75% {
            --x: 50%;
            --y: 60%;
            --r: 850px;
        }

        100% {
            --x: 38%;
            --y: 48%;
            --r: 850px;
        }
    }

    @media (max-width: 768px) {
        @keyframes spotlight-sequence {
            0% {
                --x: 50%;
                --y: 50%;
            }

            25% {
                --x: 80%;
                --y: 50%;
            }

            50% {
                --x: 20%;
                --y: 50%;
            }

            100% {
                --x: 25%;
                --y: 48%;
                --r: 200px;
            }
        }
    }

    /* CSS Transisi Akhir (Zoom) */
    .zoom-active #logo-wrapper {
        transform: scale(300);
        transition: transform 1.5s cubic-bezier(0.7, 0, 0.3, 1);
    }

    .zoom-active #black-curtain {
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .zoom-active #flashlight-overlay {
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .logo-path {
        transition: all 0.5s ease;
    }

    .zoom-active .logo-path {
        fill: transparent;
        stroke: white;
        stroke-width: 0.5px;
        vector-effect: non-scaling-stroke;
    }

    .zoom-active #loading-text {
        opacity: 0;
        transition: opacity 0.3s;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const loader = document.getElementById('cinema-loader');
        const MIN_DURATION = 4000;
        const ZOOM_DURATION = 1500;
        let isPageLoaded = false;
        window.addEventListener('load', () => {
            isPageLoaded = true;
        });

        const triggerPortal = () => {
            loader.classList.add('zoom-active');
            document.body.style.overflow = 'auto';
            setTimeout(() => {
                loader.remove();
            }, ZOOM_DURATION);
        };

        setTimeout(() => {
            if (isPageLoaded) {
                triggerPortal();
            } else {
                window.addEventListener('load', triggerPortal);
            }
        }, MIN_DURATION);
    });
</script>
