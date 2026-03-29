@push('styles')
<style>
/* Efek Fade In saat Halaman Dimuat */
body {
    margin: 0;
    padding: 0;
    background: #fec401;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
}

body.fade-in {
    opacity: 1;
}

body.fade-out {
    opacity: 0;
}

.perspective-wrap { 
    perspective: 1000px; 
}

.ticket-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 40px;
    cursor: pointer;
    transform-style: preserve-3d;
}

.ticket-border-svg {
    position: absolute;
    top: -6px; 
    left: -6px;
    width: calc(100% + 12px);
    height: calc(100% + 12px);
    fill: none;
    pointer-events: none;
    z-index: 20;
    will-change: transform;
}

.ticket-path {
    stroke: #ffffff;
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-dasharray: 100, 1300; 
    stroke-dashoffset: 0;
    transition: opacity 0.4s ease;
    opacity: 0;
    filter: drop-shadow(0 0 4px rgba(255,255,255,0.9));
}

@keyframes run-border {
    from { stroke-dashoffset: 1400; }
    to { stroke-dashoffset: 0; }
}

.ticket-wrapper:hover .ticket-path {
    opacity: 1;
    animation: run-border 3s linear infinite;
}

.ticket-card {
    display: flex;
    position: relative;
    width: 100%;
    background: white;
    border-radius: 15px;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));
    overflow: hidden;
    transform-style: preserve-3d;
    transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1);
}

