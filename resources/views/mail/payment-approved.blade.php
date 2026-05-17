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
        .total { color: #ff5b1d; font-size: 18px; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .footer { background: #111; color: #888; text-align: center; padding: 16px; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('assets/mail/header_email.png')) }}" alt="PIFF 2026" style="width: 100%; display: block; border-radius: 8px 8px 0 0;">
        </div>
        <div class="body">
            @php
                $hasOffline = $transaction->transactionItems->whereNotNull('ticket_category_id')->isNotEmpty();
                $hasOnline  = $transaction->transactionItems->whereNotNull('online_ticket_id')->isNotEmpty();
                $offlineCount = $hasOffline ? $transaction->tickets->count() : 0;
                $onlineQuantity = $hasOnline ? $transaction->transactionItems->whereNotNull('online_ticket_id')->sum('quantity') : 0;
            @endphp
            <p>Hello <strong>{{ $transaction->buyer_name }}</strong>,</p>
            @if($hasOffline && $hasOnline)
                <p>🎉 Your payment has been <strong>verified and approved</strong>! Your E-Ticket and Invoice are attached to this email.</p>
            @elseif($hasOffline)
                <p>🎉 Your payment has been <strong>verified and approved</strong>! Your E-Ticket is attached to this email.</p>
            @else
                <p>🎉 Your payment has been <strong>verified and approved</strong>! Your invoice is attached to this email.</p>
            @endif

            <div class="invoice">
                <div class="row">
                    <span class="label">Invoice</span>
                    <span class="value">{{ $transaction->invoice_code }}</span>
                </div>
                @if($hasOffline)
                <div class="row">
                    <span class="label">Ticket Count</span>
                    <span class="value">{{ $offlineCount }} ticket(s)</span>
                </div>
                @endif
                @if($hasOnline)
                <div class="row">
                    <span class="label">Online Pass</span>
                    <span class="value">{{ $onlineQuantity }} pass(es)</span>
                </div>
                @endif
                <div class="row">
                    <span class="label">Status</span>
                    <span class="value"><span class="badge">Payment Approved</span></span>
                </div>
                <div class="row" style="border-bottom:none;">
                    <span class="label">Total Paid</span>
                    <span class="value total">IDR {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($hasOffline)
            <p style="color:#666; font-size:13px;">Present the QR Code on your ticket during check-in at the event venue. You can also download your ticket from the transaction history page.</p>
            @endif
            @if($hasOnline)
            <p style="color:#666; font-size:13px;">You can access your online event content directly from our portal. Log in to your account and go to the Online Event section to start watching.</p>
            @endif
        </div>
        <div class="footer">
            &copy; 2026 PIFF - Petra International Film Festival
        </div>
    </div>
</body>
</html>
