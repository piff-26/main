<div>
    @if (session()->has('voucher_success'))
        <div class="alert alert-success">{{ session('voucher_success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <h4>Data Diri Pembeli</h4>
            
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" wire:model.blur="buyer_name" class="form-control">
                @error('buyer_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Nomor WhatsApp</label>
                <input type="text" wire:model.blur="buyer_phone" class="form-control">
                @error('buyer_phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Kota Asal</label>
                <input type="text" wire:model.blur="city" class="form-control">
                @error('city') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Tahu PIFF dari mana?</label>
                <select wire:model.blur="source_info" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="Social Media">Social Media</option>
                    <option value="Website resmi">Website Resmi</option>
                    <option value="Iklan">Iklan</option>
                    <option value="Poster">Poster</option>
                    <option value="Teman">Teman</option>
                    <option value="Dosen">Dosen</option>
                </select>
                @error('source_info') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="col-md-4">
            <h4>Ringkasan Pesanan</h4>
            <ul class="list-group mb-3">
                @foreach($transaction->transactionItems as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $item->ticketCategory->name }} (x{{ $item->quantity }})</span>
                        <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
                
                @if($discount_amount > 0)
                    <li class="list-group-item d-flex justify-content-between text-success">
                        <span>Diskon Voucher</span>
                        <span>- Rp {{ number_format($discount_amount, 0, ',', '.') }}</span>
                    </li>
                @endif
                
                <li class="list-group-item d-flex justify-content-between font-weight-bold">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                </li>
            </ul>

            <div class="input-group mb-3">
                <input type="text" wire:model="voucher_code" class="form-control" placeholder="Kode Voucher">
                <button wire:click="applyVoucher" class="btn btn-secondary" type="button">Terapkan</button>
            </div>
            @error('voucher_code') <span class="text-danger d-block mb-2">{{ $message }}</span> @enderror

            @if($applied_voucher_id)
                <button wire:click="removeVoucher" class="btn btn-sm btn-outline-danger mb-3">Hapus Voucher</button>
            @endif

            <button wire:click="processToPayment" class="btn btn-primary w-100">Lanjut ke Pembayaran</button>
        </div>
    </div>
</div>