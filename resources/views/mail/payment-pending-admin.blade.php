<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #111; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { color: #ff5b1d; margin: 0; font-size: 24px; }
        .body { background: #f9f9f9; padding: 24px; border: 1px solid #eee; }
        .invoice { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 16px; margin: 16px 0; }
        .row { padding: 6px 0; border-bottom: 1px solid #f0f0f0; }
        .label { color: #666; }
        .value { font-weight: bold; float: right; }
        .btn { display: inline-block; background: #ff5b1d; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 16px; }
        .footer { background: #111; color: #888; text-align: center; padding: 16px; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PIFF 2026 — Admin</h1>
            <p style="color:#ccc; margin:4px 0 0;">Notifikasi Pembayaran Masuk</p>
        </div>
        <div class="body">
            <p>Ada transaksi baru yang perlu diverifikasi:</p>

            <div class="invoice">
                <div class="row">
                    <span class="label">Invoice</span>
                    <span class="value">{{ $transaction->invoice_code }}</span>
                </div>
                <div class="row">
                    <span class="label">Nama Pembeli</span>
                    <span class="value">{{ $transaction->buyer_name }}</span>
                </div>
                <div class="row">
                    <span class="label">Email</span>
                    <span class="value">{{ $transaction->user->email }}</span>
                </div>
                <div class="row">
                    <span class="label">No. Telepon</span>
                    <span class="value">{{ $transaction->buyer_phone }}</span>
                </div>
                <div class="row" style="border-bottom:none;">
                    <span class="label">Total</span>
                    <span class="value">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <p>Silakan login ke dashboard admin untuk memverifikasi bukti pembayaran.</p>
            <a href="{{ url('/admin/transaction/detail/' . $transaction->invoice_code) }}" class="btn">Verifikasi Sekarang</a>
        </div>
        <div class="footer">
            &copy; 2026 PIFF - Petra International Film Festival
        </div>
    </div>
</body>
</html>
