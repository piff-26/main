@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Lengkapi Data Diri</h2>
    
    @livewire('checkout-biodata', ['invoice_code' => $invoiceCode])
    
</div>
@endsection