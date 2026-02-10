@push('styles')
<style>
    .about-content {
        position: relative;
        z-index: 3;
    }

    .about-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
        letter-spacing: 0.05em;
        line-height: 1.1;
    }

    .about-subtitle {
        font-size: clamp(0.9rem, 2vw, 1.2rem);
        font-weight: 600;
        letter-spacing: 0.1em;
    }

    .about-text-bold {
        font-size: clamp(1rem, 2.2vw, 1.3rem);
        font-weight: 700;
        line-height: 1.6;
        text-align: justify;
    }

    .about-text {
        font-size: clamp(1rem, 2.2vw, 1.3rem);
        font-weight: 400;
        line-height: 1.6;
        text-align: justify;
    }

    .word-rotate {
        display: inline-block;
        white-space: nowrap;
    }

    .rotate-wrapper {
        display: inline-block;
        width: 11ch;
    }

    .word-rotate::after {
        content: '|';
        opacity: 1;
        color: var(--blue);
    }

    .word-rotate.idle::after {
        animation: blink 0.8s infinite;
    }

    @keyframes blink {

        0%,
        49% {
            opacity: 1;
        }

        50%,
        100% {
            opacity: 0;
        }
    }
</style>
@endpush

<div class="relative w-full h-screen overflow-hidden">
    <div class="about-content mx-auto px-16 py-12 flex items-center justify-center h-full">
        <div class="w-full">
            <!-- About Title -->
            <div class="mb-4 justify-center flex">
                <div>
                    <h1 class="about-title text-[--primary-white] text-center md:text-left font-montech-bold mb-2">
                        CELEBRATING
                        <span class="rotate-wrapper text-center md:text-left">
                            <span class="word-rotate rotating-word text-center md:text-left">CREATIVES</span>
                        </span>
                    </h1>
                    <h1 class="about-title text-[--yellow] text-center md:text-left font-montech-bold">
                        HERE, THERE, EVERYWHERE.
                    </h1>
                </div>
            </div>

            <!-- About Subtitle -->
            <div class="text-center mb-12">
                <p class="about-subtitle text-[--yellow] font-montech-medium flex items-center justify-center gap-3">
                    THE HEART
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M16 15l-4 4" />
                            <path d="M8 15l4 4" />
                        </svg></span>
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-right">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="M15 16l4 -4" />
                            <path d="M15 8l4 4" />
                        </svg></span>
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-world">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                            <path d="M3.6 9h16.8" />
                            <path d="M3.6 15h16.8" />
                            <path d="M11.5 3a17 17 0 0 0 0 18" />
                            <path d="M12.5 3a17 17 0 0 1 0 18" />
                        </svg></span>
                    OF PIFF26
                </p>
            </div>

            <!-- Textbox 1 -->
            <div class="mb-8 mx-0 sm:mx-12 md:mx-20 lg:mx-46 xl:mx-60">
                <p class="about-text-bold text-[--primary-white]">
                    Petra International Film Festival (PIFF) is presented by Apresiasi Seni Film dan Sastra (ASFS), a student-led creative community at Petra Christian University.
                </p>
            </div>

            <!-- Textbox 2 -->
            <div class="mx-0 sm:mx-12 md:mx-20 lg:mx-46 xl:mx-60">
                <p class="about-text text-[--primary-white]">
                    Rooted in a long-standing commitment to film education and creative exploration, PIFF serves as a platform for emerging filmmakers to stand in the gaps between perspectives, cultures, and ideas. Through competition, screenings, and dialogue, PIFF celebrates film as a universal language that bridges differences and sparks critical conversations.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        const words = ['CREATIVES', 'FILM MAKERS', 'STUDENTS', 'VOICES', 'DIVERSITY', 'HUMANS', 'IDEAS'];
        const $word = $('.rotating-word');
        let index = 0;

        function animate(word, isDelete, onComplete) {
            $word.removeClass('idle');
            let chars = isDelete ? word.length : 0;
            gsap.to({}, {
                duration: word.length * (isDelete ? 0.08 : 0.12),
                ease: 'none',
                onUpdate: function() {
                    const progress = isDelete ? 1 - this.progress() : this.progress();
                    const newChars = Math.floor(progress * word.length);
                    if (newChars !== chars) {
                        chars = newChars;
                        $word.text(word.substring(0, chars) || ' ');
                    }
                },
                onComplete
            });
        }

        (function cycle() {
            const word = words[index];
            animate(word, false, () => {
                $word.addClass('idle');
                gsap.delayedCall(2, () => {
                    animate(word, true, () => {
                        gsap.delayedCall(0.5, () => {
                            index = (index + 1) % words.length;
                            cycle();
                        });
                    });
                });
            });
        })();
    });
</script>
@endpush