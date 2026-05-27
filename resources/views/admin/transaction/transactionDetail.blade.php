@extends('layouts.admin')
@section('title', 'Transaction Detail')
@php use App\Enums\TransactionStatusEnum; @endphp

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Transaction Detail</h1>
                <p class="text-sm text-gray-600 mt-1">Invoice: <span class="font-semibold font-mono">{{ $transaction->invoice_code }}</span></p>
            </div>
            <div class="flex gap-2 flex-wrap justify-end">
                {{-- Export PDF --}}
                @if($transaction->transaction_status === TransactionStatusEnum::PAID->value)
                <a href="{{ route('admin.transaction.export-pdf', $transaction->invoice_code) }}" target="_blank"
                    class="px-4 py-2 text-sm bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-semibold">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </a>
                @endif

                {{-- Validate / Reject untuk PENDING --}}
                @if($transaction->transaction_status === TransactionStatusEnum::PENDING->value)
                    <button id="btnValidate" class="px-4 py-2 text-sm bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                        <i class="fas fa-check mr-2"></i>Validate Payment
                    </button>
                    <button id="btnReject" class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                        <i class="fas fa-times mr-2"></i>Reject Payment
                    </button>

                {{-- Refund untuk PAID --}}
                @elseif($transaction->transaction_status === TransactionStatusEnum::PAID->value)
                    <button id="btnRefundCancel" class="px-4 py-2 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold">
                        <i class="fas fa-undo mr-2"></i>Refund
                    </button>

                {{-- Cancel untuk DRAFT --}}
                @elseif($transaction->transaction_status === TransactionStatusEnum::DRAFT->value)
                    <button id="btnRefundCancel" class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                        <i class="fas fa-ban mr-2"></i>Cancel
                    </button>
                @endif

                <a href="/admin/transaction" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Buyer Information --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <i class="fas fa-user-circle mr-2" style="color: #27b4f7;"></i>Buyer Information
            </h2>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fas fa-user text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Name</div><div class="font-semibold text-gray-900">{{ $transaction->buyer_name ?? $transaction->user->name }}</div></div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-phone text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Phone</div><div class="font-semibold text-gray-900">{{ $transaction->buyer_phone ?? '-' }}</div></div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Email</div><div class="font-semibold text-gray-900">{{ $transaction->user->email }}</div></div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">City</div><div class="font-semibold text-gray-900">{{ $transaction->city ?? '-' }}</div></div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Source Info</div><div class="font-semibold text-gray-900">{{ $transaction->source_info ?? '-' }}</div></div>
                </div>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <i class="fas fa-credit-card mr-2 text-green-600"></i>Payment Information
            </h2>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fas fa-file-invoice text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Invoice Code</div><div class="font-mono font-medium text-gray-900">{{ $transaction->invoice_code }}</div></div>
                </div>
                {{-- <div class="flex items-start">
                    <i class="fas fa-credit-card text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Payment Method</div><div class="font-medium text-gray-900">{{ $transaction->payment_method ?? '-' }}</div></div>
                </div> --}}
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-gray-400 mt-1 mr-3 w-4"></i>
                    <div>
                        <div class="text-xs text-gray-500">Status</div>
                        <div class="mt-1">
                            @if($transaction->transaction_status === TransactionStatusEnum::PAID->value)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Paid</span>
                            @elseif($transaction->transaction_status === TransactionStatusEnum::PENDING->value)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fas fa-clock mr-1"></i>Pending Verification</span>
                            @elseif($transaction->transaction_status === TransactionStatusEnum::DRAFT->value)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-edit mr-1"></i>Draft</span>
                            @elseif($transaction->transaction_status === TransactionStatusEnum::FAILED->value)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Failed</span>
                            @elseif($transaction->transaction_status === TransactionStatusEnum::EXPIRED->value)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fas fa-hourglass-end mr-1"></i>Expired</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-calendar-plus text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Created At</div><div class="font-medium text-gray-900">{{ $transaction->created_at->format('d M Y, H:i') }}</div></div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-calendar-check text-gray-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Paid At</div><div class="font-medium text-gray-900">{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, H:i') : '-' }}</div></div>
                </div>
                @if($transaction->rejection_reason)
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-400 mt-1 mr-3 w-4"></i>
                    <div><div class="text-xs text-gray-500">Rejection Reason</div><div class="font-medium text-red-600">{{ $transaction->rejection_reason }}</div></div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Payment Proof --}}
    @if($transaction->payment_proof)
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
            <i class="fas fa-receipt mr-2 text-yellow-500"></i>Bukti Pembayaran
        </h2>
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Bukti Bayar"
                class="max-h-80 rounded-xl border border-gray-200 object-contain cursor-pointer hover:opacity-90 transition"
                onclick="window.open(this.src, '_blank')" title="Klik untuk perbesar">
            <div class="text-sm text-gray-500 mt-2"><i class="fas fa-search-plus mr-1"></i>Klik gambar untuk memperbesar.</div>
        </div>
    </div>
    @endif

    {{-- Order Items --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
            <i class="fas fa-shopping-cart mr-2" style="color: #8b5cf6;"></i>Order Items
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ticket Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Event</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ticket Holders</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaction->transactionItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $item->ticket_category_id ? ($item->ticketCategory->name ?? '-') : 'Online Pass - ' . ($item->onlineTicket->name ?? '') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item->ticket_category_id ? ($item->ticketCategory->event->name ?? '-') : 'Digital Access (Terkoneksi Akun)' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            @if($item->holder_names)
                                <div class="space-y-1">
                                    @foreach($item->holder_names as $i => $name)
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-400">#{{ $i + 1 }}</span>
                                            <span>{{ $name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-sm font-bold text-gray-900 text-right">Total</td>
                        <td colspan="2" class="px-4 py-3 text-sm font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Tickets Generated --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
            <i class="fas fa-ticket-alt mr-2" style="color: #fec401;"></i>Tickets Generated
            @if($transaction->tickets->isEmpty())
                <span class="ml-2 text-xs font-normal text-gray-400">(Tiket akan digenerate setelah pembayaran divalidasi)</span>
            @endif
        </h2>
        @if($transaction->tickets->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ticket Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Holder Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Check-in</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Checked-in At</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaction->tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $ticket->ticket_code }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $ticket->holder_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $ticket->ticketCategory->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($ticket->is_checked_in)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check mr-1"></i>Checked In</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Not Yet</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $ticket->checked_in_at ? $ticket->checked_in_at->format('d M Y, H:i') : '-' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.transaction.export-pdf', $transaction->invoice_code) }}" target="_blank"
                                class="text-purple-600 hover:text-purple-800 mr-3" title="Download E-Ticket PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            {{-- <button class="text-green-600 hover:text-green-800 btn-send-email" data-code="{{ $ticket->ticket_code }}" title="Send Email">
                                <i class="fas fa-envelope"></i>
                            </button> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-ticket-alt text-3xl mb-2 block"></i>
                <p class="text-sm">Belum ada tiket yang digenerate.</p>
            </div>
        @endif
    </div>
@endsection

@section('script')
<script>
const transactionData = {
    invoice_code: '{{ $transaction->invoice_code }}',
    buyer_name: '{{ addslashes($transaction->buyer_name ?? $transaction->user->name) }}',
    event_name: '{{ addslashes($transaction->transactionItems->first()?->ticketCategory?->event?->name ?? ($transaction->transactionItems->first()?->onlineTicket ? "Online Pass - " . $transaction->transactionItems->first()->onlineTicket->name : "-")) }}',
    total_amount: {{ $transaction->total_amount }},
    transaction_status: '{{ $transaction->transaction_status }}'
};

$(document).ready(function() {
    // Send Email
    $(document).on('click', '.btn-send-email', function() {
        const ticketCode = $(this).data('code');
        Swal.fire({
            title: 'Send Ticket Email',
            text: `Send ticket ${ticketCode} to customer email?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Send',
            confirmButtonColor: '#27b4f7'
        }).then((result) => {
            if (result.isConfirmed) {
                Toastify({ text: "Email sent successfully", duration: 2000, gravity: "top", position: "right", style: { background: "#10b981" } }).showToast();
            }
        });
    });

    // Validate Payment
    $('#btnValidate').on('click', function() {
        Swal.fire({
            title: '<span class="font-bold">Validasi Pembayaran</span>',
            html: `<p class="text-gray-600">Apakah Anda yakin ingin memvalidasi pembayaran <strong>${transactionData.invoice_code}</strong>?</p>
                   <p class="text-sm text-gray-500 mt-2">Tiket akan digenerate otomatis dan dikirim ke email pembeli.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Ya, Validasi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/transaction/${transactionData.invoice_code}/validate`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Pembayaran Divalidasi!', text: 'Tiket telah digenerate dan dikirim ke email pembeli.', confirmButtonColor: '#27b4f7', timer: 2500 })
                            .then(() => window.location.href = '/admin/transaction');
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan.', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    });

    // Reject Payment
    $('#btnReject').on('click', function() {
        Swal.fire({
            title: '<span class="font-bold">Tolak Pembayaran</span>',
            html: `<p class="text-gray-600 mb-3">Masukkan alasan penolakan untuk invoice <strong>${transactionData.invoice_code}</strong>:</p>
                   <textarea id="rejectReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai"></textarea>`,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-times mr-2"></i>Tolak Pembayaran',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            preConfirm: () => {
                const reason = $('#rejectReason').val();
                if (!reason.trim()) { Swal.showValidationMessage('Alasan penolakan wajib diisi.'); return false; }
                return { reason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/transaction/${transactionData.invoice_code}/reject`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { reason: result.value.reason },
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Pembayaran Ditolak', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => window.location.href = '/admin/transaction');
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message, confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    });

    // Refund / Cancel
    $('#btnRefundCancel').on('click', function() {
        const isPaid = transactionData.transaction_status === '{{ TransactionStatusEnum::PAID->value }}';
        Swal.fire({
            title: isPaid ? 'Refund Transaction?' : 'Cancel Transaction?',
            html: `<p class="text-gray-600">Invoice: <strong>${transactionData.invoice_code}</strong></p>
                   <p class="text-gray-600">Buyer: <strong>${transactionData.buyer_name}</strong></p>
                   <p class="text-gray-600 mb-3">Amount: <strong>Rp ${transactionData.total_amount.toLocaleString('id-ID')}</strong></p>
                   <textarea id="actionReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="${isPaid ? 'Alasan refund (opsional)' : 'Alasan pembatalan (wajib)'}"></textarea>`,
            showCancelButton: true,
            confirmButtonText: isPaid ? '<i class="fas fa-undo mr-2"></i>Process Refund' : '<i class="fas fa-ban mr-2"></i>Cancel Transaction',
            cancelButtonText: 'Keep Transaction',
            confirmButtonColor: isPaid ? '#f59e0b' : '#ef4444',
            cancelButtonColor: '#6b7280',
            preConfirm: () => {
                const reason = $('#actionReason').val();
                if (!isPaid && !reason.trim()) { Swal.showValidationMessage('Alasan pembatalan wajib diisi.'); return false; }
                return { reason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/transaction/${transactionData.invoice_code}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function() {
                        Swal.fire({ icon: 'success', title: isPaid ? 'Refund Processed!' : 'Transaction Cancelled!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => window.location.href = '/admin/transaction');
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message, confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    });
});
</script>
@endsection
