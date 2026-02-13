@push('styles')
<style>
    .schedule-title {
        font-size: clamp(2.25rem, 7.5vw, 4rem);
        font-weight: 700;
        letter-spacing: 0.05em;
        line-height: 1.1;
        margin-bottom: 0.5rem;
    }

    @media (min-width: 768px) {
        .schedule-title {
            font-size: clamp(2.5rem, 7.5vw, 4rem);
            margin-bottom: 3rem;
        }
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
        padding: 0.25rem;
    }

    .schedule-section > div {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    @media (min-width: 768px) {
        .schedule-section {
            padding: 4rem 1rem;
        }
    }

    .schedule-track {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        position: relative;
        align-items: center;
        width: 100%;
        max-width: 98%;
    }

    @media (min-width: 768px) {
        .schedule-track {
            flex-direction: row;
            gap: 2rem;
            max-width: none;
        }
    }

    .schedule-card {
        width: 100%;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid var(--primary-white);
        border-radius: 8px;
        overflow: hidden;
        opacity: 0;
        transform: translateY(150vh) scale(0.9);
        transition: all 0.4s ease;
        will-change: transform, opacity;
        display: flex;
        flex-direction: row;
        height: 120px;
    }

    @media (min-width: 768px) {
        .schedule-card {
            min-width: 350px;
            width: 350px;
            border-radius: 16px;
            flex-direction: column;
            height: auto;
        }
    }

    .schedule-card.active {
        border-color: var(--yellow);
        box-shadow: 0 0 40px var(--yellow);
    }

    .schedule-card.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .card-head {
        padding: 0.6rem;
        background: rgba(128, 128, 128, 0.3);
        border-right: 2px solid var(--primary-white);
        width: calc(100% - 120px);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (min-width: 768px) {
        .card-head {
            padding: 1.5rem;
            border-right: none;
            border-bottom: 2px solid var(--primary-white);
            width: 100%;
        }
    }

    .card-date {
        font-size: 0.55rem;
        color: var(--primary-white);
        font-weight: 400;
        margin-bottom: 0.15rem;
        letter-spacing: 0.03em;
        line-height: 1.2;
    }

    @media (min-width: 768px) {
        .card-date {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
    }

    .card-date sup {
        font-size: 0.4rem;
        color: var(--primary-white);
        font-weight: 400;
        letter-spacing: 0.03em;
    }

    @media (min-width: 768px) {
        .card-date sup {
            font-size: 0.5rem;
        }
    }

    .card-title {
        font-size: 0.9rem;
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
        width: 120px;
        height: 120px;
        min-width: 120px;
        min-height: 120px;
        background: var(--primary-white);
        display: block;
        overflow: hidden;
        flex-shrink: 0;
    }

    @media (min-width: 768px) {
        .card-body {
            width: 350px;
            height: 350px;
            min-width: 350px;
            min-height: 350px;
        }
    }

    .card-body img {
        width: 100%;
        height: 100%;
        display: block;
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
    gsap.registerPlugin(ScrollTrigger);

    $(window).on('load', function () {

        let tl = gsap.timeline({
            scrollTrigger: {
                trigger: "#scheduleSection",
                start: "top top",
                end: "+=3000",
                pin: true,
                scrub: 1,
            }
        });

        tl.to("#scheduleTrack .schedule-card:nth-child(1)", {
            y: 0,
            opacity: 1,
            scale: 1,
            duration: 1,
            ease: "power3.out"
        });

        tl.to("#scheduleTrack .schedule-card:nth-child(2)", {
            y: 0,
            opacity: 1,
            scale: 1,
            duration: 1,
            ease: "power3.out"
        });

        tl.to("#scheduleTrack .schedule-card:nth-child(3)", {
            y: 0,
            opacity: 1,
            scale: 1,
            duration: 1,
            ease: "power3.out"
        });

        tl.to(".schedule-card", {
            y: "-150vh",
            opacity: 0,
            duration: 1.5,
            stagger: 0.1
        }, "+=0.5");

    });
</script>
@endpush