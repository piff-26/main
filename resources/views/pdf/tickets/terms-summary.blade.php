<div style="padding: 20px; border-top: 2px solid #000;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 25%; text-align: left; vertical-align: middle;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/logo_piff_black.png'))) }}" style="height: 140px; max-width: 100%;" alt="PIFF Logo">
            </td>
            <td style="width: 50%; text-align: center; vertical-align: middle;">
                <h1 class="text-center" style="margin-bottom: 0;">INVOICE</h1>
                <h3 class="text-center" style="margin-top: 5px; color: #555;">{{ $transaction->invoice_code }}</h3>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: middle;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/logo_asfs_black.png'))) }}" style="height: 100px; max-width: 100%;" alt="ASFS Logo">
            </td>
        </tr>
    </table>

    <hr style="margin: 20px 0;">

    <table class="w-100" style="margin-bottom: 30px;">
        <tr>
            <td style="width: 50%;">
                <strong>Buyer Name:</strong><br>
                {{ $transaction->buyer_name }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Transaction Date:</strong><br>
                {{ $transaction->created_at->format('d F Y, H:i') }} UTC+7
            </td>
        </tr>
    </table>

    <h3 style="background: #f0f0f0; padding: 10px;">Ticket Summary</h3>
    <table class="w-100" border="1" style="margin-bottom: 30px; text-align: left;" cellpadding="8">
        <thead>
            <tr style="background: #ddd;">
                <th>Category</th>
                <th>Ticket Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticketCategory->name }} - {{ $ticket->ticketCategory->event->name }}</td>
                    <td>{{ $ticket->ticket_code }}</td>
                </tr>
            @endforeach
            @foreach ($transaction->transactionItems as $item)
                @if ($item->online_ticket_id && $item->onlineTicket)
                <tr>
                    <td>Online Pass - {{ $item->onlineTicket->name }}</td>
                    <td>Digital Access (Terkoneksi Akun)</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <table class="w-100" style="margin-bottom: 10px; text-align: right;">
        <tr>
            <td style="color: #555;">Subtotal</td>
            <td style="width: 180px;">IDR
                {{ number_format($transaction->total_amount + $transaction->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @if ($transaction->voucher)
            <tr>
                <td style="color: #555;">Voucher ({{ $transaction->voucher->code }})</td>
                <td style="color: #16a34a;">- IDR {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr style="font-weight: bold; font-size: 16px;">
            <td>Total</td>
            <td>IDR {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h3 style="background: #f0f0f0; padding: 10px;">Terms & Conditions</h3>

    @php
        $hasOffline = $transaction->tickets->isNotEmpty();
        $hasOnline = $transaction->transactionItems->whereNotNull('online_ticket_id')->isNotEmpty();
    @endphp

    @if ($hasOffline)
        <p style="font-weight: bold; margin-bottom: 6px;">A. GENERAL TERMS & TICKET VALIDITY</p>
        <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
            <li>Tickets are valid only for the date, time, and event specified on the ticket.</li>
            <li>One (1) ticket is valid for one (1) person.</li>
            <li>Tickets are non-transferable without prior official approval from the organizer.</li>
            <li>Tickets are considered valid only if purchased through official sales channels designated by the organizer.</li>
            <li>The organizer reserves the right to:
                <ol type="a" style="margin-top: 6px;">
                    <li>Refuse tickets that are damaged, unreadable, duplicated, or obtained unlawfully.</li>
                    <li>Take legal action, both civil and criminal, against individuals who obtain tickets illegally,
                        including but not limited to ticket forgery, duplication, or acquisition through unauthorized means.
                    </li>
                </ol>
            </li>
        </ol>

        <p style="font-weight: bold; margin-bottom: 6px;">B. PURCHASE & REFUND POLICY</p>
        <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
            <li>All ticket purchases are final and cannot be canceled.</li>
            <li>Tickets are non-refundable and non-exchangeable, except in the event of official cancellation by the organizer.</li>
            <li>The organizer is not responsible for failed transactions caused by user input errors, network issues, or device-related problems beyond the organizer’s control.</li>
            <li>In the event of cancellation by the organizer, refund procedures will be announced through official PIFF 2026 communication channels.</li>
            <li>In the event of rescheduling, tickets will remain valid for the new date.</li>
        </ol>

        <p style="font-weight: bold; margin-bottom: 6px;">C. ACCESS & ENTRY REGISTRATION</p>
        <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
            <li>Visitors must present their digital ticket (e-ticket) during registration.</li>
            <li>The e-ticket will be exchanged for a wristband, which must be worn at all times within the event area.</li>
            <li>Visitors who fail to present a valid wristband will not be permitted to enter the event area.</li>
            <li>The organizer reserves the right to re-verify tickets and identification when necessary.</li>
        </ol>

        <p style="font-weight: bold; margin-bottom: 6px;">D. EVENT REGULATIONS</p>
        <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
            <li>Visitors must maintain order and comply with all applicable rules during the event.</li>
            <li>Bringing prohibited items is strictly forbidden as per organizer regulations (e.g., weapons, narcotics, alcoholic beverages, and other dangerous items).</li>
            <li>The organizer reserves the right to remove any visitor who violates the rules without refund.</li>
            <li>Visitors are fully responsible for the safety of their personal belongings. The organizer is not liable for any loss or damage.</li>
            <li>By purchasing a ticket, visitors consent to being photographed or recorded for event documentation and promotional purposes.</li>
        </ol>

        <p style="font-weight: bold; margin-bottom: 6px;">E. FORCE MAJEURE</p>
        <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
            <li>In the event of force majeure, the organizer reserves the right to cancel, postpone, reschedule, relocate, or modify the event format without obligation to provide additional compensation beyond the organizer’s stated policies.</li>
            <li>The organizer is not responsible for any indirect losses arising from changes or cancellation due to force majeure.</li>
            <li>Official updates regarding force majeure will be announced through PIFF 2026 official communication channels.</li>
        </ol>
    @endif

    @if ($hasOnline)
        <p style="font-weight: bold; margin-bottom: 6px; {{ $hasOffline ? 'margin-top: 20px;' : '' }}">ONLINE PASS TERMS & CONDITIONS</p>
        <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
            <li>Online Pass access is strictly for personal use. Account sharing is prohibited and will result in immediate access revocation without refund.</li>
            <li>Streaming content is only available during the specified access period for your purchased pass.</li>
            <li>Copying, recording, capturing, or redistributing the streaming content in any form is strictly prohibited and subject to legal action.</li>
            <li>The organizer is not responsible for playback issues caused by the user's internet connection, insufficient bandwidth, or incompatible devices.</li>
            <li>Purchased Online Passes are non-refundable and non-transferable under any circumstances.</li>
        </ol>
    @endif
</div>
