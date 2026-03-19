@php
    $categoryName = strtolower(trim($ticket->ticketCategory->name));
    $colors = [
        'gold'     => '#b78727',
        'platinum' => '#494949',
        'silver'   => '#c1c1c1',
        'regular'  => '#cecece',
    ];
    $headingColor = $colors[$categoryName] ?? '#333333';
@endphp

<div style="border: 2px dashed #333; padding: 30px; border-radius: 10px; margin-top: 50px;">

    <div class="text-center">
        <h1 style="text-transform: uppercase; letter-spacing: 3px; color: {{ $headingColor }};">
            TIKET {{ $ticket->ticketCategory->name }}
        </h1>
        <h2>{{ $ticket->ticketCategory->event->name }}</h2>
    </div>

    <hr style="border-top: 1px dashed #ccc; margin: 20px 0;">

    <table class="w-100">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <p><strong>Nama:</strong><br> {{ $transaction->buyer_name }}</p>
                <p><strong>Invoice:</strong><br> {{ $transaction->invoice_code }}</p>
                <p><strong>Kode Tiket:</strong><br> <span style="font-size: 20px;">{{ $ticket->ticket_code }}</span></p>
            </td>

            <td style="width: 40%; text-align: center; vertical-align: top;">
                @php
                    $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(
                        route('api.checkin', $ticket->ticket_code),
                    );
                    $qrCodeBase64 = base64_encode($qrCodeSvg);
                @endphp
                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR Code" style="margin-bottom: 10px;">

                @php
                    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                    $barcodePng = $generator->getBarcode($ticket->ticket_code, $generator::TYPE_CODE_128, 2, 50);
                    $barcodeBase64 = base64_encode($barcodePng);
                @endphp
                <div>
                    <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="Barcode" style="width: 100%; max-width: 180px;">
                </div>
                <p style="margin: 5px 0 0; font-size: 12px; letter-spacing: 2px;">{{ $ticket->ticket_code }}</p>
            </td>
        </tr>
    </table>

    <div class="text-center" style="margin-top: 30px; font-size: 12px; color: #777;">
        Tunjukkan halaman ini saat masuk ke lokasi acara.
    </div>
</div>
