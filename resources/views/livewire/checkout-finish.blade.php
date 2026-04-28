<div class="font-organetto relative w-full min-h-screen flex flex-col items-center py-[2rem] px-4">
    @php use App\Enums\PaymentAccountEnum; @endphp

    <div
        class="w-full max-w-6xl glass-card rounded-[2rem] p-6 md:p-8 relative bg-slate-900/40 backdrop-blur-lg border border-slate-700/50 shadow-2xl">

        <div wire:loading.flex wire:target="processToPayment, applyVoucher, removeVoucher, nextStep"
            class="absolute inset-0 z-50 flex-col items-center justify-center bg-black/80 backdrop-blur-sm rounded-[2rem]">
            <div class="w-16 h-16 rounded-full border-4 border-[#ff5b1d]/30 border-t-[#ff5b1d] animate-spin"></div>
            <span class="text-white text-lg font-bold tracking-widest mt-4 animate-pulse">PROCESSING...</span>
        </div>

        <div class="text-center mb-8 pt-4">
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-wider mb-2 uppercase">
                @if ($currentStep == 1)
                    Complete Your Profile
                @elseif($currentStep == 2)
                    Upload Payment proof
                @else
                    Wait for Verification
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
                @foreach ([1 => 'Data', 2 => 'Payment', 3 => 'Verification'] as $step => $label)
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

        {{-- STEP 1: BIODATA --}}
        @if ($currentStep == 1)
            <div class="flex flex-col md:flex-row gap-8">
                <div class="w-full md:w-3/5 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">FULL NAME
                                *</label>
                            <input type="text" wire:model.live.debounce.600ms="buyer_name"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] focus:ring-1 focus:ring-[#ff5b1d] transition"
                                placeholder="Full Name (as per ID card / passport)">
                            @error('buyer_name')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">PHONE NUMBER
                                *</label>
                            <div class="flex flex-col gap-2" x-data="{
                                open: false,
                                search: '',
                                selected: { flag: '🇮🇩', dial_code: '+62', name: 'Indonesia' },
                                countries: @js($countries),
                                get filtered() {
                                    if (!this.search) return this.countries;
                                    const q = this.search.toLowerCase();
                                    return this.countries.filter(c => c.name.toLowerCase().includes(q) || c.dial_code.includes(q));
                                },
                                pick(c) {
                                    this.selected = c;
                                    $wire.set('phone_code', c.dial_code);
                                    this.open = false;
                                    this.search = '';
                                }
                            }" x-init="const init = countries.find(c => c.dial_code === $wire.get('phone_code'));
                            if (init) selected = init;">
                                {{-- Trigger --}}
                                <div class="relative shrink-0">
                                    <button type="button" x-on:click="open = !open"
                                        class="flex items-center gap-2 bg-slate-800/50 border border-slate-600 rounded-xl px-3 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition w-36 h-full">
                                        <img :src="`https://flagcdn.com/24x18/${countries.find(c=>c.dial_code===selected.dial_code)?.code?.toLowerCase()}.png`"
                                            class="w-6 h-4 object-cover rounded-sm shrink-0"
                                            x-on:error="$el.style.display='none'">
                                        <span class="text-sm font-mono" x-text="selected.dial_code"></span>
                                        <i class="fas fa-chevron-down text-xs text-slate-400 ml-auto"></i>
                                    </button>

                                    {{-- Dropdown --}}
                                    <div x-show="open" x-on:click.outside="open = false" x-cloak
                                        class="absolute z-50 mt-1 w-72 bg-slate-900 border border-slate-700 rounded-xl shadow-2xl overflow-hidden">
                                        <div class="p-2 border-b border-slate-700">
                                            <input type="text" x-model="search" placeholder="Search country..."
                                                class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-[#ff5b1d]">
                                        </div>
                                        <ul class="overflow-y-auto max-h-52">
                                            <template x-for="c in filtered" :key="c.code">
                                                <li x-on:click="pick(c)"
                                                    class="flex items-center gap-3 px-3 py-2 hover:bg-slate-700 cursor-pointer transition">
                                                    <img :src="`https://flagcdn.com/24x18/${c.code.toLowerCase()}.png`"
                                                        class="w-6 h-4 object-cover rounded-sm shrink-0">
                                                    <span class="text-white text-sm flex-1" x-text="c.name"></span>
                                                    <span class="text-slate-400 text-xs font-mono"
                                                        x-text="c.dial_code"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <input type="text" inputmode="numeric" pattern="\d*" required maxlength="15"
                                    wire:model.live.debounce.600ms="phone_number"
                                    oninput="this.value = this.value.replace(/\D/g, '')"
                                    class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition"
                                    placeholder="8123456789">
                            </div>
                            @error('phone_number')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">CITY OF ORIGIN
                                *</label>
                            <input type="text" wire:model.live.debounce.600ms="city"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition"
                                placeholder="Example: New York">
                            @error('city')
                                <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">INFORMATION SOURCE
                                *</label>
                            <select wire:model.live="source_info"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] transition">
                                <option value="" class="bg-slate-800">Choose</option>
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
                        <h4 class="text-xs font-bold text-slate-400 tracking-wider mb-4">TICKET OWNERS *</h4>
                        <div class="space-y-4">
                            @foreach ($transaction->transactionItems as $item)
                                @for ($i = 0; $i < $item->quantity; $i++)
                                    <div>
                                        <label class="block text-xs text-slate-500 mb-1">
                                            {{ $item->ticketCategory->name }}
                                            @if ($item->quantity > 1)
                                                #{{ $i + 1 }}
                                            @endif
                                        </label>
                                        <input type="text"
                                            wire:model.live.debounce.400ms="holderNames.{{ $item->id }}.{{ $i }}"
                                            class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#ff5b1d] focus:ring-1 focus:ring-[#ff5b1d] transition"
                                            required placeholder="Ticket owner's name as per ID card / passport">
                                        @error("holderNames.{$item->id}.{$i}")
                                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endfor
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-700/50">
                        <label class="block text-xs font-bold text-slate-400 tracking-wider mb-2">Voucher Code
                            (Optional)</label>
                        <div class="flex flex-col gap-2">
                            <input type="text" wire:model="voucher_code"
                                class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 text-white uppercase focus:border-[#ff5b1d] transition"
                                placeholder="ENTER CODE">
                            <button wire:click.prevent="applyVoucher"
                                class="w-full bg-slate-700 hover:bg-slate-600 text-white px-6 py-3 rounded-xl font-bold tracking-wider transition">APPLY</button>
                        </div>
                        @error('voucher_code')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                        @if (session('voucher_success'))
                            <span class="text-green-400 text-xs mt-1 block">{{ session('voucher_success') }}</span>
                        @endif
                    </div>

                    {{-- TNC --}}
                    <div class="mt-6 flex items-center gap-3">
                        <input type="checkbox" id="agree_tnc" wire:model="agree_tnc"
                            class="w-4 h-4 accent-[#ff5b1d] cursor-pointer">
                        <label for="agree_tnc" class="text-sm text-slate-300 cursor-pointer">
                            I Agree
                            <button type="button" x-data x-on:click="$dispatch('open-tnc')"
                                class="text-[#ff5b1d] underline hover:text-[#ff8c3a] transition">Terms and
                                Conditions</button>
                        </label>
                    </div>
                    @if ($tncError)
                        <span class="text-red-400 text-xs mt-1 block">{{ $tncError }}</span>
                    @endif

                    <div class="mt-6">
                        <button type="button"
                            x-on:click="Swal.fire({
                                title: 'Cancel Transaction?',
                                text: 'This action cannot be undone.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#475569',
                                confirmButtonText: 'Yes, cancel it',
                                cancelButtonText: 'Go back',
                                background: '#0f172a',
                                color: '#f1f5f9'
                            }).then(result => { if (result.isConfirmed) $wire.cancelTransaction() })"
                            class="inline-flex items-center gap-2 text-red-400 hover:text-red-300 text-sm underline transition">
                            <i class="fas fa-times-circle"></i> Cancel Transaction
                        </button>
                    </div>
                </div>

                <div class="w-full md:w-2/5">
                    <div class="bg-slate-800/40 rounded-2xl p-6 border border-slate-700/50 sticky top-6">
                        <h3 class="text-white font-bold tracking-wider mb-4 border-b border-slate-600 pb-3">ORDER
                            SUMMARY</h3>

                        <div class="space-y-4 mb-6">
                            @foreach ($transaction->transactionItems as $item)
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-white font-semibold">{{ $item->ticketCategory->name }}</p>
                                        <p class="text-slate-400 text-sm">{{ $item->quantity }}x Tiket</p>
                                    </div>
                                    <p class="text-white">IDR
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            @endforeach

                            @if ($discount_amount > 0)
                                <div
                                    class="flex justify-between items-center text-green-400 pt-2 border-t border-slate-700/50">
                                    <p class="font-semibold">Voucher Discount</p>
                                    <p>- IDR {{ number_format($discount_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col justify-between items-center mb-6 pt-4 border-t border-slate-600"
                            x-data="{
                                usd: null,
                                idr: {{ (int) $grand_total }},
                                async fetchRate() {
                                    if (window._usdRate) { this.usd = (this.idr * window._usdRate).toFixed(2); return; }
                                    try {
                                        const r = await fetch('https://api.frankfurter.dev/v1/latest?base=IDR&symbols=USD');
                                        const d = await r.json();
                                        window._usdRate = d.rates.USD;
                                        this.usd = (this.idr * window._usdRate).toFixed(2);
                                    } catch (e) { this.usd = null; }
                                }
                            }" x-init="fetchRate()"
                            x-effect="idr = {{ (int) $grand_total }}; idrFormatted = '{{ number_format($grand_total, 0, ',', '.') }}'; if(window._usdRate) usd = (idr * window._usdRate).toFixed(2)">
                            <p class="text-slate-300 font-bold">TOTAL</p>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-2xl font-extrabold text-[#ff5b1d]">IDR
                                    {{ number_format($grand_total, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-1" x-show="idr > 0">
                                <p class="text-2xl text-slate-300">≈ $<span x-text="usd ?? '...'"></span> USD
                                    <span class="text-slate-600 text-xs">· estimated</span>
                                </p>
                            </div>
                        </div>

                        <button wire:click="processToPayment"
                            class="w-full bg-[#ff5b1d] hover:bg-[#e04a10] text-white py-4 rounded-xl font-bold tracking-widest transition-all shadow-[0_0_20px_rgba(255,91,29,0.3)] hover:shadow-[0_0_30px_rgba(255,91,29,0.5)] transform hover:-translate-y-1">
                            CONTINUE TO PAYMENT
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 2: UPLOAD BUKTI BAYAR --}}
        @elseif ($currentStep == 2)
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Info Rekening --}}
                <div class="w-full md:w-1/2">
                    <div class="bg-slate-800/40 rounded-2xl p-6 border border-slate-700/50">
                        <h3
                            class="text-white font-bold tracking-wider mb-4 border-b border-slate-600 pb-3 flex items-center gap-2">
                            <i class="fas fa-university text-[#ff5b1d]"></i> PAYMENT INFORMATION
                        </h3>
                        <p class="text-slate-400 text-sm mb-4">Transfer to one of the following accounts:</p>

                        <div class="space-y-3">
                            @foreach (PaymentAccountEnum::cases() as $account)
                                <div
                                    class="bg-slate-900/60 rounded-xl p-4 border border-slate-700 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">
                                            {{ $account->label() }}</p>
                                        <p class="text-white font-bold text-lg tracking-widest"
                                            data-norek="{{ $account->accountNumber() }}">
                                            {{ $account->accountNumber() }}</p>
                                        <p class="text-slate-400 text-sm">a.n. {{ $account->accountName() }}</p>
                                    </div>
                                    <button type="button"
                                        onclick="copyText('{{ $account->accountNumber() }}', this)"
                                        class="text-slate-400 hover:text-white transition p-2 rounded-lg hover:bg-slate-700">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
                            <p class="text-yellow-400 text-sm font-semibold mb-1"><i
                                    class="fas fa-exclamation-triangle mr-2"></i>Important!</p>
                            <p class="text-yellow-300/80 text-xs mb-3">Transfer the exact total amount due. Use the
                                following transfer reference:</p>
                            <div class="flex items-center gap-2 bg-black/30 rounded-lg px-3 py-2">
                                <span class="text-white font-mono text-sm flex-1"
                                    id="transferNote">piff_{{ strtolower(substr($transaction->invoice_code, 4)) }}</span>
                                <button type="button"
                                    onclick="copyText('piff_{{ strtolower(substr($transaction->invoice_code, 4)) }}', this)"
                                    class="text-yellow-400 hover:text-yellow-300 text-xs font-semibold transition flex items-center gap-1">
                                    <i class="fas fa-copy"></i> Salin
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-700" x-data="{
                            usd: null,
                            idr: {{ (int) $grand_total }},
                            idrFormatted: '{{ number_format($grand_total, 0, ',', '.') }}',
                            async fetchRate() {
                                if (window._usdRate) { this.usd = (this.idr * window._usdRate).toFixed(2); return; }
                                try {
                                    const r = await fetch('https://api.frankfurter.dev/v1/latest?base=IDR&symbols=USD');
                                    const d = await r.json();
                                    window._usdRate = d.rates.USD;
                                    this.usd = (this.idr * window._usdRate).toFixed(2);
                                } catch (e) { this.usd = null; }
                            }
                        }"
                            x-init="fetchRate()">
                            <div class="flex justify-between items-start">
                                <span class="text-slate-400">Total</span>
                                <div class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <span class="text-2xl font-extrabold text-[#ff5b1d]">IDR
                                            <span x-text="idrFormatted"></span></span>
                                        <button type="button" x-on:click="copyText(String(idr), $el)"
                                            class="text-slate-500 hover:text-slate-300 transition p-1 rounded-lg hover:bg-slate-700">
                                            <i class="fas fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-end gap-2 mt-0.5" x-show="idr > 0">
                                        <span class="text-2xl text-slate-300">≈ $<span x-text="usd ?? '...'"></span>
                                            USD
                                            <span class="text-slate-600 text-xs">· estimated</span>
                                        </span>
                                        <button type="button" x-show="usd" x-on:click="copyText(usd, $el)"
                                            class="text-slate-500 hover:text-slate-300 transition p-1 rounded-lg hover:bg-slate-700">
                                            <i class="fas fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-slate-400 text-sm">Invoice</span>
                                <span class="text-white font-mono text-sm">{{ $transaction->invoice_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Upload --}}
                <div class="w-full md:w-1/2">
                    <div class="bg-slate-800/40 rounded-2xl p-6 border border-slate-700/50">
                        <h3
                            class="text-white font-bold tracking-wider mb-4 border-b border-slate-600 pb-3 flex items-center gap-2">
                            <i class="fas fa-upload text-[#ff5b1d]"></i> UPLOAD PAYMENT PROOF
                        </h3>

                        <div x-data="{
                            preview: null,
                            dragging: false,
                            uploading: false,
                            handleFile(file) {
                                if (!file || !file.type.startsWith('image/')) return;
                                this.uploading = true;
                                const reader = new FileReader();
                                reader.onload = e => this.preview = e.target.result;
                                reader.readAsDataURL(file);
                                $wire.upload('payment_proof', file,
                                    () => { this.uploading = false; },
                                    () => { this.uploading = false; },
                                    (event) => {}
                                );
                            }
                        }" class="space-y-4" x-on:dragover.prevent="dragging = true"
                            x-on:dragleave.prevent="dragging = false"
                            x-on:drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])">

                            <div class="border-2 border-dashed rounded-xl p-6 text-center transition cursor-pointer"
                                :class="dragging ? 'border-[#ff5b1d] bg-[#ff5b1d]/10' :
                                    'border-slate-600 hover:border-[#ff5b1d]/50'"
                                x-on:click="$refs.fileInput.click()">
                                <template x-if="uploading">
                                    <div>
                                        <i class="fas fa-spinner fa-spin text-3xl text-[#ff5b1d] mb-3"></i>
                                        <p class="text-slate-400 text-sm">Mengupload...</p>
                                    </div>
                                </template>
                                <template x-if="!uploading && !preview">
                                    <div>
                                        <i class="fas fa-cloud-upload-alt text-4xl text-slate-500 mb-3"></i>
                                        <p class="text-slate-400 text-sm">Click or drag & drop</p>
                                        <p class="text-slate-600 text-xs mt-1">JPG, PNG, max 2MB</p>
                                    </div>
                                </template>
                                <template x-if="!uploading && preview">
                                    <div>
                                        <img :src="preview"
                                            class="max-h-48 mx-auto rounded-lg object-contain mb-2">
                                        <p class="text-slate-500 text-xs">Click to change image</p>
                                    </div>
                                </template>
                            </div>

                            <input type="file" x-ref="fileInput" accept="image/*" class="hidden"
                                x-on:change="handleFile($event.target.files[0])">

                            @error('payment_proof')
                                <span class="text-red-400 text-xs block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 mt-6">
                            <button wire:click="previousStep"
                                class="flex-1 px-4 py-3 border border-slate-600 text-slate-300 rounded-xl hover:bg-slate-800 transition font-semibold">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </button>
                            <button wire:click="uploadPaymentProof"
                                class="flex-1 bg-[#ff5b1d] hover:bg-[#e04a10] text-white py-3 rounded-xl font-bold tracking-wider transition shadow-[0_0_20px_rgba(255,91,29,0.3)]">
                                <i class="fas fa-paper-plane mr-2"></i>UPLOAD
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3: MENUNGGU VERIFIKASI --}}
        @elseif ($currentStep == 3)
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div
                    class="w-24 h-24 bg-yellow-500/20 rounded-full flex items-center justify-center mb-6 border-4 border-yellow-500">
                    <i class="fas fa-clock text-yellow-400 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Payment Proof Submitted!</h2>
                <p class="text-slate-400 mb-2 max-w-md">Thank you! Your payment proof is currently being verified by
                    our team.</p>
                <p class="text-slate-500 text-sm mb-8 max-w-md">The verification process usually takes up to 24 hours.
                    Please check your email and transaction history page regularly.</p>

                <div class="bg-slate-800/50 border border-slate-700 p-6 rounded-2xl w-full max-w-md mb-8">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400">Invoice</span>
                        <span class="text-white font-mono font-semibold">{{ $transaction->invoice_code }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400">Name</span>
                        <span class="text-white font-semibold">{{ $transaction->buyer_name }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-400">Ticket Quantity</span>
                        <span class="text-white font-semibold">{{ $transaction->transactionItems->sum('quantity') }}
                            Tiket</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-slate-700 mt-2">
                        <span class="text-slate-300 font-bold">Total</span>
                        <span class="text-[#ff5b1d] font-bold">IDR
                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('user.transactions-history') }}"
                    class="px-8 py-4 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold transition flex items-center gap-3">
                    <i class="fas fa-history"></i> Check Transaction History
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
                <h3 class="text-white font-bold text-lg tracking-wider">TERMS AND CONDITIONS</h3>
            </div>

            <div class="overflow-y-auto flex-1 px-6 py-4 text-slate-300 text-sm space-y-3" x-ref="tncContent"
                x-on:scroll="if($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10) scrolled = true">

                <p class="font-bold text-white">A. GENERAL TERMS & TICKET VALIDITY</p>
                <p><span class="font-semibold">1.</span> Tickets are only valid for the date, time, and event stated on
                    the ticket.</p>
                <p><span class="font-semibold">2.</span> One (1) ticket is valid for one (1) person.</p>
                <p><span class="font-semibold">3.</span> Tickets are non-transferable without official approval from
                    the organizer.</p>
                <p><span class="font-semibold">4.</span> Tickets are considered valid only if purchased through
                    official sales channels designated by the organizer.</p>
                <p><span class="font-semibold">5.</span> The organizer reserves the right to:</p>
                <p class="ml-4"><span class="font-semibold">a.</span> Reject tickets that are damaged, unreadable,
                    duplicated, or obtained unlawfully.</p>
                <p class="ml-4"><span class="font-semibold">b.</span> Take legal action, both civil and criminal,
                    against individuals who obtain tickets illegally.</p>

                <p class="font-bold text-white mt-4">B. PURCHASE & REFUND POLICY</p>
                <p><span class="font-semibold">1.</span> All ticket purchases are final and cannot be canceled.</p>
                <p><span class="font-semibold">2.</span> Tickets are non-refundable and non-exchangeable, except in the
                    event of official cancellation by the organizer.</p>
                <p><span class="font-semibold">3.</span> The organizer is not responsible for failed transactions
                    caused by user input errors or network issues.</p>
                <p><span class="font-semibold">4.</span> In the event of cancellation by the organizer, refund
                    procedures will be announced through official PIFF 2026 communication channels.</p>
                <p><span class="font-semibold">5.</span> In the event of rescheduling, tickets will remain valid for
                    the new date.</p>

                <p class="font-bold text-white mt-4">C. ACCESS & ENTRY REGISTRATION</p>
                <p><span class="font-semibold">1.</span> Visitors must present their digital ticket (e-ticket) during
                    registration.</p>
                <p><span class="font-semibold">2.</span> The e-ticket will be exchanged for a wristband, which must be
                    worn at all times within the event area.</p>
                <p><span class="font-semibold">3.</span> Visitors without a valid wristband will not be allowed to
                    enter the event area.</p>

                <p class="font-bold text-white mt-4">D. EVENT REGULATIONS</p>
                <p><span class="font-semibold">1.</span> Visitors must maintain order and comply with all applicable
                    rules during the event.</p>
                <p><span class="font-semibold">2.</span> Bringing prohibited items is strictly forbidden as per
                    organizer regulations.</p>
                <p><span class="font-semibold">3.</span> The organizer reserves the right to remove any visitor who
                    violates the rules without refund.</p>
                <p><span class="font-semibold">5.</span> By purchasing a ticket, visitors consent to being photographed
                    or recorded for event documentation and promotional purposes.</p>

                <p class="font-bold text-white mt-4">E. FORCE MAJEURE</p>
                <p><span class="font-semibold">1.</span> In the event of force majeure, the organizer reserves the
                    right to cancel, postpone, or modify the event format.</p>
                <p><span class="font-semibold">2.</span> Official updates regarding force majeure will be announced
                    through PIFF 2026 official communication channels.</p>

                <p class="text-slate-500 text-xs mt-4">Scroll down to agree.</p>
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
                    AGREE
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function copyText(text, btn) {
        // Fallback method yang reliable di semua browser
        const el = document.createElement('textarea');
        el.value = text;
        el.style.position = 'fixed';
        el.style.opacity = '0';
        document.body.appendChild(el);
        el.focus();
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);

        // Visual feedback pada tombol
        const icon = btn.querySelector('i');
        const originalClass = icon.className;
        icon.className = 'fas fa-check';
        btn.classList.add('text-green-400');
        setTimeout(() => {
            icon.className = originalClass;
            btn.classList.remove('text-green-400');
        }, 1500);

        Toastify({
            text: 'Copied!',
            duration: 1500,
            gravity: 'top',
            position: 'right',
            style: {
                background: '#10b981',
                color: '#fff'
            }
        }).showToast();
    }
</script>
