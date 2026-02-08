@push('styles')
<style>
    .title-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
        background-image: url('{{ asset('assets/img/dummy1.png') }}');
        background-size: cover;
        background-position: center;
    }

    .title-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--black);
        opacity: 0.5;
        z-index: 2;
    }

    .title-content {
        position: relative;
        z-index: 3;
        width: 100%;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .piff-stacked {
        display: flex;
        flex-direction: column;
        font-size: clamp(0.7rem, 2.4vw, 1rem);
        letter-spacing: 0.12em;
        line-height: 1.25;
    }

    .big-text {
        font-size: clamp(3rem, 10vw, 10rem);
        font-weight: 900;
        letter-spacing: -0.02em;
        line-height: 0.9;
    }

    .small-text {
        font-size: clamp(0.7rem, 2.5vw, 1rem);
        letter-spacing: 0.1em;
    }

    .divider-line {
        height: 1px;
        background: var(--primary-white);
        width: 100%;
    }
</style>
@endpush

<div class="relative w-full h-screen overflow-hidden">
    <div class="title-bg"></div>

    <div class="title-overlay"></div>

    <div class="title-content flex items-center justify-center h-full">
        <div class="w-full md:mx-20 lg:mx-32 xl:mx-40">
            <!-- Baris 1 -->
            <div class="grid grid-cols-3 items-center mb-2">
                <!-- Kiri -->
                <div class="flex justify-start items-center">
                    <div class="piff-stacked text-[--primary-white]">
                        <span class="font-inter-semibold">Petra</span>
                        <span class="font-inter-semibold">International</span>
                        <span class="font-inter-semibold">Film Festival</span>
                        <span class="font-inter-light">2026</span>
                    </div>
                </div>

                <!-- Tengah -->
                <div class="flex justify-center items-center">
                    <div class="big-text text-[--primary-white] font-montech-black the-text" data-text="THE">THE</div>
                </div>

                <!-- Kanan -->
                <div></div>
            </div>

            <!-- Baris 2 -->
            <div class="grid grid-cols-3 items-center mb-2">
                <!-- Kiri -->
                <div class="flex justify-start items-center">
                    <div class="big-text text-[--primary-white] font-montech-black gap-text" data-text="GAP">GAP</div>
                </div>

                <!-- Tengah -->
                <div class="flex items-center justify-center">
                    <span class="small-text text-[--primary-white] font-inter-semibold">SBY, IDN</span>
                </div>

                <!-- Kanan -->
                <div class="flex items-center justify-end">
                    <div class="divider-line mr-6 sm:mr-16 md:mr-18 lg:mr-28 xl:mr-32"></div>
                    <span class="small-text text-[--primary-white] font-inter-semibold whitespace-nowrap">MAY 29–30</span>
                </div>
            </div>

            <!-- Baris 3 -->
            <div class="grid grid-cols-3 items-center">
                <!-- Kiri -->
                <div></div>

                <!-- Tengah -->
                <div class="flex justify-center">
                    <div class="big-text text-[--primary-white] font-montech-black standers-text" data-text="STANDERS">
                        STANDERS
                    </div>
                </div>

                <!-- Kanan -->
                <div></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(window).on('load', function() {
        // Scramble Text Animation
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const texts = [
            { el: $('.the-text'), text: 'THE', delay: 300 },
            { el: $('.gap-text'), text: 'GAP', delay: 800 },
            { el: $('.standers-text'), text: 'STANDERS', delay: 1300 }
        ];

        function scramble(item) {
            let frame = 0;
            gsap.to({}, {
                duration: 1,
                ease: 'none',
                onUpdate: function() {
                    if (++frame % 4 !== 0) return;
                    const progress = this.progress();
                    const reveal = Math.floor(progress * item.text.length);
                    item.el.text(item.text.split('').map((c, i) => 
                        i < reveal ? c : chars[Math.floor(Math.random() * chars.length)]
                    ).join(''));
                },
                onComplete: () => item.el.text(item.text)
            });
        }

        // Initial
        texts.forEach(item => setTimeout(() => scramble(item), item.delay));

        // Loop
        setInterval(() => texts.forEach((item, i) => 
            setTimeout(() => scramble(item), i * 500)
        ), 4000);
    });
</script>
@endpush
