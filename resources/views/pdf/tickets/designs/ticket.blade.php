@php
    $categoryName = strtolower(trim($ticket->ticketCategory->name));
    $colors = [
        'gold' => '#b78727',
        'platinum' => '#615d5d',
        'silver' => '#c1c1c1',
        'regular' => '#000',
    ];
    $headingColor = $colors[$categoryName] ?? '#fff';

    $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($ticket->ticket_code);
    $qrCodeBase64 = base64_encode($qrCodeSvg);

    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $barcodePng = $generator->getBarcode($ticket->ticket_code, $generator::TYPE_CODE_128, 3, 80);
    $barcodeBase64 = base64_encode($barcodePng);

    // $bgImageSrc dikirim dari controller sebagai base64 data URI
    $isRegular = ($categoryName === 'regular');
    $showBg    = $isRegular && !empty($bgImageSrc);
@endphp

<style>
    @font-face {
        font-family: 'MontechMedium';
        src: url('{{ asset('assets/fonts/MONTECHV02-Medium.ttf') }}') format('truetype');
    }

    .font-montech-medium {
        font-family: 'MontechMedium', sans-serif;
    }
</style>

{{-- Main Container --}}
<div style="position: relative; background-color: #111; padding: 40px 30px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ffffff; min-height: 100vh;">

    {{-- Background image untuk Regular (DomPDF: gunakan img tag, bukan CSS background-image) --}}
    @if ($showBg)
        <img src="{{ $bgImageSrc }}" alt=""
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; opacity: 0.6;">
    @endif

    {{-- Overlay agar konten terbaca --}}
    <div style="position: relative; z-index: 1;">

    {{-- Header --}}
    <div
        style="text-align: center; margin-bottom: 25px;background-color: {{ $headingColor }}; border-radius: 10px; padding: 20px; margin-top:20px;">
        <h1
            style="text-transform: uppercase; letter-spacing: 2px; font-size: 45px; font-weight: 900; margin: 0 0 10px 0; font-weight: bold;">
            {{ $ticket->ticketCategory->name }} TICKET
        </h1>
        <h2 style="margin: 0; font-size: 28px; font-weight: normal; color: #2294cd; letter-spacing: 1px;">
            {{ $ticket->ticketCategory->event->name }}
        </h2>
    </div>

    <hr style="border-top: 2px solid #ffffff; margin: 25px 0;margin-bottom:40px;">

    {{-- QR Code --}}
    <div style="text-align: center; margin-bottom: 30px;">
        <div
            style="display: inline-block; background-color: #ffffff; padding: 15px; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR Code"
                style="width: 300px; height: 300px; display: block;">
        </div>
    </div>

    {{-- Barcode --}}
    <div style="text-align: center; margin-bottom: 15px;">
        <div style="display: inline-block; background-color: #ffffff; padding: 10px 20px; border-radius: 8px;">
            <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="Barcode"
                style="width: 375px; height: 75px; display: block;">
        </div>
    </div>

    {{-- Kode tiket di bawah barcode --}}
    <div style="text-align: center; margin-bottom: 30px;">
        <span style="font-size: 25px; font-weight: bold; letter-spacing: 3px; color: #ffffff;">
            {{ $ticket->ticket_code }}
        </span>
    </div>

    <hr style="border-top: 2px solid #ffffff; margin: 25px 0; margin-top:30px;">

    {{-- Info Tiket --}}
    <table style="width: 100%; border-collapse: collapse; text-align: center;">
        <tr>
            <td style="padding: 10px; width: 33%;">
                <div
                    style="font-size: 15px; color: #ffffff; font-weight: bold; margin-bottom: 6px; letter-spacing: 1px;">
                    NAME</div>
                <div style="font-size: 19px; font-weight: bold; color: #00bfff; font-style: italic;">
                    {{ $ticket->holder_name ?? $transaction->buyer_name }}
                </div>
            </td>
            <td style="padding: 10px; width: 33%;">
                <div
                    style="font-size: 15px; color: #ffffff; font-weight: bold; margin-bottom: 6px; letter-spacing: 1px;">
                    INVOICE</div>
                <div
                    style="font-size: 19px; color: #00bfff; font-style: italic; font-weight: bold;">
                    {{ $transaction->invoice_code }}
                </div>
            </td>
            <td style="padding: 10px; width: 33%;">
                <div
                    style="font-size: 15px; color: #ffffff; font-weight: bold; margin-bottom: 6px; letter-spacing: 1px;">
                    TICKET CODE</div>
                <div
                    style="font-size: 19px; color: #00bfff; font-style: italic; font-weight: bold;">
                    {{ $ticket->ticket_code }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Footer Rules --}}
    <div
        style="text-align: center; margin-top: 35px; font-size: 11px; color: #cccccc; line-height: 1.5; padding: 0 20px;margin-top:41.3px;">
        Show this ticket's QR code at the entrance. Valid for one-time use only. <br> Duplicate or photocopied tickets
        will not be accepted.
    </div>
    </div>

</div>
