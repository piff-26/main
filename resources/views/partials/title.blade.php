@push('styles')
<style>
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

    :root {
        --line-gap: 0.75rem;
    }

    .text-container {
        margin-left: 0;
        margin-right: 0;
    }

    @media (min-width: 768px) {
        .text-container {
            margin-left: clamp(8rem, 20vw, 30rem);
            margin-right: clamp(8rem, 20vw, 30rem);
        }
    }

    .line-dynamic {
        position: absolute;
        height: 2px;
        background: var(--primary-white);
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 2;
    }
</style>
@endpush

<div class="relative w-full h-screen overflow-hidden">
    <div class="title-content flex items-center justify-center h-full">
        <div class="w-full text-container">
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
            <div class="grid grid-cols-3 items-center mb-2 relative">

                <div class="line-dynamic"></div>

                <!-- Kiri -->
                <div class="flex justify-start items-center">
                    <div class="big-text text-[--primary-white] font-montech-black gap-text">
                        GAP
                    </div>
                </div>

                <!-- Tengah -->
                <div class="flex items-center justify-center">
                    <span class="small-text text-[--primary-white] font-inter-semibold mid-text bg-black px-2">
                        SBY, IDN
                    </span>
                </div>

                <!-- Kanan -->
                <div class="flex items-center justify-end">
                    <span class="small-text text-[--primary-white] font-inter-semibold whitespace-nowrap right-text bg-black px-2">
                        MAY 29–30
                    </span>
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
        const texts = [{
                el: $('.the-text'),
                text: 'THE',
                delay: 300
            },
            {
                el: $('.gap-text'),
                text: 'GAP',
                delay: 800
            },
            {
                el: $('.standers-text'),
                text: 'STANDERS',
                delay: 1300
            }
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

        function updateLine() {
            const $mid = $('.mid-text');
            const $right = $('.right-text');
            const $line = $('.line-dynamic');
            const $row = $line.parent();

            if (!$mid.length || !$right.length) return;

            const gap = 2;

            const midRect = $mid[0].getBoundingClientRect();
            const rightRect = $right[0].getBoundingClientRect();
            const rowRect = $row[0].getBoundingClientRect();

            const left = midRect.right - rowRect.left + gap;
            const width = rightRect.left - rowRect.left - gap - left;

            gsap.set($line, {
                left,
                width: Math.max(0, width)
            });
        }

        updateLine();

        $(window).on('resize', updateLine);

        // Initial
        texts.forEach(item => setTimeout(() => scramble(item), item.delay));

        // Loop
        setInterval(() => texts.forEach((item, i) =>
            setTimeout(() => scramble(item), i * 500)
        ), 4000);
    });
</script>
@endpush
