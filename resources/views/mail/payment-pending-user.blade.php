<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #111; padding: 0; text-align: center; border-radius: 8px 8px 0 0; overflow: hidden; }
        .body { background: #f9f9f9; padding: 24px; border: 1px solid #eee; }
        .invoice { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 16px; margin: 16px 0; }
        .row { padding: 6px 0; border-bottom: 1px solid #f0f0f0; }
        .label { color: #666; }
        .value { font-weight: bold; float: right; }
        .badge { display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .footer { background: #111; color: #888; text-align: center; padding: 16px; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('assets/mail/header_email.png')) }}" alt="PIFF 2026" style="width: 100%; display: block; border-radius: 8px 8px 0 0;">
        </div>
        <div class="body">
            <p>Hello <strong>{{ $transaction->buyer_name }}</strong>,</p>
            <p>We have received your payment proof. Our team is currently verifying your payment.</p>

            <div class="invoice">
                <div class="row">
                    <span class="label">Invoice</span>
                    <span class="value">{{ $transaction->invoice_code }}</span>
                </div>
                <div class="row">
                    <span class="label">Total</span>
                    <span class="value">IDR {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="row" style="border-bottom:none;">
                    <span class="label">Status</span>
                    <span class="value"><span class="badge">Pending Verification</span></span>
                </div>
            </div>

            <p style="color:#666; font-size:13px;">Verification usually takes up to 24 hours. Please check your email and transaction history page regularly.</p>
        </div>
        <div class="footer">
            &copy; 2026 PIFF - Petra International Film Festival
        </div>
    </div>
</body>
</html>
