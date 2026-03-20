<div class="font-organetto relative w-full min-h-screen flex flex-col items-center py-[2rem] px-4">

    <div
        class="w-full max-w-6xl glass-card rounded-[2rem] p-6 md:p-8 relative bg-slate-900/40 backdrop-blur-lg border border-slate-700/50 shadow-2xl">

        <div wire:loading.flex wire:target="processToPayment, applyVoucher, reTriggerMidtrans"
            class="absolute inset-0 z-50 flex-col items-center justify-center bg-black/80 backdrop-blur-sm rounded-[2rem]">
            <div class="w-16 h-16 rounded-full border-4 border-[#ff5b1d]/30 border-t-[#ff5b1d] animate-spin"></div>
            <span class="text-white text-lg font-bold tracking-widest mt-4 animate-pulse">MEMPROSES DATA...</span>
        </div>

        <div class="text-center mb-8 pt-4">
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-wider mb-2 uppercase">
                @if ($currentStep == 1)
                    Lengkapi Data Diri
                @elseif($currentStep == 2)
                    Selesaikan Pembayaran
                @else
                    Transaksi Berhasil
                @endif
            </h1>

            {{-- Auto Save Notification (Hanya muncul di Step 1) --}}
            @if ($currentStep == 1)
                <div class="flex justify-center mt-4 h-8">
                    <div x-data="{ show: false, time: '' }" x-show="show" x-transition.opacity.duration.300ms
                        x-on:draft-saved.window="show = true; time = $event.detail[0]?.time ?? $event.detail.time; setTimeout(() => show = false, 2500);"
                        style="display: none;"
                        class="px-4 py-1.5 bg-green-500/20 border border-green-400/30 rounded-full backdrop-blur-sm flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                        <span class="text-green-300 text-xs font-bold uppercase tracking-wider">
                            Draft Auto-Saved <span x-text="time"></span>
                        </span>
                    </div>
                </div>
            @endif

            {{-- Progress Steps --}}
            <div class="flex items-center justify-center space-x-4 mt-6">
                @foreach ([1 => 'Biodata', 2 => 'Pembayaran', 3 => 'E-Ticket'] as $step => $label)
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                            {{ $currentStep >= $step ? 'bg-[#ff5b1d] text-white shadow-[0_0_15px_rgba(255,91,29,0.5)]' : 'bg-slate-800 text-slate-500' }}">
                            {{ $step }}
                        </div>
                        <span
                            class="text-[10px] mt-2 tracking-widest uppercase {{ $currentStep >= $step ? 'text-[#ff5b1d]' : 'text-slate-500' }}">{{ $label }}</span>
                    </div>
                    @if ($step < 3)
                        <div
                            class="w-16 h-1 rounded-full {{ $currentStep > $step ? 'bg-[#ff5b1d]' : 'bg-slate-800' }} mb-5">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        @if ($currentStep == 1)
            <div class="flex flex-col md:flex-row gap-8">
                <div class="w-full md:w-3/5 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">NAMA LENGKAP
                                *</label>
                            <input type="text" wire:model.live.debounce.600ms="buyer_name"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] focus:ring-1 focus:ring-[#ff5b1d] transition"
                                placeholder="Sesuai KTP/KTM">
                            @error('buyer_name')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">NOMOR TELEPON
                                *</label>
                            <input type="text" inputmode="numeric" pattern="\d*" required maxlength="15"
                                wire:model.live.debounce.600ms="buyer_phone"
                                oninput="this.value = this.value.replace(/\D/g, '')"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition"
                                placeholder="08xxxxxxxxxx">
                            @error('buyer_phone')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">KOTA ASAL
                                *</label>
                            <input type="text" wire:model.live.debounce.600ms="city"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition"
                                placeholder="Contoh: Surabaya">
                            @error('city')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">SUMBER INFORMASI
                                *</label>
                            <select wire:model.live="source_info"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition">
                                <option value="" class="bg-slate-800">Pilih Sumber Info</option>
                                @foreach ($sourceInfoOptions ?? [] as $option)
                                    <option value="{{ $option }}" class="bg-slate-800">{{ $option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('source_info')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-700/50">
                        <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">KODE VOUCHER
                            (OPSIONAL)</label>
                        <div class="flex gap-3">
                            <input type="text" wire:model="voucher_code"
                                class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white uppercase focus:border-[#ff5b1d] transition"
                                placeholder="MASUKKAN KODE">
                            <button wire:click.prevent="applyVoucher"
                                class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-3 rounded-xl font-bold tracking-wider transition">TERAPKAN</button>
                        </div>
                    </div>

                    {{-- TNC --}}
                    <div class="mt-6 flex items-center gap-3">
                        <input type="checkbox" id="agree_tnc" wire:model="agree_tnc" class="w-4 h-4 accent-[#ff5b1d]"
                            {{ !$tncRead ? 'disabled' : '' }}>
                        <label for="agree_tnc" class="text-sm text-slate-300">
                            Saya menyetujui
                            <button type="button" x-data x-on:click="$dispatch('open-tnc')"
                                class="text-[#ff5b1d] underline hover:text-[#ff8c3a] transition">Syarat dan
                                Ketentuan</button>
                        </label>
                    </div>
                    @if ($tncError)
                        <span class="text-red-400 text-xs mt-1 block">{{ $tncError }}</span>
                    @endif

                    <div class="mt-6">
                        <button wire:click="cancelTransaction"
                            x-on:click="if(!confirm('Batalkan transaksi ini? Kuota tiket akan dikembalikan.')) $event.preventDefault()"
                            class="inline-flex items-center gap-2 text-red-400 hover:text-red-300 text-sm underline transition">
                            <i class="fas fa-times-circle"></i> Batalkan Transaksi
                        </button>
                    </div>
                </div>

                <div class="w-full md:w-2/5">
                    <div class="bg-slate-800/40 rounded-2xl p-6 border border-slate-700/50 sticky top-6">
                        <h3 class="text-white font-bold tracking-wider mb-4 border-b border-slate-600 pb-3">RINGKASAN
                            PESANAN</h3>

                        <div class="space-y-4 mb-6">
                            @foreach ($transaction->transactionItems as $item)
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-white font-semibold">{{ $item->ticketCategory->name }}</p>
                                        <p class="text-slate-400 text-sm">{{ $item->quantity }}x Tiket</p>
                                    </div>
                                    <p class="text-white">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            @endforeach

                            @if ($discount_amount > 0)
                                <div
                                    class="flex justify-between items-center text-green-400 pt-2 border-t border-slate-700/50">
                                    <p class="font-semibold">Diskon Voucher</p>
                                    <p>- Rp {{ number_format($discount_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center mb-6 pt-4 border-t border-slate-600">
                            <p class="text-slate-300 font-bold">TOTAL BAYAR</p>
                            <p class="text-2xl font-extrabold text-[#ff5b1d]">Rp
                                {{ number_format($grand_total, 0, ',', '.') }}</p>
                        </div>

                        <button wire:click="processToPayment"
                            class="w-full bg-[#ff5b1d] hover:bg-[#e04a10] text-white py-4 rounded-xl font-bold tracking-widest transition-all shadow-[0_0_20px_rgba(255,91,29,0.3)] hover:shadow-[0_0_30px_rgba(255,91,29,0.5)] transform hover:-translate-y-1">
                            LANJUT PEMBAYARAN
                        </button>
                    </div>
                </div>
            </div>
        @elseif ($currentStep == 2)
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Menunggu Pembayaran</h2>
                <p class="text-slate-400 mb-8 max-w-md">Selesaikan pembayaran Anda melalui *popup* yang muncul. Jika
                    *popup* tidak muncul atau ter-close, klik tombol di bawah ini.</p>

                <div class="text-3xl font-extrabold text-[#ff5b1d] mb-8">Rp
                    {{ number_format($grand_total, 0, ',', '.') }}</div>

                <div class="flex gap-4">
                    <button wire:click="$set('currentStep', 1)"
                        class="px-6 py-3 border border-slate-600 text-slate-300 rounded-xl hover:bg-slate-800 transition">Kembali</button>
                    <button wire:click="reTriggerMidtrans"
                        class="px-8 py-3 bg-[#ff5b1d] hover:bg-[#e04a10] text-white rounded-xl font-bold shadow-lg transition">Bayar
                        Sekarang</button>

                    {{-- Tombol simulasi untuk tes pindah ke step 3 (Hapus saat Production) --}}
                    <button wire:click="paymentSuccess"
                        class="px-4 py-3 bg-green-600 text-white rounded-xl text-xs">Simulasi Sukses (Dev)</button>
                </div>
            </div>
        @elseif ($currentStep == 3)
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div
                    class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mb-6 border-4 border-green-500">
                    <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Pembayaran Berhasil!</h2>
                <p class="text-slate-400 mb-8 max-w-md">Terima kasih, pembayaran untuk tiket Anda telah kami terima.
                    Invoice: <span class="text-white font-mono">{{ $transaction->invoice_code }}</span></p>

                <div class="bg-slate-800/50 border border-slate-700 p-6 rounded-2xl w-full max-w-md mb-8">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400">Nama Lengkap</span>
                        <span class="text-white font-semibold">{{ $transaction->buyer_name }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400">Total Tiket</span>
                        <span class="text-white font-semibold">{{ $transaction->transactionItems->sum('quantity') }}
                            Tiket</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-slate-700 mt-2">
                        <span class="text-slate-300 font-bold">Total Dibayar</span>
                        <span class="text-green-400 font-bold">Rp
                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('ticket.download', $transaction->invoice_code) }}" target="_blank"
                    class="px-8 py-4 bg-gradient-to-r from-[#ff5b1d] to-[#ff8c3a] text-white rounded-xl font-bold shadow-[0_0_20px_rgba(255,91,29,0.4)] hover:shadow-[0_0_30px_rgba(255,91,29,0.6)] transition-all transform hover:-translate-y-1 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    DOWNLOAD E-TICKET (PDF)
                </a>
            </div>
        @endif

    </div>

    {{-- TNC MODAL --}}
    <div x-data="{ open: false, scrolled: false }"
        x-on:open-tnc.window="
            open = true; scrolled = false;
            document.body.style.overflow = 'hidden';
            document.getElementById('navigation-bar')?.classList.add('nav-hidden');
        "
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);">

        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg flex flex-col"
            style="max-height: 80vh;">
            <div class="px-6 py-4 border-b border-slate-700">
                <h3 class="text-white font-bold text-lg tracking-wider">SYARAT DAN KETENTUAN</h3>
            </div>

            <div class="overflow-y-auto flex-1 px-6 py-4 text-slate-300 text-sm space-y-3" x-ref="tncContent"
                x-on:scroll="if($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10) scrolled = true">

                <p class="font-bold text-white">A. KETENTUAN UMUM & VALIDITAS TIKET</p>

                <p><span class="font-semibold">1.</span> Tiket hanya berlaku untuk tanggal, waktu, dan acara yang
                    tertera pada tiket.</p>
                <p><span class="font-semibold">2.</span> Satu (1) tiket berlaku untuk satu (1) orang.</p>
                <p><span class="font-semibold">3.</span> Tiket tidak dapat dipindahtangankan tanpa persetujuan resmi
                    dari panitia.</p>
                <p><span class="font-semibold">4.</span> Tiket yang telah dibeli dianggap sah apabila diperoleh melalui
                    kanal penjualan resmi yang ditunjuk oleh panitia.</p>
                <p><span class="font-semibold">5.</span> Panitia berhak untuk:</p>
                <p class="ml-4"><span class="font-semibold">a.</span> Menolak tiket yang rusak, tidak terbaca,
                    terduplikasi, atau diperoleh secara tidak sah.</p>
                <p class="ml-4"><span class="font-semibold">b.</span> Memproses atau mengajukan hukum, baik perdata
                    atau kriminal kepada pengunjung yang mendapatkan tiket dengan ilegal, termasuk memalsukan dan
                    menggandakan tiket yang sah atau mendapatkan tiket dengan cara yang tidak sesuai prosedur.</p>

                <p class="font-bold text-white mt-4">B. KEBIJAKAN PEMBELIAN & PENGEMBALIAN DANA</p>

                <p><span class="font-semibold">1.</span> Seluruh pembelian tiket bersifat final dan tidak dapat
                    dibatalkan.</p>
                <p><span class="font-semibold">2.</span> Tiket yang telah dibeli tidak dapat dikembalikan dan tidak
                    dapat ditukar (<i>non-refundable</i> & <i>non-exchangeable</i>), kecuali apabila acara dibatalkan
                    secara resmi oleh panitia.</p>
                <p><span class="font-semibold">3.</span> Panitia tidak bertanggung jawab atas kegagalan proses
                    pembelian tiket yang disebabkan oleh kesalahan pengisian data oleh pembeli maupun gangguan jaringan
                    dan perangkat yang berada di luar kendali panitia.</p>
                <p><span class="font-semibold">4.</span> Apabila acara dibatalkan oleh panitia, mekanisme pengembalian
                    dana akan diinformasikan melalui kanal komunikasi resmi PIFF 2026.</p>
                <p><span class="font-semibold">5.</span> Apabila acara mengalami perubahan jadwal (<i>reschedule</i>),
                    tiket tetap berlaku untuk tanggal pengganti.</p>

                <p class="font-bold text-white mt-4">C. AKSES & REGISTRASI MASUK</p>

                <p><span class="font-semibold">1.</span> Pengunjung wajib menunjukkan tiket dalam bentuk digital
                    (<i>e-ticket</i>) saat proses registrasi.</p>
                <p><span class="font-semibold">2.</span> Tiket digital (<i>e-ticket</i>) akan ditukarkan dengan tiket
                    gelang yang wajib digunakan selama berada di area acara.</p>
                <p><span class="font-semibold">3.</span> Pengunjung yang tidak dapat menunjukkan tiket gelang tidak
                    diperkenankan memasuki area acara.</p>
                <p><span class="font-semibold">4.</span> Panitia berhak melakukan pemeriksaan ulang terhadap tiket dan
                    identitas apabila diperlukan.</p>

                <p class="font-bold text-white mt-4">D. KETENTUAN SELAMA ACARA</p>

                <p><span class="font-semibold">1.</span> Pengunjung wajib menjaga ketertiban dan mematuhi seluruh
                    peraturan yang berlaku selama acara berlangsung.</p>
                <p><span class="font-semibold">2.</span> Dilarang membawa barang terlarang sesuai ketentuan panitia
                    (misalnya senjata tajam, narkotika, minuman beralkohol, dan barang berbahaya lainnya).</p>
                <p><span class="font-semibold">3.</span> Panitia berhak mengeluarkan pengunjung dari area acara apabila
                    melanggar peraturan tanpa kewajiban pengembalian dana.</p>
                <p><span class="font-semibold">4.</span> Tamu bertanggung jawab sepenuhnya atas keamanan semua
                    barang-barang pribadi. Kehilangan barang pribadi bukan tanggung jawab panitia.</p>
                <p><span class="font-semibold">5.</span> Dengan membeli tiket, pengunjung memberikan persetujuan untuk
                    didokumentasikan (foto/video) dan digunakan untuk kepentingan publikasi acara.</p>

                <p class="font-bold text-white mt-4">E. KEJADIAN KAHAR (FORCE MAJEURE)</p>

                <p><span class="font-semibold">1.</span> Dalam hal terjadi kejadian kahar (<i>Force Majeure</i>),
                    Panitia berhak untuk membatalkan, menunda, mengubah jadwal, memindahkan lokasi, atau menyesuaikan
                    format acara tanpa kewajiban memberikan kompensasi tambahan di luar kebijakan yang ditentukan
                    Panitia.</p>
                <p><span class="font-semibold">2.</span> Panitia tidak bertanggung jawab atas kerugian tidak langsung
                    yang mungkin timbul akibat perubahan atau pembatalan acara yang disebabkan oleh <i>Force
                        Majeure</i>.</p>
                <p><span class="font-semibold">3.</span> Informasi resmi terkait perubahan akibat <i>Force Majeure</i>
                    akan diumumkan melalui kanal komunikasi resmi PIFF 2026.</p>

                <p class="text-slate-500 text-xs mt-4">Scroll ke bawah untuk menyetujui.</p>

            </div>

            <div class="px-6 py-4 border-t border-slate-700">
                <button x-bind:disabled="!scrolled"
                    x-bind:class="scrolled ? 'bg-[#ff5b1d] hover:bg-[#e04a10] cursor-pointer' :
                        'bg-slate-700 cursor-not-allowed opacity-50'"
                    x-on:click="
                        open = false;
                        $wire.set('tncRead', true); $wire.set('agree_tnc', true);
                        document.body.style.overflow = '';
                        document.getElementById('navigation-bar')?.classList.remove('nav-hidden');
                    "
                    class="w-full text-white font-bold py-3 rounded-xl transition">
                    Saya Setuju
                </button>
            </div>
        </div>
    </div>

</div>

@script
    <script>
        $wire.on('trigger-midtrans', (event) => {
            let token = event?.snapToken ?? event?.[0]?.snapToken ?? event?.[0];

            if (!token) {
                console.error('Snap token tidak ditemukan', event);
                return;
            }

            window.snap.pay(token, {
                onSuccess: function(result) {
                    $wire.paymentSuccess();
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda!");
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    console.log('Popup ditutup tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>
@endscript
