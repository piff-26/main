<div>
    @if (session()->has('voucher_success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <p class="text-sm text-green-700 font-medium">{{ session('voucher_success') }}</p>
            </div>
        </div>
    @endif

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        
        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Data Pemesan</h3>
                    <a href="{{ route('checkout.step2', $transaction->invoice_code) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Edit Data</a>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $transaction->buyer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor WhatsApp</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $transaction->buyer_phone }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Kota Asal</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $transaction->city }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Tiket</h3>
                </div>
                <ul class="divide-y divide-gray-200">
                    @foreach($transaction->transactionItems as $item)
                        <li class="px-6 py-5 flex justify-between items-center">
                            <div>
                                <h4 class="text-base font-bold text-gray-900">{{ $item->ticketCategory->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $item->quantity }}x Tiket</p>
                            </div>
                            <span class="text-base font-semibold text-gray-900">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
            
        </div>

        <div class="lg:col-span-5 mt-8 lg:mt-0">
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Punya Kode Voucher?</label>
                    <div class="flex rounded-md shadow-sm">
                        <input type="text" wire:model="voucher_code" 
                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 uppercase sm:text-sm {{ $applied_voucher_id ? 'bg-gray-100 cursor-not-allowed' : '' }}" 
                               placeholder="Masukkan kode" 
                               {{ $applied_voucher_id ? 'disabled' : '' }}>
                        
                        @if($applied_voucher_id)
                            <button wire:click="removeVoucher" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-red-300 rounded-r-md bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100 focus:outline-none focus:ring-1 focus:ring-red-500 transition">
                                Hapus
                            </button>
                        @else
                            <button wire:click="applyVoucher" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 transition">
                                Terapkan
                            </button>
                        @endif
                    </div>
                    @error('voucher_code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <hr class="border-gray-200 my-4">

                <dl class="space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <dt>Subtotal</dt>
                        <dd class="font-medium text-gray-900">Rp {{ number_format($transaction->total_amount + $discount_amount, 0, ',', '.') }}</dd>
                    </div>
                    
                    @if($discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <dt class="flex items-center">Diskon Voucher</dt>
                            <dd class="font-medium">- Rp {{ number_format($discount_amount, 0, ',', '.') }}</dd>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-4">
                        <dt class="text-base font-bold text-gray-900">Total Tagihan</dt>
                        <dd class="text-xl font-extrabold text-blue-600">Rp {{ number_format($grand_total, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <button wire:click="processToPayment" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <span wire:loading.remove wire:target="processToPayment">Pilih Metode Pembayaran</span>
                        <span wire:loading wire:target="processToPayment" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                    <p class="mt-4 text-xs text-center text-gray-500 flex items-center justify-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        Pembayaran aman dilindungi oleh Midtrans
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>