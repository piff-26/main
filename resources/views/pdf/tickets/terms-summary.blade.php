<div style="padding: 20px; border: 2px solid #000;">
    <h1 class="text-center" style="margin-bottom: 0;">INVOICE PEMBELIAN</h1>
    <h3 class="text-center" style="margin-top: 5px; color: #555;">{{ $transaction->invoice_code }}</h3>
    
    <hr style="margin: 20px 0;">

    <table class="w-100" style="margin-bottom: 30px;">
        <tr>
            <td style="width: 50%;">
                <strong>Nama Pembeli:</strong><br>
                {{ $transaction->buyer_name }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Tanggal Transaksi:</strong><br>
                {{ $transaction->created_at->format('d F Y, H:i') }}
            </td>
        </tr>
    </table>

    <h3 style="background: #f0f0f0; padding: 10px;">Ringkasan Tiket</h3>
    <table class="w-100" border="1" style="margin-bottom: 30px; text-align: left;" cellpadding="8">
        <thead>
            <tr style="background: #ddd;">
                <th>Kategori</th>
                <th>Kode Tiket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticketCategory->name }} - {{ $ticket->ticketCategory->event->name }}</td>
                <td>{{ $ticket->ticket_code }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="background: #f0f0f0; padding: 10px;">Syarat & Ketentuan (Terms & Conditions)</h3>
    <ol style="font-size: 14px; line-height: 1.5;">
        <li>E-Ticket ini adalah bukti sah pembelian tiket.</li>
        <li>Satu <i>barcode/QR Code</i> hanya berlaku untuk satu kali <i>scan</i> di pintu masuk.</li>
        <li>Panitia berhak menolak pengunjung jika tiket terbukti telah dipindai sebelumnya.</li>
        <li>Harap persiapkan identitas asli (KTM/KTP) saat penukaran tiket atau <i>check-in</i>.</li>
        <li>Tiket yang sudah dibeli tidak dapat dikembalikan (<i>non-refundable</i>).</li>
    </ol>
</div>