<div class="ticket-wrapper">
    <h2>TIKET PLATINUM</h2>
    <p>Nama: {{ $transaction->buyer_name }}</p>
    <p>Tanggal Beli: {{ $transaction->created_at->format('d M Y') }}</p>
    <p>Kode Tiket: <strong>{{ $ticket->ticket_code }}</strong></p>

    @php
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(route('api.checkin', $ticket->ticket_code));
        $qrCodeBase64 = base64_encode($qrCodeSvg);
    @endphp
    <div style="margin-bottom: 20px;">
        <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR Code">
    </div>

    @php
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodePng = $generator->getBarcode($ticket->ticket_code, $generator::TYPE_CODE_128, 2, 50);
        $barcodeBase64 = base64_encode($barcodePng);
    @endphp
    <div>
        <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="Barcode">
    </div>
</div>