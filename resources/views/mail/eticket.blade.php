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
        .total { color: #ff5b1d; font-size: 18px; }
        .footer { background: #111; color: #888; text-align: center; padding: 16px; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PIFF 2026</h1>
            <p style="color:#ccc; margin:4px 0 0;">Petra International Film Festival</p>
        </div>
        <div class="body">
            <p>Halo <strong>{{ $transaction->buyer_name }}</strong>,</p>
            <p>Pembayaran Anda telah berhasil! E-Ticket terlampir dalam email ini sebagai file PDF.</p>

            <div class="invoice">
                <div class="row">
                    <span class="label">Invoice</span>
                    <span class="value">{{ $transaction->invoice_code }}</span>
                </div>
                <div class="row">
                    <span class="label">Jumlah Tiket</span>
                    <span class="value">{{ $transaction->tickets->count() }} tiket</span>
                </div>
                <div class="row" style="border-bottom:none;">
                    <span class="label">Total Dibayar</span>
                    <span class="value total">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <p style="color:#666; font-size:13px;">Tunjukkan QR Code pada tiket saat check-in di lokasi acara.</p>
        </div>
        <div class="footer">
            &copy; 2026 PIFF - Petra International Film Festival
        </div>
    </div>
</body>
</html>
