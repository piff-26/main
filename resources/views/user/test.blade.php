<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side-by-Side Scroll Effect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { overflow-x: hidden; }
        
        /* POSISI AWAL: 
           Kita dorong semua kartu ke bawah layar menggunakan translate-y.
           Tapi secara layout, mereka sudah punya 'kavling' masing-masing 
           karena kita pakai Flexbox, bukan Absolute.
        */
        .card-item {
            transform: translateY(150vh); 
            opacity: 0;
            will-change: transform, opacity;
        }
    </style>
</head>
<body class="bg-black text-white">

    <div class="h-screen flex flex-col items-center justify-center bg-gray-900 border-b border-gray-800">
        <h1 class="text-5xl font-bold mb-4">Scroll Down 👇</h1>
        <p class="text-gray-400">Efek muncul berjejer (Side-by-Side)</p>
    </div>

    <section id="side-section" class="relative w-full h-screen overflow-hidden flex flex-col justify-center items-center">
        
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2670&auto=format&fit=crop" 
                 class="w-full h-full object-cover opacity-20">
        </div>

        <div class="relative z-10 w-full max-w-7xl px-4 flex flex-col md:flex-row justify-center items-center gap-6 h-full">
            
            <div id="card-1" class="card-item w-full md:w-1/3 h-80 md:h-[500px] bg-gray-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
                <img src="https://images.unsplash.com/photo-1535930749574-1399327ce78f?q=80&w=1000&auto=format&fit=crop" class="w-full h-3/5 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-blue-400 mb-2">Langkah 1</h3>
                    <p class="text-gray-300">Gambar pertama muncul dan diam di sini.</p>
                </div>
            </div>

            <div id="card-2" class="card-item w-full md:w-1/3 h-80 md:h-[500px] bg-gray-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
                <img src="https://images.unsplash.com/photo-1517420704952-d9f39e95b43e?q=80&w=1000&auto=format&fit=crop" class="w-full h-3/5 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-red-400 mb-2">Langkah 2</h3>
                    <p class="text-gray-300">Gambar kedua naik di sebelahnya. Gambar 1 tetap diam.</p>
                </div>
            </div>

            <div id="card-3" class="card-item w-full md:w-1/3 h-80 md:h-[500px] bg-gray-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
                <img src="https://images.unsplash.com/photo-1550745165-9014eb94aca5?q=80&w=1000&auto=format&fit=crop" class="w-full h-3/5 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-green-400 mb-2">Langkah 3</h3>
                    <p class="text-gray-300">Gambar ketiga melengkapi barisan.</p>
                </div>
            </div>

        </div>

    </section>

    <div class="h-screen flex items-center justify-center bg-gray-900 border-t border-gray-800">
        <h1 class="text-4xl font-bold">End of Section</h1>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        let tl = gsap.timeline({
            scrollTrigger: {
                trigger: "#side-section",
                start: "top top",
                end: "+=3000", // Panjang scroll
                pin: true,     // Kunci background & container
                scrub: 1,      // Smooth scroll
            }
        });

        // LOGIKA ANIMASI:
        // Kita hanya mengembalikan posisi Y ke 0 (posisi asli flexbox-nya)
        
        // 1. Gambar 1 Naik
        tl.to("#card-1", {
            y: 0,
            opacity: 1,
            duration: 1,
            ease: "power3.out"
        });

        // 2. Gambar 2 Naik (Gambar 1 diam karena tidak kita apa-apakan di step ini)
        tl.to("#card-2", {
            y: 0,
            opacity: 1,
            duration: 1,
            ease: "power3.out"
        }); // Default GSAP adalah sequence, jadi ini jalan SETELAH card 1 selesai

        // 3. Gambar 3 Naik (Gambar 1 & 2 diam)
        tl.to("#card-3", {
            y: 0,
            opacity: 1,
            duration: 1,
            ease: "power3.out"
        });

        // 4. (Opsional) Semua naik barengan untuk keluar layar
        tl.to(".card-item", {
            y: "-150vh",
            opacity: 0,
            duration: 1.5,
            stagger: 0.1
        }, "+=0.5");

    </script>
</body>
</html>