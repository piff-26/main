@push('styles')
    <style>
        .title-session{
            color: var(--black);
            /* Font size dipindahkan ke class HTML agar responsif */
        }
        .info-session{
            color: var(--black);
            text-align: center;
            /* Font size dipindahkan ke class HTML agar responsif */
        }
        .perspective-wrap {
            perspective: 1000px;
        }
    </style>
@endpush

<div class="relative w-screen left-1/2 -translate-x-1/2 h-auto overflow-hidden">
    <div class="absolute inset-0 w-full h-full z-0" style="background-color: #fec401"></div>
    
    <div class="relative z-10 container mx-auto py-10 md:py-20 px-4">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-6 perspective-wrap">
            <div>
                <h1 data-aos="fade-up-right" data-aos-duration="3000" class="title-session font-montech-regular ticket-animation-1 text-4xl sm:text-[50px]">SESSION</h1>
            </div>
            <div>
                <h1 data-aos="fade-up-left" data-aos-duration="3000" class="title-session font-montech-bold ticket-animation-1 text-4xl sm:text-[50px]">ACCESS</h1>
            </div>
        </div>

        <div class="mt-4">
            <h2 data-aos="zoom-in-up" data-aos-duration="3000" class="info-session font-inter-semibold ticket-animation-2 text-sm sm:text-base">Tickets for D-Day sessions will be available soon</h2>
            <h2 data-aos="zoom-in-up" data-aos-duration="3000" class="info-session font-inter-regular ticket-animation-2 text-sm sm:text-base">Check back for more details.</h2>
        </div>
    </div>
    
</div>

@push('scripts')

<!-- <script>
    gsap.registerPlugin(ScrollTrigger);
    
    gsap.from(".ticket-animation-1",{
        scrollTrigger:{
            trigger: ".ticket-animation-1",
            start: "top 80%",
            toggleActions: "play none none reverse"
        },
        rotationY: 90,
        opacity: 0,
        duration: 1.5,
        ease: "power2.out",
        stagger: 0.2
    });

    gsap.from(".ticket-animation-2",{
        scrollTrigger:{
            trigger: ".ticket-animation-2",
            start: "top 85%",
            toggleActions: "play none none reverse"
        },
        y: 50,
        opacity: 0,
        duration: 1.2,
        ease: "power2.out",
        stagger: 0.3
    });
</script> -->
@endpush