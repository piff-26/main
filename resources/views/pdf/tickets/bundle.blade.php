<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $transaction->invoice_code }}</title>
    <style>
        /* Setup Dasar DomPDF */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        /* Untuk memisahkan halaman */
        .page-break {
            page-break-after: always;
        }

        /* Helper classes */
        .text-center { text-align: center; }
        .w-100 { width: 100%; }
        table { border-collapse: collapse; width: 100%; }
    </style>
</head>
<body>

    @include('pdf.tickets.terms-summary', ['transaction' => $transaction])

    @foreach($transaction->tickets as $ticket)
        <div class="page-break"></div>
        
        @php
            // Memanggil file desain berdasarkan nama kategori (contoh: platinum.blade.php)
            $categoryName = strtolower($ticket->ticketCategory->name);
            $designFile = 'pdf.tickets.designs.' . $categoryName;
        @endphp

        @if(View::exists($designFile))
            @include($designFile, ['ticket' => $ticket, 'transaction' => $transaction])
        @else
            @include('pdf.tickets.designs.regular', ['ticket' => $ticket, 'transaction' => $transaction])
        @endif
    @endforeach

</body>
</html>