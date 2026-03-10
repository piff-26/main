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
                    <option value="PIFF Day 1">PIFF Day 1</option>
                    <option value="PIFF Day 2">PIFF Day 2</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                <select id="filterPayment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Methods</option>
                    <option value="QRIS">QRIS</option>
                    <option value="BCA">BCA</option>
                    <option value="Gopay">Gopay</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                <select id="filterCity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Cities</option>
                    <option value="Surabaya">Surabaya</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Bandung">Bandung</option>
                </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script>
const transactionsData = [
    {
        invoice_code: 'INV-2026-001',
        event_name: 'PIFF Day 1',
        buyer_name: 'John Doe',
        email: 'john@example.com',
        buyer_phone: '081234567890',
        city: 'Surabaya',
        total_amount: 20000,
        voucher_code: '-',
        payment_method: 'QRIS',
        transaction_status: 'paid',
        created_at: '2026-05-01 10:30',
        paid_at: '2026-05-01 10:35'
    },
    {
        invoice_code: 'INV-2026-002',
        event_name: 'PIFF Day 2',
        buyer_name: 'Jane Smith',
        email: 'jane@example.com',
        buyer_phone: '081234567891',
        city: 'Jakarta',
        total_amount: 79000,
        voucher_code: '-',
        payment_method: 'BCA',
        transaction_status: 'paid',
        created_at: '2026-05-01 11:15',
        paid_at: '2026-05-01 11:20'
    },
    {
        invoice_code: 'INV-2026-003',
        event_name: 'PIFF Day 2',
        buyer_name: 'Bob Wilson',
        email: 'bob@example.com',
        buyer_phone: '081234567892',
        city: 'Bandung',
        total_amount: 59000,
        voucher_code: '-',
        payment_method: 'Gopay',
        transaction_status: 'draft',
        created_at: '2026-05-01 12:00',
        paid_at: '-'
    },
    {
        invoice_code: 'INV-2026-004',
        event_name: 'PIFF Day 1',
        buyer_name: 'Alice Brown',
        email: 'alice@example.com',
        buyer_phone: '081234567893',
        city: 'Surabaya',
        total_amount: 20000,
        voucher_code: '-',
        payment_method: 'QRIS',
        transaction_status: 'failed',
        created_at: '2026-05-01 13:30',
        paid_at: '-'
    }
];

$(document).ready(function() {
    const table = $('#transactionTable').DataTable({
        data: transactionsData,
        columns: [
            { data: 'invoice_code', render: (data) => `<span class="font-medium text-gray-900">${data}</span>` },
            { data: 'event_name' },
            { data: 'buyer_name' },
            { data: 'email' },
            { data: 'city' },
            { data: 'total_amount', render: (data) => `<span class="font-semibold">Rp ${data.toLocaleString('id-ID')}</span>` },
            { data: 'payment_method' },
            { 
                data: 'transaction_status', 
                render: (data) => {
                    const badges = {
                        paid: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>',
                        draft: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>',
                        expired: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>',
                        failed: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Failed</span>'
                    };
                    return badges[data] || data;
                }
            },
            { data: 'created_at' },
            { 
                data: null, 
                render: (data) => `
                    <a href="/admin/transaction/detail/${data.invoice_code}" class="text-blue-600 hover:text-blue-800 mr-2" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button class="text-green-600 hover:text-green-800 mr-2 btn-export-pdf" data-invoice="${data.invoice_code}" title="Export PDF">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                    ${data.transaction_status === 'paid' ? `
                        <button class="text-red-600 hover:text-red-800 btn-refund" data-invoice="${data.invoice_code}" title="Refund">
                            <i class="fas fa-undo"></i>
                        </button>
                    ` : data.transaction_status !== 'failed' && data.transaction_status !== 'expired' ? `
                        <button class="text-red-600 hover:text-red-800 btn-cancel" data-invoice="${data.invoice_code}" title="Cancel">
                            <i class="fas fa-ban"></i>
                        </button>
                    ` : ''}
                `
            }
        ],
        pageLength: 10,
        order: [[8, 'desc']],
        drawCallback: function() {
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
                                <p><strong>Payment:</strong> ${transaction.payment_method}</p>
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
                Swal.fire({
                    icon: 'success',
                    title: 'Refund Processed!',
                    confirmButtonColor: '#27b4f7',
                    timer: 2000
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
                Swal.fire({
                    icon: 'success',
                    title: 'Transaction Cancelled!',
                    confirmButtonColor: '#27b4f7',
                    timer: 2000
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
                row.payment_method,
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

        table.columns().search('');
        
        if (event) table.column(1).search(event);
        if (status) table.column(7).search(status);
        if (payment) table.column(6).search(payment);
        if (city) table.column(4).search(city);
        
        table.draw();
    });

    $('#btnResetFilter').on('click', function() {
        $('#filterEvent, #filterStatus, #filterPayment, #filterCity').val('');
        table.columns().search('').draw();
    });
});
</script>
@endsection