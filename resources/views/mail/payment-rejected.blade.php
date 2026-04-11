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
        .reason-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 16px; margin: 16px 0; }
        .badge { display: inline-block; background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
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
            <p>Mohon maaf, pembayaran Anda untuk invoice <strong>{{ $transaction->invoice_code }}</strong> <strong>tidak dapat diverifikasi</strong>.</p>

            <div class="invoice">
                <div class="row">
                    <span class="label">Invoice</span>
                    <span class="value">{{ $transaction->invoice_code }}</span>
                </div>
                <div class="row">
                    <span class="label">Total</span>
                    <span class="value">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="row" style="border-bottom:none;">
                    <span class="label">Status</span>
                    <span class="value"><span class="badge">Ditolak</span></span>
                </div>
            </div>

            <div class="reason-box">
                <strong style="color:#991b1b;">Alasan Penolakan:</strong>
                <p style="margin:8px 0 0; color:#7f1d1d;">{{ $transaction->rejection_reason }}</p>
            </div>

            <p style="color:#666; font-size:13px;">Kuota tiket Anda telah dikembalikan. Jika ada pertanyaan, silakan hubungi panitia PIFF 2026.</p>
        </div>
        <div class="footer">
            &copy; 2026 PIFF - Petra International Film Festival
        </div>
    </div>
</body>
</html>
