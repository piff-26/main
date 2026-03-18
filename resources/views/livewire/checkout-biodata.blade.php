<div>
    @if (session()->has('voucher_success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('voucher_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">Informasi Pemesan</h5>
                    <small class="text-muted">Data ini akan disimpan otomatis setiap Anda selesai mengetik.</small>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" wire:model.blur="buyer_name" class="form-control" placeholder="Sesuai KTP/KTM">
                        @error('buyer_name') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" wire:model.blur="buyer_phone" class="form-control" placeholder="Contoh: 08123456789">
                        @error('buyer_phone') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kota Asal <span class="text-danger">*</span></label>
                        <input type="text" wire:model.blur="city" class="form-control" placeholder="Contoh: Surabaya">
                        @error('city') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tahu acara ini dari mana?</label>
                        <select wire:model.blur="source_info" class="form-select">
                            <option value="">-- Pilih Sumber Informasi --</option>
                            <option value="Social Media">Social Media (IG/TikTok)</option>
                            <option value="Website resmi">Website Resmi</option>
                            <option value="Iklan">Iklan</option>
                            <option value="Poster">Poster Kampus</option>
                            <option value="Teman">Teman / Keluarga</option>
                            <option value="Dosen">Dosen</option>
                        </select>
                        @error('source_info') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0 position-sticky" style="top: 20px;">
                <div class="card-header bg-white pt-4 pb-2 border-bottom">
                    <h5 class="fw-bold mb-0">Ringkasan Pesanan</h5>
                </div>
                
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-4">
                        @foreach($transaction->transactionItems as $item)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $item->ticketCategory->name }}</h6>
                                    <small class="text-muted">{{ $item->quantity }}x Tiket</small>
                                </div>
                                <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                        
                        @if($discount_amount > 0)
                            <li class="list-group-item px-0 d-flex justify-content-between text-success fw-semibold border-bottom-0">
                                <span>Diskon Voucher</span>
                                <span>- Rp {{ number_format($discount_amount, 0, ',', '.') }}</span>
                            </li>
                        @endif
                    </ul>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kode Voucher (Opsional)</label>
                        <div class="input-group">
                            <input type="text" wire:model="voucher_code" class="form-control text-uppercase" placeholder="Masukkan kode" {{ $applied_voucher_id ? 'disabled' : '' }}>
                            
                            @if($applied_voucher_id)
                                <button wire:click="removeVoucher" class="btn btn-outline-danger" type="button"><i class="fas fa-times"></i> Hapus</button>
                            @else
                                <button wire:click="applyVoucher" class="btn btn-secondary" type="button">Terapkan</button>
                            @endif
                        </div>
                        @error('voucher_code') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                        <h5 class="fw-bold mb-0">Total Bayar</h5>
                        <h4 class="text-primary fw-bold mb-0">Rp {{ number_format($grand_total, 0, ',', '.') }}</h4>
                    </div>

                    <button wire:click="processToPayment" class="btn btn-primary btn-lg w-100 fw-bold">
                        <span wire:loading.remove wire:target="processToPayment">Lanjut ke Pembayaran</span>
                        <span wire:loading wire:target="processToPayment">Memproses... <i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>