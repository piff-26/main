@extends('layouts.user')
@section('title', 'Submit Film')

@push('styles')
    <style>
        .submission-title {
            font-weight: bold;
            color: var(--primary-white);
        }

        .spinning-border-box {
            perspective: 1500px;
        }

        /* --- HOLOGRAPHIC CARD --- */
        .holographic-card {
            position: relative;
            display: flex;
            flex-direction: column;
            border-radius: 15px;
            background-color: #111;
            cursor: pointer;
            pointer-events: auto;
            z-index: 2;
            width: 300px;
            max-width: 90vw;
            aspect-ratio: 4 / 5;
            overflow: hidden;

            border: 1px solid #333;
            background: var(--card-gradient, #111);

            /* Posisi default kursor di tengah */
            --mouse-x: 50%;
            --mouse-y: 50%;
            --spotlight-color: rgba(255, 255, 255, 0.15);

            transition-property: border-color, transform, box-shadow;
            transition-duration: 300ms;
            transition-timing-function: ease-out;
            transform: scale3d(1, 1, 1) rotate3d(0, 0, 0, 0deg);
            box-shadow: 0 1px 5px #00000099;
        }

        @media (min-width: 1025px) {
            .holographic-card {
                width: 400px;
            }
        }

        .holographic-card:hover {
            border-color: var(--card-border, #fff);

            box-shadow:
                0 15px 30px 5px rgba(0, 0, 0, 0.6),
                0 0 25px 3px var(--card-border),
                inset 0 0 15px 2px var(--card-border);

            transition-duration: 100ms;
        }

        .holographic-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle 200px at var(--mouse-x) var(--mouse-y), var(--spotlight-color), transparent 100%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: 10;
        }

        .holographic-card:hover::before {
            opacity: 1;
        }

        .holographic-card .flip-card-front {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .img-grayscale,
        .img-color-reveal {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: inherit;
        }

        .img-grayscale {
            filter: grayscale(100%);
            z-index: 1;
        }

        .img-color-reveal {
            z-index: 2;
            opacity: 0;
            transition: opacity 0.3s ease;
            -webkit-mask-image: radial-gradient(circle 180px at var(--mouse-x) var(--mouse-y), black 0%, transparent 100%);
            mask-image: radial-gradient(circle 180px at var(--mouse-x) var(--mouse-y), black 0%, transparent 100%);
        }

        .holographic-card:hover .img-color-reveal {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="relative w-screen left-1/2 -translate-x-1/2 h-auto overflow-hidden mt-16 md:mt-4">
        <div class="absolute inset-0 w-full h-full z-0">
            <img src="{{ asset('assets/img/submission-background.png') }}" class="absolute inset-0 w-full h-full object-cover"
                alt="Background Red">

            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <img src="{{ asset('assets/img/submission-transparent.png') }}"
                    class="absolute -bottom-24 left-1/2 -translate-x-1/2 w-[150%] max-w-none opacity-80"
                    alt="Submit Your Films Overlay">
            </div>

            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        <div class="relative z-10 container mx-auto py-10 md:py-20 px-4">
            <div class="flex flex-col md:flex-row items-center justify-center gap-6 text-center">
                <div data-aos="flip-left" data-aos-duration="3000"
                    class="submission-title font-montech-bold leading-tight animate-from-left text-4xl md:text-[50px] text-center">
                    <h1>SUBMISSIONS</h1>
                    <p class="text-xl">CHOOSE BASED ON YOUR REGION!</p>
                </div>
            </div>

            <div
                class="flex flex-col xl:flex-row items-center justify-center mt-10 md:mt-7 gap-6 md:gap-10 p-4 relative z-10">

                <div data-aos="fade-right" data-aos-duration="3000"
                    class="submission-photo-animation w-full md:w-auto flex justify-center relative">
                    <a href="https://filmfreeway.com/piff-pcu" target="_blank" class="block">
                        <div class="spinning-border-box" id="spinning-border-box-1">
                            <div class="holographic-card chroma-card-item" id="holographic-card-1"
                                style="--card-border: #0092d7; --card-gradient: linear-gradient(145deg, #111, #000);">
                                <div class="flip-card-front">
                                    <img src="{{ asset('assets/img/submit_inter.png') }}" alt="GAP IN A MINUTE"
                                        class="img-grayscale">
                                    <img src="{{ asset('assets/img/submit_inter.png') }}" alt="GAP IN A MINUTE"
                                        class="img-color-reveal">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div data-aos="fade-up" data-aos-duration="3000"
                    class="submission-photo-animation w-full md:w-auto flex justify-center relative">
                    <a href="https://petra.id/PIFF2026Submission" target="_blank" class="block">
                        <div class="spinning-border-box" id="spinning-border-box-2">
                            <div class="holographic-card chroma-card-item" id="holographic-card-2"
                                style="--card-border: #cc2727; --card-gradient: linear-gradient(180deg, #111, #000);">
                                <div class="flip-card-front">
                                    <img src="{{ asset('assets/img/submit_indo.png') }}" alt="STUDENT GAP STANDERS"
                                        class="img-grayscale">
                                    <img src="{{ asset('assets/img/submit_indo.png') }}" alt="STUDENT GAP STANDERS"
                                        class="img-color-reveal">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.chroma-card-item');

            cards.forEach(card => {
                let bounds;

                const rotateToMouse = (e) => {
                    const mouseX = e.clientX;
                    const mouseY = e.clientY;
                    const leftX = mouseX - bounds.x;
                    const topY = mouseY - bounds.y;

                    card.style.setProperty('--mouse-x', `${leftX}px`);
                    card.style.setProperty('--mouse-y', `${topY}px`);

                    const center = {
                        x: leftX - bounds.width / 2,
                        y: topY - bounds.height / 2
                    };
                    const distance = Math.sqrt(center.x ** 2 + center.y ** 2);

                    card.style.transform = `
                        scale3d(1.05, 1.05, 1.05)
                        rotate3d(
                            ${center.y / 100},
                            ${-center.x / 100},
                            0,
                            ${Math.log(distance) * 2}deg
                        )
                    `;
                };

                card.addEventListener('mouseenter', () => {
                    bounds = card.getBoundingClientRect();
                    document.addEventListener('mousemove', rotateToMouse);
                });

                card.addEventListener('mouseleave', () => {
                    document.removeEventListener('mousemove', rotateToMouse);

                    card.style.transform = '';

                    card.style.setProperty('--mouse-x', `50%`);
                    card.style.setProperty('--mouse-y', `50%`);
                });
            });
        });
    </script>
@endpush
