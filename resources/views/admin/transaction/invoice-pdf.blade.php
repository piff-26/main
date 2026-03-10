<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #27b4f7 0%, #1e90ff 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            margin-bottom: 0;
        }
        
        .header h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        
        .header .subtitle {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .header .event-info {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .invoice-info {
            background: #f8f9fa;
            padding: 20px 30px;
            border-left: 4px solid #27b4f7;
            margin-bottom: 30px;
        }
        
        .invoice-info h2 {
            color: #27b4f7;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .buyer-info, .payment-info {
            flex: 1;
            padding: 20px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin: 0 10px;
        }
        
        .buyer-info h3, .payment-info h3 {
            color: #27b4f7;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #27b4f7;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: #27b4f7;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #27b4f7;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .total-row.final {
            border-top: 2px solid #27b4f7;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #27b4f7;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
        }
        
        .footer .logo {
            font-size: 18px;
            font-weight: bold;
            color: #27b4f7;
            margin-bottom: 10px;
        }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #fff;
            border: 2px dashed #27b4f7;
            border-radius: 8px;
        }
        
        .price {
            font-weight: bold;
            color: #27b4f7;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE</h1>
            <div class="subtitle">Petra International Film Festival 2026</div>
            <div class="event-info">Official Ticket Invoice</div>
        </div>
        
        <!-- Invoice Info -->
        <div class="invoice-info">
            <h2>{{ $transaction->invoice_code }}</h2>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>Issue Date:</strong> {{ $transaction->created_at->format('d F Y, H:i') }}<br>
                    @if($transaction->paid_at)
                    <strong>Paid Date:</strong> {{ $transaction->paid_at->format('d F Y, H:i') }}
                    @endif
                </div>
                <div>
                    <span class="status-badge {{ $transaction->transaction_status === 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ strtoupper($transaction->transaction_status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Buyer & Payment Info -->
        <div class="invoice-meta">
            <div class="buyer-info">
                <h3>Buyer Information</h3>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $transaction->buyer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $transaction->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $transaction->buyer_phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">City:</span>
                    <span class="info-value">{{ $transaction->city }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Source:</span>
                    <span class="info-value">{{ $transaction->source_info }}</span>
                </div>
            </div>
            
            <div class="payment-info">
                <h3>Payment Details</h3>
                <div class="info-row">
                    <span class="info-label">Method:</span>
                    <span class="info-value">{{ $transaction->payment_method }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">{{ ucfirst($transaction->transaction_status) }}</span>
                </div>
                @if($transaction->payment_reference)
                <div class="info-row">
                    <span class="info-label">Reference:</span>
                    <span class="info-value">{{ $transaction->payment_reference }}</span>
                </div>
                @endif
                @if($transaction->voucher)
                <div class="info-row">
                    <span class="info-label">Voucher:</span>
                    <span class="info-value">{{ $transaction->voucher->code }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Event & Category</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach($transaction->transactionItems as $item)
                @php 
                    $itemSubtotal = $item->price * $item->quantity;
                    $subtotal += $itemSubtotal;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $item->ticketCategory->event->name }}</strong><br>
                        <small style="color: #666;">{{ $item->ticketCategory->name }}</small>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right price">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span class="price">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount_amount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span style="color: #dc3545;">-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row final">
                <span>TOTAL AMOUNT:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- QR Code Section (if paid) -->
        @if($transaction->transaction_status === 'paid')
        <div class="qr-section">
            <div style="font-weight: bold; color: #27b4f7; margin-bottom: 10px;">✓ PAYMENT CONFIRMED</div>
            <div style="font-size: 12px; color: #666;">
                Your tickets have been generated and sent to your email.<br>
                Please present this invoice and your tickets at the event entrance.
            </div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <div class="logo">PIFF 2026</div>
            <div>Petra Christian University - Surabaya, Indonesia</div>
            <div>Thank you for your participation in Petra International Film Festival 2026!</div>
            <div style="margin-top: 10px; font-size: 10px;">
                This is a computer-generated invoice. No signature required.
            </div>
        </div>
    </div>
</body>
</html>