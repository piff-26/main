@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2>Pembayaran Tiket</h2>
    <p>Invoice: <strong>{{ $transaction->invoice_code }}</strong></p>
    <h3 class="text-primary mb-4">Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h3>

    <button id="pay-button" class="btn btn-success btn-lg">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // Snap Pop-up dengan token dari controller
        snap.pay('{{ $snapToken }}', {
            // Jika pembayaran berhasil
            onSuccess: function (result) {
                alert("Pembayaran Berhasil! Tiket sedang diproses.");
                window.location.href = "/user/tickets"; // Arahkan ke halaman tiket user
            },
            // Jika pembayaran ditutup sebelum selesai
            onPending: function (result) {
                alert("Menunggu pembayaran Anda!");
            },
            // Jika pembayaran gagal
            onError: function (result) {
                alert("Pembayaran Gagal!");
            },
            // Jika user menutup pop-up sebelum memilih metode bayar
            onClose: function () {
                alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
            }
        });
    };
</script>
@endsection