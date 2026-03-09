<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $transaction->invoice_code }}</title>
    <style>
        /* Setup basic font dan margin untuk PDF */
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 0; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        /* Tambahkan CSS styling lainnya di sini (hindari flexbox/grid karena DomPDF kurang support) */
    </style>
</head>
<body>

    @include('pdf.tickets.terms-summary', ['transaction' => $transaction])

    @foreach($transaction->tickets as $ticket)
        <div class="page-break"></div>
        
        @php
            // Misal nama kategori di DB: "Platinum", maka akan memanggil file "platinum.blade.php"
            $designFile = 'pdf.tickets.designs.' . strtolower($ticket->ticketCategory->name);
        @endphp

        @if(View::exists($designFile))
            @include($designFile, ['ticket' => $ticket, 'transaction' => $transaction])
        @else
            @include('pdf.tickets.designs.regular', ['ticket' => $ticket, 'transaction' => $transaction])
        @endif
    @endforeach

</body>
</html>