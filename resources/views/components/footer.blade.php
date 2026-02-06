<footer class="bg-black text-white font-sans">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            {{-- Kiri: Logo Utama & Kontak --}}
            <div class="flex flex-col">
                {{-- Lingkaran Merah (Placeholder Logo Utama) --}}
                <div class="mb-8">
                    <div class="w-32 h-32 bg-red-700 rounded-full"></div>
                    {{-- 
                    <img src="{{ asset('path/to/main-logo.png') }}" alt="Main Logo" class="w-32 h-auto"> 
                    --}}
                </div>

                {{-- Social Media & Email --}}
                <div class="space-y-3 text-lg font-medium mb-8">
                    {{-- Instagram --}}
                    <a href="https://instagram.com/piff.pcu" target="_blank"
                        class="flex items-center gap-3 hover:text-gray-300 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-instagram">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                        </svg>
                        <span>@piff.pcu</span>
                    </a>

                    {{-- Email --}}
                    <a href="mailto:piff.pcu@gmail.com" class="flex items-center gap-3 hover:text-gray-300 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-mail">
                            <rect width="20" height="16" x="2" y="4" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                        <span>piff.pcu@gmail.com</span>
                    </a>
                </div>

                {{-- Alamat --}}
                <div>
                    <h3 class="font-extrabold text-xl uppercase mb-2 tracking-wide">PETRA CHRISTIAN UNIVERSITY</h3>
                    <address class="not-italic text-gray-300 leading-relaxed">
                        Jl. Siwalankerto No.121-131,<br>
                        Siwalankerto, Kec. Wonocolo,<br>
                        Surabaya, Jawa Timur 60236
                    </address>
                </div>
            </div>

            {{-- Kanan: Partners & Sponsors --}}
            <div class="flex flex-col justify-between">

                {{-- Initiated By --}}
                <div class="mb-10">
                    <h4 class="font-bold text-lg uppercase mb-6 tracking-widest">INITIATED BY</h4>

                    {{-- Baris Logo Partner --}}
                    <div class="flex flex-wrap items-center gap-6">
                        {{-- Logo UK Petra --}}
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo-petra.png') }}" alt="Petra"
                                class="h-12 w-auto object-contain">
                        </div>

                        {{-- Logo BEM --}}
                        <img src="{{ asset('images/logo-bem.png') }}" alt="BEM"
                            class="h-12 w-auto object-contain bg-white rounded-full p-1">

                        {{-- Logo ASFS --}}
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo-asfs.png') }}" alt="ASFS"
                                class="h-10 w-auto object-contain">

                        </div>
                    </div>
                </div>

                {{-- Sponsored By --}}
                <div>
                    <h4 class="font-bold text-lg uppercase mb-4 tracking-widest">SPONSORED BY</h4>
                    <div
                        class="w-full h-48 bg-gray-700 border border-gray-600 flex items-center justify-center text-gray-500">
                        Space Sponsor
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>