.ticket-left {
    flex: 0 0 65%;
    padding: 28px;
    background: white;
    border-radius: 15px 0 0 15px;
    border-right: 2px dashed #fec401;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.ticket-right {
    flex: 1;
    background: #333333;
    color: white;
    padding: 24px;
    border-radius: 0 15px 15px 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.btn-animate {
    border: 1.5px solid white;
    border-radius: 999px;
    padding: 8px 20px;
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 800;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    background: transparent;
    color: white;
    margin-top: 15px;
}

.ticket-wrapper:hover .btn-animate {
    background: white;
    color: #333333;
    transform: scale(1.05);
}

/* ============================= */
/* RESPONSIVE */
/* ============================= */

@media (max-width: 768px) {

    .ticket-card {
        flex-direction: column;
    }

    .ticket-left {
        flex: unset;
        width: 100%;
        border-right: none;
        border-bottom: 2px dashed #fec401;
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }

    .ticket-right {
        width: 100%;
        border-radius: 0 0 15px 15px;
        padding: 20px;
    }

    .ticket-wrapper {
        margin-bottom: 24px;
    }

    .ticket-left h3 {
        font-size: 16px;
    }

    .ticket-right p {
        font-size: 13px;
    }

    .btn-animate {
        font-size: 11px;
        padding: 10px 18px;
    }
}

/* mobile kecil */
@media (max-width: 480px) {

    .ticket-left {
        padding: 18px;
    }

    .ticket-right {
        padding: 18px;
    }

    .ticket-left h3 {
        font-size: 15px;
    }

    .btn-animate {
        width: 100%;
    }
}

</style>
@endpush


<div class="relative w-screen left-1/2 -translate-x-1/2 h-auto bg-[#fec401] min-h-screen py-20 overflow-x-hidden">
    <div class="relative z-10 container mx-auto px-4 max-w-2xl">
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 mb-12">
            <h1 data-aos="fade-right" data-aos-duration="1000" class="font-montech-medium text-4xl sm:text-[50px] text-black">SESSION</h1>
            <h1 data-aos="fade-left" data-aos-duration="1000" class="font-montech-bold text-4xl sm:text-[50px] text-black">ACCESS</h1>
        </div>

        <!-- CARD 1 -->
        <div class="ticket-wrapper js-tilt-card" data-aos="fade-right" data-aos-duration="1000">
            <svg class="ticket-border-svg" viewBox="0 0 500 160" preserveAspectRatio="none">
                <rect x="2" y="2" width="496" height="156" rx="15" class="ticket-path"/>
            </svg>

            <div class="ticket-card">
                <div class="ticket-left">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Day 1</p>
                    <h3 class="font-montech-bold text-lg border-b-2 border-black pb-1 mb-2 uppercase">
                        Opening Ceremony
                    </h3>
                    <h3 class="font-montech-bold text-lg leading-tight uppercase">
                        Voices In The Gap<br>Screening Session
                    </h3>
                </div>

                <div class="ticket-right">
                    <p class="font-bold text-sm uppercase">
                        May 29th 2026<br>09.00 WIB
                    </p>
                    <button class="btn-animate">Claim Free Tickets</button>
                </div>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="ticket-wrapper js-tilt-card" data-aos="fade-right" data-aos-delay="200">
            <svg class="ticket-border-svg" viewBox="0 0 500 160" preserveAspectRatio="none">
                <rect x="2" y="2" width="496" height="156" rx="15" class="ticket-path"/>
            </svg>

            <div class="ticket-card">
                <div class="ticket-left">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Day 1</p>
                    <h3 class="font-montech-bold text-lg leading-tight uppercase">
                        Student Gap Standers<br>Screening Session
                    </h3>
                </div>

                <div class="ticket-right">
                    <p class="font-bold text-sm uppercase">
                        May 29th 2026<br>14.00 WIB
                    </p>
                    <button class="btn-animate">Purchase Tickets</button>
                </div>
            </div>
        </div>

        <!-- CARD 3 -->
        <div class="ticket-wrapper js-tilt-card" data-aos="fade-right" data-aos-delay="400">
            <svg class="ticket-border-svg" viewBox="0 0 500 160" preserveAspectRatio="none">
                <rect x="2" y="2" width="496" height="156" rx="15" class="ticket-path"/>
            </svg>

            <div class="ticket-card">
                <div class="ticket-left">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Day 2</p>
                    <h3 class="font-montech-bold text-lg border-b-2 border-black pb-1 mb-2 uppercase">
                        Gap In A Minute / Talkshow
                    </h3>
                    <h3 class="font-montech-bold text-lg leading-tight uppercase">
                        Awarding Ceremony
                    </h3>
                </div>

                <div class="ticket-right">
                    <p class="font-bold text-sm uppercase">
                        May 30th 2026<br>12.00 WIB
                    </p>
                    <button class="btn-animate">Purchase Tickets</button>
                </div>
            </div>
        </div>

    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Fade in
    document.body.classList.add('fade-in');

    // Fade out
    const links = document.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('click', e => {
            const href = link.getAttribute('href');
            if (!href.startsWith('#') && link.target !== '_blank') {
                e.preventDefault();
                document.body.classList.add('fade-out');
                setTimeout(() => {
                    window.location.href = href;
                }, 800);
            }
        });
    });

    // tilt
    const wrappers = document.querySelectorAll('.js-tilt-card');

    wrappers.forEach(wrapper => {

        const card = wrapper.querySelector('.ticket-card');
        const svg = wrapper.querySelector('.ticket-border-svg');

        wrapper.addEventListener('mousemove', (e) => {

            const rect = wrapper.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const rotateX = ((y - rect.height/2) / (rect.height/2)) * -7;
            const rotateY = ((x - rect.width/2) / (rect.width/2)) * 7;

            const transform =
                `perspective(1000px)
                 rotateX(${rotateX}deg)
                 rotateY(${rotateY}deg)
                 scale3d(1.02,1.02,1.02)`;

            card.style.transform = transform;
            svg.style.transform = transform;
        });

        wrapper.addEventListener('mouseleave', () => {

            const reset =
                `perspective(1000px)
                 rotateX(0)
                 rotateY(0)
                 scale3d(1,1,1)`;

            card.style.transform = reset;
            svg.style.transform = reset;
        });

    });

});
</script>
@endpush