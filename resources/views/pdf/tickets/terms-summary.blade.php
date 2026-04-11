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
                {{ $transaction->created_at->format('d F Y, H:i') }} WIB
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
            @foreach ($transaction->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticketCategory->name }} - {{ $ticket->ticketCategory->event->name }}</td>
                    <td>{{ $ticket->ticket_code }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="w-100" style="margin-bottom: 10px; text-align: right;">
        <tr>
            <td style="color: #555;">Subtotal</td>
            <td style="width: 180px;">Rp {{ number_format($transaction->total_amount + $transaction->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @if ($transaction->voucher)
        <tr>
            <td style="color: #555;">Voucher ({{ $transaction->voucher->code }})</td>
            <td style="color: #16a34a;">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr style="font-weight: bold; font-size: 16px;">
            <td>Total</td>
            <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h3 style="background: #f0f0f0; padding: 10px;">Syarat & Ketentuan (Terms & Conditions)</h3>
    <p style="font-weight: bold; margin-bottom: 6px;">A. KETENTUAN UMUM & VALIDITAS TIKET</p>
    <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
        <li>Tiket hanya berlaku untuk tanggal, waktu, dan acara yang tertera pada tiket.</li>
        <li>Satu (1) tiket berlaku untuk satu (1) orang.</li>
        <li>Tiket tidak dapat dipindahtangankan tanpa persetujuan resmi dari panitia.</li>
        <li>Tiket yang telah dibeli dianggap sah apabila diperoleh melalui kanal penjualan resmi yang ditunjuk oleh
            panitia.</li>
        <li>Panitia berhak untuk:
            <ol type="a" style="margin-top: 6px;">
                <li>Menolak tiket yang rusak, tidak terbaca, terduplikasi, atau diperoleh secara tidak sah.</li>
                <li>Memproses atau mengajukan hukum, baik perdata atau kriminal kepada pengunjung yang mendapatkan tiket
                    dengan ilegal, termasuk memalsukan dan menggandakan tiket yang sah atau mendapatkan tiket dengan
                    cara yang tidak sesuai prosedur.</li>
            </ol>
        </li>
    </ol>

    <p style="font-weight: bold; margin-bottom: 6px;">B. KEBIJAKAN PEMBELIAN & PENGEMBALIAN DANA</p>
    <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
        <li>Seluruh pembelian tiket bersifat final dan tidak dapat dibatalkan.</li>
        <li>Tiket yang telah dibeli tidak dapat dikembalikan dan tidak dapat ditukar (<i>non-refundable</i> &
            <i>non-exchangeable</i>), kecuali apabila acara dibatalkan secara resmi oleh panitia.</li>
        <li>Panitia tidak bertanggung jawab atas kegagalan proses pembelian tiket yang disebabkan oleh kesalahan
            pengisian data oleh pembeli maupun gangguan jaringan dan perangkat yang berada di luar kendali panitia.</li>
        <li>Apabila acara dibatalkan oleh panitia, mekanisme pengembalian dana akan diinformasikan melalui kanal
            komunikasi resmi PIFF 2026.</li>
        <li>Apabila acara mengalami perubahan jadwal (<i>reschedule</i>), tiket tetap berlaku untuk tanggal pengganti.
        </li>
    </ol>

    <p style="font-weight: bold; margin-bottom: 6px;">C. AKSES & REGISTRASI MASUK</p>
    <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
        <li>Pengunjung wajib menunjukkan tiket dalam bentuk digital (<i>e-ticket</i>) saat proses registrasi.</li>
        <li>Tiket digital (<i>e-ticket</i>) akan ditukarkan dengan tiket gelang yang wajib digunakan selama berada di
            area acara.</li>
        <li>Pengunjung yang tidak dapat menunjukkan tiket gelang tidak diperkenankan memasuki area acara.</li>
        <li>Panitia berhak melakukan pemeriksaan ulang terhadap tiket dan identitas apabila diperlukan.</li>
    </ol>

    <p style="font-weight: bold; margin-bottom: 6px;">D. KETENTUAN SELAMA ACARA</p>
    <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
        <li>Pengunjung wajib menjaga ketertiban dan mematuhi seluruh peraturan yang berlaku selama acara berlangsung.
        </li>
        <li>Dilarang membawa barang terlarang sesuai ketentuan panitia (misalnya senjata tajam, narkotika, minuman
            beralkohol, dan barang berbahaya lainnya).</li>
        <li>Panitia berhak mengeluarkan pengunjung dari area acara apabila melanggar peraturan tanpa kewajiban
            pengembalian dana.</li>
        <li>Tamu bertanggung jawab sepenuhnya atas keamanan semua barang-barang pribadi. Kehilangan barang pribadi bukan
            tanggung jawab panitia.</li>
        <li>Dengan membeli tiket, pengunjung memberikan persetujuan untuk didokumentasikan (foto/video) dan digunakan
            untuk kepentingan publikasi acara.</li>
    </ol>

    <p style="font-weight: bold; margin-bottom: 6px;">E. KEJADIAN KAHAR (FORCE MAJEURE)</p>
    <ol style="font-size: 14px; line-height: 1.5; margin-top: 0;">
        <li>Dalam hal terjadi kejadian kahar (<i>Force Majeure</i>), Panitia berhak untuk membatalkan, menunda, mengubah
            jadwal, memindahkan lokasi, atau menyesuaikan format acara tanpa kewajiban memberikan kompensasi tambahan di
            luar kebijakan yang ditentukan Panitia.</li>
        <li>Panitia tidak bertanggung jawab atas kerugian tidak langsung yang mungkin timbul akibat perubahan atau
            pembatalan acara yang disebabkan oleh <i>Force Majeure</i>.</li>
        <li>Informasi resmi terkait perubahan akibat <i>Force Majeure</i> akan diumumkan melalui kanal komunikasi resmi
            PIFF 2026.</li>
    </ol>
</div>
