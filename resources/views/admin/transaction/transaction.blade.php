@extends('layouts.admin')
@section('title', 'Manage Transaction')

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800">Manage Transactions</h1>
            <p class="text-sm text-gray-600 mt-1">View and manage all ticket transactions</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Event</label>
                <select id="filterEvent" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event }}">{{ $event }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Status</option>
                    @foreach(App\Enums\TransactionStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                <select id="filterPayment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Methods</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                <select id="filterCity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                <input type="date" id="filterDateStart" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                <input type="date" id="filterDateEnd" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button id="btnApplyFilter" class="px-4 py-2 text-white rounded-lg hover:shadow-md transition font-semibold" style="background-color: #27b4f7;">
                <i class="fas fa-filter mr-2"></i>Apply Filters
            </button>
            <button id="btnResetFilter" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                <i class="fas fa-redo mr-2"></i>Reset
            </button>
            <button id="btnExport" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition ml-auto font-semibold">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="hidden fixed inset-0 z-50 flex flex-col items-center justify-center" style="background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);">
        <div class="bg-white rounded-2xl px-10 py-8 flex flex-col items-center shadow-2xl">
            <div class="w-14 h-14 rounded-full border-4 border-gray-200 border-t-blue-500 animate-spin mb-4"></div>
            <p id="loadingText" class="text-gray-700 font-semibold text-base">Memproses...</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="transactionTable" class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buyer Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voucher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script>
const STATUS_PAID = '{{ \App\Enums\TransactionStatusEnum::PAID->value }}';
const STATUS_PENDING = '{{ \App\Enums\TransactionStatusEnum::PENDING->value }}';
const STATUS_DRAFT = '{{ \App\Enums\TransactionStatusEnum::DRAFT->value }}';
const STATUS_FAILED = '{{ \App\Enums\TransactionStatusEnum::FAILED->value }}';
const STATUS_EXPIRED = '{{ \App\Enums\TransactionStatusEnum::EXPIRED->value }}';
const transactionsData = {!! json_encode($transactions) !!};

function showLoading(text) {
    document.getElementById('loadingText').innerText = text || 'Memproses...';
    document.getElementById('loadingOverlay').classList.remove('hidden');
}
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

