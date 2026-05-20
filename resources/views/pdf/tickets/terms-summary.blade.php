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
        <p style="font-weight: bold; margin-bottom: 6px; {{ $hasOffline ? 'margin-top: 20px;' : '' }}">
    TERMS & CONDITIONS – PIFF 2026 ONLINE ACCESS</p>

    <ol style="font-size: 14px; line-height: 1.5; margin-top: 0; padding-left: 18px;">

        <li>
            <strong>Access Policy</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>PIFF 2026 Online Access will only be available until H+5 after the festival ends (five days after May 30, 2026). Access will automatically expire after the stated period.</li>
                <li>Online access is valid only for the account or email address used during registration.</li>
                <li>All access is personal and non-transferable.</li>
                <li>Users are responsible for maintaining the confidentiality of their accounts, passwords, and access links.</li>
                <li>PIFF 2026 reserves the right to limit or terminate access if there is any indication of account misuse or violation of these Terms & Conditions.</li>
                <li>Program schedules are subject to change at any time with or without prior notice.</li>
            </ul>
        </li>

        <li>
            <strong>Payment & Refund Policy</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>All completed payments are final.</li>
                <li>Purchased tickets or online passes are non-refundable, non-exchangeable, and non-transferable unless otherwise stated by the organizer.</li>
                <li>In the event of force majeure or certain technical issues, PIFF 2026 reserves the right to determine the appropriate form of compensation.</li>
            </ul>
        </li>

        <li>
            <strong>Copyright & Content Usage</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>All films screened during PIFF 2026 remain the intellectual property and copyright of their respective filmmakers, producers, and rights holders.</li>
                <li>All films, videos, visuals, audio materials, presentations, designs, and other content presented during PIFF 2026 are protected by copyright and intellectual property laws.</li>
                <li>Users are prohibited from recording, copying, distributing, reproducing, restreaming, excessive screenshotting, or republishing any PIFF 2026 content without written permission.</li>
                <li>Any violation may result in access termination without refund and may lead to legal action in accordance with applicable laws.</li>
            </ul>
        </li>

        <li>
            <strong>Technical Issues</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>Users are responsible for ensuring that their internet connection, devices, and software support access to the online services.</li>
                <li>PIFF 2026 is not responsible for disruptions caused by the user’s internet connection, device malfunction, third-party platform disruptions, or circumstances beyond the organizer’s control.</li>
                <li>In the event of technical issues originating from the organizer’s side, PIFF 2026 will make reasonable efforts to provide appropriate solutions.</li>
            </ul>
        </li>

        <li>
            <strong>Privacy & Data Policy</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>By registering, users agree that their personal data may be used for administrative, communication, documentation, and promotional purposes related to PIFF 2026.</li>
                <li>PIFF 2026 is committed to protecting users’ personal data and will not share such data with third parties without consent, unless required by law.</li>
            </ul>
        </li>

        <li>
            <strong>Force Majeure</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>PIFF 2026 shall not be held responsible for delays, changes, or cancellations caused by circumstances beyond the organizer’s reasonable control, including but not limited to natural disasters, internet disruptions, government policies, war, riots, or digital system failures.</li>
            </ul>
        </li>

        <li>
            <strong>Changes to Terms & Conditions</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                <li>PIFF 2026 reserves the right to modify or update these Terms & Conditions at any time without prior notice.</li>
                <li>By accessing PIFF 2026 online services, users acknowledge that they have understood and agreed to all applicable Terms & Conditions.</li>
            </ul>
        </li>
    </ol>
    @endif
</div>
