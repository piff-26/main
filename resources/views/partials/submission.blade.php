    @push('styles')
        <style>
            .submission-title {
                font-weight: bold;
                color: var(--primary-white);
            }

            /* --- Definisi Animasi Putar --- */
            @keyframes border-spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            /* --- Container Terluar Baru (Untuk efek Gasing Putar) --- */
            .spinning-border-box {
                position: relative;
                display: inline-block;
                /* Padding ini menentukan ketebalan garis yang berputar */
                padding: 4px;
                border-radius: 20px;
                /* Radius sudut luar */
                overflow: hidden;
                /* Memotong gradien yang berputar agar berbentuk kotak tumpul */
                z-index: 1;
            }

            /* Layer Berputar (Conic Gradient) */
            .spinning-border-box::before {
                content: '';
                position: absolute;
                z-index: -2;
                /* Taruh paling belakang */
                left: -50%;
                top: -50%;
                width: 200%;
                height: 200%;
                background-repeat: no-repeat;
                background-position: 0 0;
                /* Membuat gradien melingkar. Ubah #00ffff ke warna lain jika mau */
                /* Animasi berputar terus menerus */
                animation: border-spin 3s linear infinite;
            }

            #spinning-border-box-1::before {
                background-image: conic-gradient(transparent, rgb(38, 179, 248), transparent 30%);
            }

            #spinning-border-box-2::before {
                background-image: conic-gradient(transparent, rgb(253, 195, 1), transparent 30%);
            }

            #spinning-border-box-3::before {
                background-image: conic-gradient(transparent, rgb(255, 0, 0), transparent 30%);
            }

            /* Layer Tambahan untuk efek Glow di belakangnya (Optional, biar lebih halus) */
            .spinning-border-box::after {
                content: '';
                position: absolute;
                z-index: -1;
                left: 6px;
                top: 6px;
                width: calc(100% - 12px);
                height: calc(100% - 12px);
                background: #000;
                border-radius: 15px;
                filter: blur(5px);
                /* Memberi efek blur pada glow */
            }


            /* --- Kartu Holografik (Bagian Dalam) --- */
            /* Ini adalah style yang mirip sebelumnya, tapi disesuaikan */
            .holographic-card {
                position: relative;
                display: block;
                overflow: hidden;
                border-radius: 15px;
                /* Harus sedikit lebih kecil dari radius container luar */
                background-color: #111;
                /* Penting! Background gelap untuk menutupi tengah putaran */
                transition: all 0.5s ease;
                cursor: pointer;
                z-index: 2;
                /* Pastikan berada DI ATAS border yang berputar */
                width: 100%;
                max-width: 380px;
                aspect-ratio: 4 / 5;
            }

            /* Gambar di dalam kartu */
            .holographic-card img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            /* Efek Kilau "Swoosh" saat Hover (Efek lama dipertahankan) */
            .holographic-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                transition: all 0.6s;
                z-index: 3;
            }

            #holographic-card-1::before {
                background: linear-gradient(120deg,
                        transparent,
                        rgba(38, 179, 248, 0.5),
                        /* Warna kilau hover */
                        transparent);
            }

            #holographic-card-2::before {
                background: linear-gradient(120deg,
                        transparent,
                        rgba(253, 195, 1, 0.5),
                        /* Warna kilau hover */
                        transparent);
            }

            #holographic-card-3::before {
                background: linear-gradient(120deg,
                        transparent,
                        rgba(204, 50, 44, 0.5),
                        /* Warna kilau hover */
                        transparent);
            }

            /* --- Hover Effects --- */
            /* Saat wrapper terluar di-hover, efek di kartu dalam aktif */
            .spinning-border-box:hover .holographic-card {
                transform: scale(1.05);
                /* Membesar */
                box-shadow: 0 0 25px rgba(0, 255, 255, 0.6);
                /* Glow tambahan saat hover */
            }

            /* Menjalankan kilau "swoosh" */
            .spinning-border-box:hover .holographic-card::before {
                left: 100%;
            }
        </style>
    @endpush

    <div class="relative w-screen left-1/2 -translate-x-1/2 h-auto overflow-hidden">
        <div class="absolute inset-0 w-full h-full z-0">
            <img src="{{ asset('assets/img/submission-background.png') }}"
                class="absolute inset-0 w-full h-full object-cover" alt="Background Red">

            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <img src="{{ asset('assets/img/submission-transparent.png') }}"
                    class="absolute -bottom-24 left-1/2 -translate-x-1/2 
                            w-[150%] max-w-none 
                            opacity-80"
                    alt="Submit Your Films Overlay">
            </div>

            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        <div class="relative z-10 container mx-auto py-10 md:py-20 px-4">
            <div class="flex flex-col md:flex-row items-center justify-center gap-6 text-right">
                <div data-aos="flip-left" data-aos-duration="3000"
                    class="submission-title font-montech-bold leading-tight animate-from-left text-4xl md:text-[50px] text-right">
                    <h1>
                        SUBMISSIONS <br>
                        ARE NOW OPEN!
                    </h1>
                </div>

                <div data-aos="flip-right" data-aos-duration="3000"
                    class="submission-title font-inter-regular animate-from-right text-base md:text-[20px] text-left">
                    Select one of the <br>
                    categories to learn more <br>
                    or submit your films!
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center mt-10 md:mt-7 gap-6 md:gap-10">

                <div data-aos="fade-right" data-aos-duration="3000"
                    class="submission-photo-animation w-full md:w-auto flex justify-center">
                    <div class="spinning-border-box" id="spinning-border-box-1">
                        <div class="holographic-card" id="holographic-card-1">
                            <img src="{{ asset('assets/img/poster_submission_1.png') }}" alt="GAP IN A MINUTE">
                        </div>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="3000"
                    class="submission-photo-animation w-full md:w-auto flex justify-center">
                    <div class="spinning-border-box" id="spinning-border-box-2">
                        <div class="holographic-card" id="holographic-card-2">
                            <img src="{{ asset('assets/img/poster_submission_2.png') }}" alt="STUDENT GAP STANDERS">
                        </div>
                    </div>
                </div>

                <div data-aos="fade-left" data-aos-duration="3000"
                    class="submission-photo-animation w-full md:w-auto flex justify-center">
                    <div class="spinning-border-box" id="spinning-border-box-3">
                        <div class="holographic-card" id="holographic-card-3">
                            <img src="{{ asset('assets/img/poster_submission_3.png') }}" alt="VOICES IN THE GAP">
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        <!-- <script>
            gsap.registerPlugin(ScrollTrigger);

            gsap.from(".animate-from-left", {
                scrollTrigger: {
                    trigger: ".animate-from-left",
                    start: "top 80%",
                    toggleActions: "play pause resume reverse"
                },
                x: -150,
                opacity: 0,
                duration: 1.5,
                ease: "power2.out"
            });

            gsap.from(".animate-from-right", {
                scrollTrigger: {
                    trigger: ".animate-from-right",
                    start: "top 80%",
                    toggleActions: "play pause resume reverse"
                },
                x: 150,
                opacity: 0,
                duration: 1.5,
                ease: "power2.out"
            });
        </script> -->
    @endpush
