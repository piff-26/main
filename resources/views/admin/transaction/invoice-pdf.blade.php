<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $transaction->invoice_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; background: #fff; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px 30px; }
        
        /* Header with gradient */
        .header {
            background: linear-gradient(135deg, #27b4f7 0%, #1a8cd8 100%);
            color: white;
            padding: 40px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .header-content { position: relative; z-index: 1; }
        .header h1 { font-size: 42px; font-weight: 700; margin-bottom: 8px; letter-spacing: 3px; }
        .header .tagline { font-size: 16px; opacity: 0.95; margin-bottom: 5px; font-weight: 500; }
        .header .year { font-size: 14px; opacity: 0.85; }
        
        /* Invoice Info Bar */
        .invoice-bar {
            background: #f8f9fa;
            padding: 20px 25px;
            border-radius: 8px;
            border-left: 5px solid #27b4f7;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-bar .invoice-number {
            font-size: 24px;
            font-weight: 700;
            color: #27b4f7;
        }
        .invoice-bar .dates {
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .invoice-bar .dates strong { color: #333; }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-draft { background: #fef3c7; color: #92400e; }
        .status-failed { background: #f3f4f6; color: #1f2937; }
        .status-expired { background: #fee2e2; color: #991b1b; }
        
        /* Info Cards */
        .info-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-card {
            flex: 1;
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 25px;
        }
        .info-card h3 {
            color: #27b4f7;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #27b4f7;
            letter-spacing: 1px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
            line-height: 1.6;
        }
        .info-label { font-weight: 600; color: #666; }
        .info-value { color: #333; text-align: right; }
        
        /* Items Table */
        .items-section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #27b4f7;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .items-table thead {
            background: linear-gradient(135deg, #27b4f7 0%, #1a8cd8 100%);
            color: white;
        }
        .items-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }
        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table tbody tr:hover { background: #f8f9fa; }
        .event-name { font-weight: 700; color: #333; margin-bottom: 3px; }
        .category-name { color: #666; font-size: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .price { font-weight: 700; color: #27b4f7; }
        
        /* Total Section */
        .total-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #27b4f7;
            margin-bottom: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .total-row.discount { color: #dc3545; }
        .total-row.final {
            border-top: 3px solid #27b4f7;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 22px;
            font-weight: 700;
            color: #27b4f7;
        }
        
        /* Confirmation Box */
        .confirmation-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
        }
        .confirmation-box .icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 700;
            color: white;
        }
        .confirmation-box .title {
            font-size: 18px;
            font-weight: 700;
            color: #155724;
            margin-bottom: 8px;
        }
        .confirmation-box .message {
            font-size: 13px;
            color: #155724;
            line-height: 1.6;
        }
        
        /* Footer */
        .footer {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-top: 40px;
        }
        .footer .logo {
            font-size: 28px;
            font-weight: 700;
            color: #27b4f7;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        .footer .university {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        .footer .thank-you {
            font-size: 14px;
            color: #333;
            font-weight: 600;
            margin: 15px 0 10px 0;
        }
        .footer .note {
            font-size: 11px;
            color: #999;
            margin-top: 15px;
            font-style: italic;
        }
        
        /* Decorative Elements */
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #27b4f7, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1>PIFF 2026</h1>
                <div class="tagline">Petra International Film Festival</div>
                <div class="year">Official Transaction Invoice</div>
            </div>
        </div>
        
        <!-- Invoice Bar -->
        <div class="invoice-bar">
            <div>
                <div class="invoice-number">{{ $transaction->invoice_code }}</div>
                @php
                    use App\Enums\TransactionStatusEnum;
                    $statusClass = 'status-draft';
                    $statusText = strtoupper($transaction->transaction_status);
                    
                    if ($transaction->transaction_status === TransactionStatusEnum::PAID->value) {
                        $statusClass = 'status-paid';
                    } elseif ($transaction->transaction_status === TransactionStatusEnum::FAILED->value) {
                        $statusClass = 'status-failed';
                    } elseif ($transaction->transaction_status === TransactionStatusEnum::EXPIRED->value) {
                        $statusClass = 'status-expired';
                    }
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    {{ $statusText }}
                </span>
            </div>
            <div class="dates">
                <div><strong>Issued:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</div>
                @if($transaction->paid_at)
                <div><strong>Paid:</strong> {{ $transaction->paid_at->format('d M Y, H:i') }}</div>
                @endif
            </div>
        </div>
        
        <!-- Info Cards -->
        <div class="info-cards">
            <div class="info-card">
                <h3>Buyer Information</h3>
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $transaction->buyer_name ?? $transaction->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $transaction->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $transaction->buyer_phone ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">City</span>
                    <span class="info-value">{{ $transaction->city ?? '-' }}</span>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Payment Details</h3>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ ucfirst($transaction->transaction_status) }}</span>
                </div>
                @if($transaction->voucher)
                <div class="info-row">
                    <span class="info-label">Voucher</span>
                    <span class="info-value" style="color: #8b5cf6; font-weight: 700;">{{ $transaction->voucher->code }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Source</span>
                    <span class="info-value">{{ $transaction->source_info ?? '-' }}</span>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Items Section -->
        <div class="items-section">
            <div class="section-title">Order Details</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Event & Ticket Category</th>
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
                            @if($item->online_ticket_id)
                                <div class="event-name">Online Pass</div>
                                <div class="category-name">{{ $item->onlineTicket->name ?? '-' }}</div>
                            @else
                                <div class="event-name">{{ $item->ticketCategory->event->name ?? '-' }}</div>
                                <div class="category-name">{{ $item->ticketCategory->name ?? '-' }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right price">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span class="price">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->voucher && $transaction->discount_amount > 0)
            <div class="total-row discount">
                <span>Discount ({{ $transaction->voucher->code }})</span>
                <span>-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row final">
                <span>TOTAL AMOUNT</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Confirmation Box (if paid) -->
        @if($transaction->transaction_status === TransactionStatusEnum::PAID->value)
        <div class="confirmation-box">
            <div class="icon">OK</div>
            <div class="title">Payment Confirmed</div>
            <div class="message">
                Your payment has been successfully processed.<br>
                Tickets have been generated and sent to your email address.<br>
                Please present this invoice at the event entrance.
            </div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <div class="logo">PIFF 2026</div>
            <div class="university">Petra Christian University</div>
            <div class="university">Surabaya, Indonesia</div>
            <div class="thank-you">Thank you for your participation!</div>
            <div class="note">This is a computer-generated invoice. No signature required.</div>
        </div>
    </div>
</body>
</html>