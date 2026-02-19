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
            --r: 950px;
            background: radial-gradient(circle var(--r) at var(--x) var(--y), transparent 0%, rgba(0,0,0,0.98) 35%, black 100%);
        ">
    </div>
</div>

<style>
    @keyframes pulse-glow {

        0%,
        100% {
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5)) drop-shadow(0 0 15px rgba(255, 255, 255, 0.2));
        }

        50% {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 1.0)) drop-shadow(0 0 40px rgba(255, 255, 255, 0.7)) drop-shadow(0 0 80px rgba(255, 255, 255, 0.4));
        }
    }

    #main-logo {
        animation: pulse-glow 3s ease-in-out infinite;
    }

    .zoom-active #main-logo {
        animation: none;
        filter: none;
        transition: filter 0.5s ease;
    }

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
        initial-value: 950px;
    }

    @keyframes spotlight-sequence {
        0% {
            --x: 50%;
            --y: 50%;
        }

        /* Tengah */
        25% {
            --x: 80%;
            --y: 60%;
        }

        /* Kanan Bawah */
        50% {
            --x: 20%;
            --y: 40%;
        }

        /* Kiri Atas */
        75% {
            --x: 70%;
            --y: 30%;
        }

        /* Kanan Atas */
        100% {
            --x: 30%;
            --y: 70%;
        }

    }

    #flashlight-overlay {
        animation: spotlight-sequence 5s ease-in-out infinite alternate;
    }

    @media (max-width: 768px) {
        #flashlight-overlay {
            --r: 250px;
        }
    }


    /* --- transisi akhir (.zoom-active) --- */
    .zoom-active #logo-wrapper {
        transform: scale(600);
        transition: transform 1.5s cubic-bezier(0.7, 0, 0.3, 1);
    }

    /* Mobile Zoom Adjustment */
    @media (max-width: 768px) {
        #logo-wrapper {
            transform-origin: 20.3% 33.5% !important;
        }

        .zoom-active #logo-wrapper {
            transform: scale(400);
        }
    }

    /* Fade Out Curtains */
    .zoom-active #black-curtain {
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .zoom-active #flashlight-overlay {
        /* Matikan animasi loop "mencari" */
        animation: none;

        /* Paksa pindah ke posisi Segitiga (Desktop) */
        --x: 38%;
        --y: 48%;

        /* Fade out opacity */
        opacity: 0;

        /* Transisi:
           - Opacity 1s (menghilang)
           - --x & --y 0.8s (meluncur ke posisi lock segitiga)
        */
        transition: opacity 1s ease-in-out, --x 0.8s ease-out, --y 0.8s ease-out;
    }

    /* Override Posisi Lock untuk Mobile */
    @media (max-width: 768px) {
        .zoom-active #flashlight-overlay {
            --x: 25%;
            /* Posisi Lock Mobile */
            --y: 48%;
        }
    }

    /* Logo Style Change */
    .logo-path {
        transition: all 0.5s ease;
    }

    .zoom-active .logo-path {
        fill: transparent;
        stroke: white;
        stroke-width: 0.5px;
        vector-effect: non-scaling-stroke;
    }

    /* Hide Text */
    .zoom-active #loading-text {
        opacity: 0;
        transition: opacity 0.3s;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const loader = document.getElementById('cinema-loader');
        const MIN_DURATION = 2000;
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