$(document).ready(function() {
    // Custom sorting for currency
    $.fn.dataTable.ext.type.order['currency-pre'] = function(data) {
        return parseFloat(data.replace(/[^0-9,-]/g, '').replace(/\./g, '').replace(',', '.')) || 0;
    };

    const table = $('#transactionTable').DataTable({
        data: transactionsData,
        columns: [
            { data: 'invoice_code', render: (data) => `<span class="font-medium text-gray-900">${data}</span>` },
            { data: 'event_name' },
            { data: 'buyer_name' },
            { data: 'email' },
            { data: 'city' },
            { data: 'total_amount', render: (data) => `<span class="font-semibold">Rp ${data.toLocaleString('id-ID')}</span>` },
            { data: 'voucher_code', render: (data) => data === '-' ? '<span class="text-gray-400">-</span>' : `<span class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800">${data}</span>` },
            { 
                data: 'transaction_status', 
                render: (data) => {
                    const badges = {
                        paid:    '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>',
                        pending: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pending Verification</span>',
                        draft:   '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>',
                        expired: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>',
                        failed:  '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Failed</span>'
                    };
                    return badges[data] || data;
                }
            },
            { data: 'created_at' },
            { data: 'paid_at', render: (data) => data === '-' ? '<span class="text-gray-400">-</span>' : data },
            { 
                data: null,
                orderable: false,
                render: (data) => `
                    <a href="/admin/transaction/detail/${data.invoice_code}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition font-semibold" title="View Details">
                        <i class="fas fa-eye"></i> View
                    </a>
                    ${data.transaction_status === STATUS_PAID ? `<button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg transition font-semibold btn-export-pdf" data-invoice="${data.invoice_code}" title="Export PDF"><i class="fas fa-file-pdf"></i> PDF</button>` : ''}
                `
            },
            {
                data: null,
                orderable: false,
                render: (data) => {
                    if (data.transaction_status === STATUS_PENDING) {
                        return `
                            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-green-500 text-white hover:bg-green-600 rounded-lg transition font-semibold btn-quick-validate" data-invoice="${data.invoice_code}">
                                <i class="fas fa-check"></i> Validate
                            </button>
                            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-red-500 text-white hover:bg-red-600 rounded-lg transition font-semibold btn-quick-reject" data-invoice="${data.invoice_code}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        `;
                    } else if (data.transaction_status === STATUS_PAID) {
                        return `
                            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-yellow-500 text-white hover:bg-yellow-600 rounded-lg transition font-semibold btn-refund" data-invoice="${data.invoice_code}">
                                <i class="fas fa-undo"></i> Refund
                            </button>
                        `;
                    } else if (data.transaction_status === STATUS_DRAFT) {
                        return `
                            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-red-500 text-white hover:bg-red-600 rounded-lg transition font-semibold btn-cancel" data-invoice="${data.invoice_code}">
                                <i class="fas fa-ban"></i> Cancel
                            </button>
                        `;
                    }
                    return '<span class="text-gray-400 text-xs">-</span>';
                }
            }
        ],
        columnDefs: [
            { targets: 5, type: 'currency' }
        ],
        pageLength: 10,
        order: [[8, 'desc']],
        drawCallback: function() {
            $('.btn-quick-validate').off('click').on('click', function() {
                const invoice = $(this).data('invoice');
                Swal.fire({
                    title: 'Validasi Pembayaran?',
                    text: `Invoice ${invoice} akan disetujui dan tiket digenerate.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-check mr-1"></i>Ya, Validasi',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/transaction/${invoice}/validate`,
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            beforeSend: () => showLoading('Memvalidasi pembayaran...'),
                            success: () => { hideLoading(); Swal.fire({ icon: 'success', title: 'Divalidasi!', timer: 1500, showConfirmButton: false }).then(() => location.reload()); },
                            error: (xhr) => { hideLoading(); Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message, confirmButtonColor: '#ef4444' }); }
                        });
                    }
                });
            });

            $('.btn-quick-reject').off('click').on('click', function() {
                const invoice = $(this).data('invoice');
                Swal.fire({
                    title: 'Tolak Pembayaran?',
                    html: `<textarea id="quickRejectReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg mt-2" placeholder="Alasan penolakan (wajib)"></textarea>`,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-times mr-1"></i>Tolak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    preConfirm: () => {
                        const reason = $('#quickRejectReason').val();
                        if (!reason.trim()) { Swal.showValidationMessage('Alasan wajib diisi.'); return false; }
                        return { reason };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/transaction/${invoice}/reject`,
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: { reason: result.value.reason },
                            beforeSend: () => showLoading('Menolak pembayaran...'),
                            success: () => { hideLoading(); Swal.fire({ icon: 'success', title: 'Ditolak!', timer: 1500, showConfirmButton: false }).then(() => location.reload()); },
                            error: (xhr) => { hideLoading(); Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message, confirmButtonColor: '#ef4444' }); }
                        });
                    }
                });
            });

            $('.btn-export-pdf').off('click').on('click', function() {
                const invoice = $(this).data('invoice');
                window.open(`/admin/transaction/${invoice}/export-pdf`, '_blank');
            });
            
            $('.btn-refund').off('click').on('click', function() {
                const invoice = $(this).data('invoice');
                refundTransaction(invoice);
            });
            
            $('.btn-cancel').off('click').on('click', function() {
                const invoice = $(this).data('invoice');
                cancelTransaction(invoice);
            });
        }
    });

    function refundTransaction(invoiceCode) {
        const transaction = transactionsData.find(t => t.invoice_code === invoiceCode);
        
        Swal.fire({
            title: '<span class="font-bold">Refund Transaction</span>',
            html: `
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-undo text-yellow-600 text-xl"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Are you sure you want to refund transaction <strong>${invoiceCode}</strong>?</p>
                    ${transaction ? `
                        <div class="bg-blue-50 p-4 rounded-lg mb-4 text-left">
                            <h4 class="font-semibold text-blue-800 mb-2">Transaction Details:</h4>
                            <div class="space-y-1 text-sm text-blue-700">
                                <p><strong>Buyer:</strong> ${transaction.buyer_name}</p>
                                <p><strong>Event:</strong> ${transaction.event_name}</p>
                                <p><strong>Amount:</strong> Rp ${transaction.total_amount.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    ` : ''}
                    <div class="bg-yellow-50 p-3 rounded-lg mb-4">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            This will cancel the transaction and process a refund. The customer will be notified.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Refund Reason</label>
                        <textarea id="refundReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter reason for refund (optional)"></textarea>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-undo mr-2"></i>Process Refund',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            customClass: {
                confirmButton: 'font-semibold',
                cancelButton: 'font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/transaction/${invoiceCode}`,
                    method: 'DELETE',
                    beforeSend: () => showLoading('Memproses refund...'),
                    success: function() {
                        hideLoading();
                        Swal.fire({ icon: 'success', title: 'Refund Processed!', confirmButtonColor: '#27b4f7', timer: 2000 }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        hideLoading();
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to process refund', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    }
    
    function cancelTransaction(invoiceCode) {
        const transaction = transactionsData.find(t => t.invoice_code === invoiceCode);
        
        Swal.fire({
            title: '<span class="font-bold">Cancel Transaction</span>',
            html: `
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-ban text-red-600 text-xl"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Are you sure you want to cancel transaction <strong>${invoiceCode}</strong>?</p>
                    ${transaction ? `
                        <div class="bg-blue-50 p-4 rounded-lg mb-4 text-left">
                            <h4 class="font-semibold text-blue-800 mb-2">Transaction Details:</h4>
                            <div class="space-y-1 text-sm text-blue-700">
                                <p><strong>Buyer:</strong> ${transaction.buyer_name}</p>
                                <p><strong>Event:</strong> ${transaction.event_name}</p>
                                <p><strong>Amount:</strong> Rp ${transaction.total_amount.toLocaleString('id-ID')}</p>
                                <p><strong>Status:</strong> ${transaction.transaction_status}</p>
                            </div>
                        </div>
                    ` : ''}
                    <div class="bg-red-50 p-3 rounded-lg mb-4">
                        <p class="text-sm text-red-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            This action cannot be undone. The customer will be notified of the cancellation.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cancellation Reason</label>
                        <textarea id="cancelReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter reason for cancellation (required)" required></textarea>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-ban mr-2"></i>Cancel Transaction',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Keep Transaction',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            customClass: {
                confirmButton: 'font-semibold',
                cancelButton: 'font-semibold'
            },
            preConfirm: () => {
                const reason = $('#cancelReason').val();
                if (!reason.trim()) {
                    Swal.showValidationMessage('Please provide a reason for cancellation');
                    return false;
                }
                return { reason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/transaction/${invoiceCode}`,
                    method: 'DELETE',
                    beforeSend: () => showLoading('Membatalkan transaksi...'),
                    success: function() {
                        hideLoading();
                        Swal.fire({ icon: 'success', title: 'Transaction Cancelled!', confirmButtonColor: '#27b4f7', timer: 2000 }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        hideLoading();
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to cancel transaction', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    }

    $('#btnExport').on('click', function() {
        const currentData = table.rows().data().toArray();
        
        if (currentData.length === 0) {
            Toastify({
                text: "No data to export",
                duration: 2000,
                gravity: "top",
                position: "right",
                style: { background: "#ef4444", color: "#fff" }
            }).showToast();
            return;
        }
        
        const headers = ['Invoice', 'Event', 'Buyer', 'Email', 'Phone', 'City', 'Total', 'Payment', 'Status', 'Created'];
        const csvContent = [
            headers.join(','),
            ...currentData.map(row => [
                row.invoice_code,
                row.event_name,
                row.buyer_name,
                row.email,
                row.buyer_phone,
                row.city,
                row.total_amount,
                row.transaction_status,
                row.created_at
            ].map(field => `"${field}"`).join(','))
        ].join('\n');
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `transactions_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
        
        Toastify({
            text: `Exported ${currentData.length} transactions`,
            duration: 3000,
            gravity: "top",
            position: "right",
            style: { background: "#10b981", color: "#fff" }
        }).showToast();
    });

    // Filter functionality
    $('#btnApplyFilter').on('click', function() {
        const event = $('#filterEvent').val();
        const status = $('#filterStatus').val();
        const payment = $('#filterPayment').val();
        const city = $('#filterCity').val();
        const dateStart = $('#filterDateStart').val();
        const dateEnd = $('#filterDateEnd').val();

        table.columns().search('');
        
        if (event) table.column(1).search(event);
        if (status) table.column(7).search(status);
        if (city) table.column(4).search(city);
        
        // Date range filter
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const createdAt = data[8]; // Created At column
            if (!dateStart && !dateEnd) return true;
            
            const rowDate = new Date(createdAt);
            const start = dateStart ? new Date(dateStart) : null;
            const end = dateEnd ? new Date(dateEnd) : null;
            
            if (start && end) return rowDate >= start && rowDate <= end;
            if (start) return rowDate >= start;
            if (end) return rowDate <= end;
            return true;
        });
        
        table.draw();
    });

    $('#btnResetFilter').on('click', function() {
        $('#filterEvent, #filterStatus, #filterPayment, #filterCity, #filterDateStart, #filterDateEnd').val('');
        $.fn.dataTable.ext.search.pop();
        table.columns().search('').draw();
    });
});
</script>
@endsection