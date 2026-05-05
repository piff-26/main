<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Ticket {{ $transaction->invoice_code }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Margin 0 khusus untuk halaman tiket */
        .ticket-page {
            page-break-before: always;
        }

        @page ticket {
            margin: 0;
            background-color: #000;
        }

        .text-center {
            text-align: center;
        }

        .w-100 {
            width: 100%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body>

    {{-- Syarat & Ketentuan --}}
    @include('pdf.tickets.terms-summary', ['transaction' => $transaction])

    {{-- 2, 3, dst: Lembaran Tiket --}}
    @foreach ($transaction->tickets as $ticket)
        <div class="ticket-page" style="page: ticket;">
            @include('pdf.tickets.designs.ticket', ['ticket' => $ticket, 'transaction' => $transaction, 'bgImageSrc' => $bgImageSrc ?? ''])
        </div>
    @endforeach

</body>

</html>
