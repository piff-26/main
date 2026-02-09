@push('styles')
<style>
    .schedule-title {
        font-size: clamp(2.5rem, 7.5vw, 4rem);
        font-weight: 700;
        letter-spacing: 0.05em;
        line-height: 1.1;
    }

    .schedule-section {
        position: relative;
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--black);
        overflow: hidden;
        cursor: grab;
    }

    .schedule-section:active {
        cursor: grabbing;
    }

    .schedule-track {
        display: flex;
        gap: 2rem;
        position: relative;
        user-select: none;
    }

    .schedule-card {
        min-width: 260px;
        width: 260px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid var(--primary-white);
        border-radius: 16px;
        overflow: hidden;
        opacity: 0.6;
        transform: scale(0.9);
        transition: all 0.4s ease;
    }

    @media (min-width: 768px) {
        .schedule-card {
            min-width: 350px;
            width: 350px;
        }
    }

    .schedule-card.active {
        opacity: 1;
        transform: scale(1);
        border-color: var(--yellow);
        box-shadow: 0 0 40px var(--yellow);
    }

    .card-head {
        padding: 1rem;
        background: rgba(128, 128, 128, 0.3);
        border-bottom: 2px solid var(--primary-white);
    }

    @media (min-width: 768px) {
        .card-head {
            padding: 1.5rem;
        }
    }

    .card-date {
        font-size: 0.7rem;
        color: var(--primary-white);
        font-weight: 400;
        margin-bottom: 0.25rem;
        letter-spacing: 0.03em;
        line-height: 1.2;
    }

    @media (min-width: 768px) {
        .card-date {
            font-size: 0.85rem;
        }
    }

    .card-date sup {
        font-size: 0.5rem;
        color: var(--primary-white);
        font-weight: 400;
        letter-spacing: 0.03em;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-white);
        letter-spacing: 0.05em;
        line-height: 1.1;
    }

    @media (min-width: 768px) {
        .card-title {
            font-size: 2rem;
        }
    }

    .card-body {
        width: 260px;
        height: 260px;
        background: var(--primary-white);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--black);
        font-size: 1rem;
        overflow: hidden;
    }

    @media (min-width: 768px) {
        .card-body {
            width: 350px;
            height: 350px;
        }
    }

    .card-body img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>
@endpush

<div class="schedule-section py-16" id="scheduleSection">
    <div>
        <!-- Schedule Title -->
        <h1 class="text-center schedule-title font-bold text-[--yellow] mb-12 font-montech-black">SCHEDULE</h1>
        <div class="schedule-track" id="scheduleTrack">

            <!-- Card 1 -->
            <div class="schedule-card">
                <div class="card-head">
                    <div class="card-date font-montech-medium">MARCH 1<sup class="font-montech-medium">ST</sup> – MAY 10<sup class="font-montech-medium">TH</sup></div>
                    <div class="card-title font-montech-bold">OPEN</div>
                    <div class="card-title font-montech-bold">SUBMISSION</div>
                </div>
                <div class="card-body">
                    <img src="assets/img/dummy1.png" alt="Open Submission">
                </div>
            </div>

            <!-- Card 2 -->
            <div class="schedule-card">
                <div class="card-head">
                    <div class="card-date font-montech-medium">MAY 29<sup class="font-montech-medium">TH</sup></div>
                    <div class="card-title font-montech-bold">OPENING &</div>
                    <div class="card-title font-montech-bold">SCREENING</div>
                </div>
                <div class="card-body">
                    <img src="assets/img/dummy1.png" alt="Opening & Screening">
                </div>
            </div>

            <!-- Card 3 -->
            <div class="schedule-card">
                <div class="card-head">
                    <div class="card-date font-montech-medium">MAY 30<sup class="font-montech-medium">TH</sup></div>
                    <div class="card-title font-montech-bold">TALKSHOW &</div>
                    <div class="card-title font-montech-bold">AWARDING</div>
                </div>
                <div class="card-body">
                    <img src="assets/img/dummy1.png" alt="Talkshow & Awarding">
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        const $section = $('#scheduleSection');
        const $track = $('#scheduleTrack');
        const $cards = $('.schedule-card');
        const totalCards = $cards.length;
        const GAP = 32;

        let currentIndex = 0;
        let isDragging = false;
        let startX = 0;
        let currentX = 0;
        let dragOffset = 0;
        let resizeTimer;
        let autoScrollInterval;
        let isAutoScrollActive = true;

        const getCardWidth = () => window.innerWidth < 768 ? 260 : 350;

        function getTargetX(index) {
            const cardWidth = getCardWidth();
            return ($section.outerWidth() - cardWidth) / 2 - index * (cardWidth + GAP);
        }

        function updateCards() {
            $cards.removeClass('active').eq(currentIndex).addClass('active');
        }

        function snapToCard() {
            gsap.to($track[0], {
                x: getTargetX(currentIndex),
                duration: 0.5,
                ease: 'power3.out'
            });
            updateCards();
        }

        function startAutoScroll() {
            if (autoScrollInterval) clearInterval(autoScrollInterval);
            autoScrollInterval = setInterval(() => {
                if (!isAutoScrollActive) return;
                
                if (currentIndex < totalCards - 1) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                snapToCard();
            }, 3000);
        }

        function stopAutoScroll() {
            isAutoScrollActive = false;
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }
        }

        function resumeAutoScroll() {
            isAutoScrollActive = true;
            startAutoScroll();
        }

        gsap.set($track[0], { x: getTargetX(0) });
        updateCards();
        startAutoScroll();

        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(snapToCard, 150);
        });

        function onDragStart(clientX) {
            stopAutoScroll();
            isDragging = true;
            startX = clientX;
            currentX = gsap.getProperty($track[0], 'x');
            gsap.killTweensOf($track[0]);
        }

        function onDragMove(clientX) {
            if (!isDragging) return;
            dragOffset = clientX - startX;
            gsap.set($track[0], { x: currentX + dragOffset });
        }

        function onDragEnd() {
            if (!isDragging) return;
            isDragging = false;
            const threshold = (getCardWidth() + GAP) * 0.3;
            if (dragOffset < -threshold && currentIndex < totalCards - 1) currentIndex++;
            else if (dragOffset > threshold && currentIndex > 0) currentIndex--;
            snapToCard();
            resumeAutoScroll();
        }

        $section.on('mousedown', e => onDragStart(e.clientX));
        $(document).on('mousemove', e => { if (isDragging) { e.preventDefault(); onDragMove(e.clientX); } });
        $(document).on('mouseup', onDragEnd);

        $section.on('touchstart', e => onDragStart(e.touches[0].clientX));
        $section.on('touchmove', e => { if (isDragging) { e.preventDefault(); onDragMove(e.touches[0].clientX); } });
        $section.on('touchend', onDragEnd);
        $section.on('dragstart', e => e.preventDefault());
    });
</script>
@endpush