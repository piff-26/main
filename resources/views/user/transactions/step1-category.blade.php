@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="row mb-4">
        <div class="col-md-8 offset-md-2 text-center">
            <h2 class="fw-bold">{{ $event->name }}</h2>
            <p class="text-muted">Langkah 1 dari 4: Pilih kategori dan jumlah tiket yang ingin dibeli.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            @if(session('error'))
                <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
            @endif

            <form action="{{ route('checkout.storeStep1', $event->slug) }}" method="POST">
                @csrf
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h5 class="card-title mb-0">1. Pilih Kategori Tiket</h5>
                    </div>
                    <div class="card-body">
                        
                        @foreach($event->ticketCategories as $category)
                            @php
                                // Cek apakah sold_count sudah menyentuh/melewati quota
                                $isSoldOut = $category->quota !== null && $category->sold_count >= $category->quota;
                            @endphp
                            
                            <label class="border rounded p-3 mb-3 d-block {{ $isSoldOut ? 'bg-light text-muted' : 'border-primary' }}" style="{{ $isSoldOut ? 'cursor: not-allowed;' : 'cursor: pointer;' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" id="cat_{{ $category->id }}" value="{{ $category->id }}" {{ $isSoldOut ? 'disabled' : '' }} required>
                                        
                                        <div class="ms-2">
                                            <span class="fw-bold fs-5">{{ $category->name }}</span>
                                            <span class="d-block text-primary fw-semibold mt-1">Rp {{ number_format($category->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end">
                                        @if($isSoldOut)
                                            <span class="badge bg-danger px-3 py-2">Habis Terjual</span>
                                        @else
                                            <span class="badge bg-success px-3 py-2">Tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach

                        @error('category_id') 
                            <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> 
                        @enderror

                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h5 class="card-title mb-0">2. Jumlah Tiket</h5>
                    </div>
                    <div class="card-body">
                        <select name="qty" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Jumlah Pembelian --</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Tiket</option>
                            @endfor
                        </select>
                        <small class="text-muted mt-2 d-block">*Maksimal pembelian 5 tiket per transaksi.</small>
                        
                        @error('qty') 
                            <span class="text-danger small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                        Lanjut Isi Biodata Diri <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection