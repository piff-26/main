@php
    $categoryName = strtolower(trim($ticket->ticketCategory->name));
    $colors = [
        'gold'     => '#b78727',
        'platinum' => '#494949',
        'silver'   => '#c1c1c1',
        'regular'  => '#cecece',
    ];
    $headingColor = $colors[$categoryName] ?? '#333333';

    $qrCodeSvg     = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($ticket->ticket_code);
    $qrCodeBase64  = base64_encode($qrCodeSvg);

    $generator     = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $barcodePng    = $generator->getBarcode($ticket->ticket_code, $generator::TYPE_CODE_128, 4, 100);
    $barcodeBase64 = base64_encode($barcodePng);
@endphp

<div style="border: 2px dashed #333; padding: 30px; border-radius: 10px; margin-top: 50px;">

    {{-- Header --}}
    <div style="text-align: center;">
        <h1 style="text-transform: uppercase; letter-spacing: 3px; color: {{ $headingColor }}; margin: 0 0 8px 0;">
            TICKET {{ $ticket->ticketCategory->name }}
        </h1>
        <h2 style="margin: 0;">{{ $ticket->ticketCategory->event->name }}</h2>
    </div>

    <hr style="border-top: 1px dashed #ccc; margin: 20px 0;">

    {{-- QR Code --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}"
             alt="QR Code"
             style="width: 250px; height: 250px;">
    </div>

    {{-- Barcode --}}
    <div style="text-align: center; margin-bottom: 8px;">
        <img src="data:image/png;base64,{{ $barcodeBase64 }}"
             alt="Barcode"
             style="width: 380px; height: 100px;">
    </div>

    {{-- Kode tiket di bawah barcode --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <span style="font-size: 12px; letter-spacing: 3px; color: #555; font-family: monospace;">{{ $ticket->ticket_code }}</span>
    </div>

    <hr style="border-top: 1px dashed #ccc; margin: 20px 0;">

    {{-- Info Tiket --}}
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 6px 12px; width: 33%;">
                <div style="font-size: 11px; color: #888; margin-bottom: 2px;">NAME</div>
                <div style="font-size: 15px; font-weight: bold;">{{ $ticket->holder_name ?? $transaction->buyer_name }}</div>
            </td>
            <td style="padding: 6px 12px; width: 33%;">
                <div style="font-size: 11px; color: #888; margin-bottom: 2px;">INVOICE</div>
                <div style="font-size: 13px; font-family: monospace;">{{ $transaction->invoice_code }}</div>
            </td>
            <td style="padding: 6px 12px; width: 33%;">
                <div style="font-size: 11px; color: #888; margin-bottom: 2px;">TICKET CODE</div>
                <div style="font-size: 13px; font-family: monospace;">{{ $ticket->ticket_code }}</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 24px; font-size: 11px; color: #999;">
        Show this ticket's QR code at the entrance. Valid for one-time use only. Duplicate or photocopied tickets will not be accepted.
    </div>

</div>
