<footer class="bg-black text-white font-sans">
    <style>
        .footer-social-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 5px 0;
        }

        .footer-social-link span {
            position: relative;
        }

        .footer-social-link span::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #ff0000;
            transition: width 0.3s ease;
        }

        .footer-social-link:hover span::after {
            width: 100%;
        }
    </style>
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            {{-- Kiri: Logo Utama & Kontak --}}
            <div class="flex flex-col">
                {{-- Lingkaran Merah (Placeholder Logo Utama) --}}
                <div class="mb-8">
                    {{-- <div class="w-32 h-32 bg-red-700 rounded-full"></div> --}}

                    <img src="{{ asset('assets/logo/logo_piff.png') }}" alt="Main Logo" class="w-48 h-auto">

                </div>

                {{-- Social Media & Email --}}
                <div class="flex flex-col space-y-3 text-lg font-inter-bold mb-8">
                    {{-- Instagram --}}
                    <a href="https://instagram.com/piff.pcu" target="_blank"
                        class="footer-social-link hover:text-gray-300 transition">
                        <img src="{{ asset('assets/icons/icon_instagram.png') }}" alt="Instagram" class="w-8 h-8">
                        <span>@piff.pcu</span>
                    </a>

                    {{-- Email --}}
                    <a href="mailto:piff.pcu@gmail.com" class="footer-social-link hover:text-gray-300 transition">
                        <img src="{{ asset('assets/icons/icon_email.png') }}" alt="Email" class="w-8 h-8">
                        <span>piff.pcu@gmail.com</span>
                    </a>
                </div>

                {{-- Alamat --}}
                <div>
                    <h3 class="font-montech-bold text-xl uppercase mb-2 tracking-wide">PETRA CHRISTIAN UNIVERSITY</h3>
                    <address class="not-italic font-inter-regular text-gray-300 leading-relaxed">
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
                    <h4 class="font-montech-bold text-lg uppercase mb-6 tracking-widest">INITIATED BY</h4>

                    {{-- Baris Logo Partner --}}
                    <div class="flex flex-wrap items-center gap-6">
                        {{-- Logo UK Petra --}}
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('assets/logo/logo_pcu.png') }}" alt="Petra"
                                class="h-12 w-auto object-contain">
                        </div>

                        {{-- Logo BEM --}}
                        <img src="{{ asset('assets/logo/logo_bem.png') }}" alt="BEM"
                            class="h-12 w-auto object-contain">
                        <img src="{{ asset('assets/logo/logo_bem_branding.png') }}" alt="BEM"
                            class="h-12 w-auto object-contain">
                        <img src="{{ asset('assets/logo/logo_anc.png') }}" alt="BEM"
                            class="h-12 w-auto object-contain">

                        {{-- Logo ASFS --}}
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('assets/logo/logo_asfs.png') }}" alt="ASFS"
                                class="h-10 w-auto object-contain">

                        </div>
                    </div>
                </div>

                {{-- Sponsored By --}}
                <div>
                    <h4 class="font-montech-bold text-lg uppercase mb-4 tracking-widest">SPONSORED BY</h4>
                    <div
                        class="w-full h-48 bg-gray-700 border border-gray-600 flex items-center justify-center text-gray-500">
                        Space Sponsor
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>
