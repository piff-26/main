@extends('layouts.admin')
@section('title', 'Manage Transaction')

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Transaction Detail</h1>
                <p class="text-sm text-gray-600 mt-1">Invoice: <span id="invoiceCode" class="font-semibold">INV-2026-001</span></p>
            </div>
            <div class="flex gap-2">
                <button id="btnRefundCancel" class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                    <i class="fas fa-undo mr-2"></i><span id="refundCancelText">Refund</span>
                </button>
                <a href="/admin/transaction" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Buyer Information --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <i class="fas fa-user-circle mr-2" style="color: #27b4f7;"></i>Buyer Information
            </h2>
            <div class="space-y-3" id="buyerInfo">
                <div class="flex items-start">
                    <i class="fas fa-user text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-600">Name</div>
                        <div class="text-base font-semibold text-gray-900">John Doe</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-phone text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-600">Phone</div>
                        <div class="text-base font-semibold text-gray-900">081234567890</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-600">Email</div>
                        <div class="text-base font-semibold text-gray-900">john@example.com</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-600">City</div>
                        <div class="text-base font-semibold text-gray-900">Surabaya</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-600">Source Info</div>
                        <div class="text-base font-semibold text-gray-900">Website</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <i class="fas fa-credit-card mr-2 text-green-600"></i>Payment Information
            </h2>
            <div class="space-y-3" id="paymentInfo">
                <div class="flex items-start">
                    <i class="fas fa-file-invoice text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-500">Invoice Code</div>
                        <div class="text-base font-medium text-gray-900">INV-2026-001</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-credit-card text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-500">Payment Method</div>
                        <div class="text-base font-medium text-gray-900">QRIS</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-500">Status</div>
                        <div><span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Paid</span></div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-calendar-plus text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-500">Created At</div>
                        <div class="text-base font-medium text-gray-900">2026-05-01 10:30:00</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-calendar-check text-gray-400 mt-1 mr-3"></i>
                    <div>
                        <div class="text-sm text-gray-500">Paid At</div>
                        <div class="text-base font-medium text-gray-900">2026-05-01 10:35:00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
            <i class="fas fa-shopping-cart mr-2" style="color: #8b5cf6;"></i>Order Items
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Ticket Category</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Event</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Quantity</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Price</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="orderItems">
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">Regular</td>
                        <td class="px-4 py-3 text-sm text-gray-900">PIFF Day 1</td>
                        <td class="px-4 py-3 text-sm text-gray-900">1</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Rp 20,000</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp 20,000</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50" id="orderSummary">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-base font-bold text-gray-900 text-right">Total</td>
                        <td class="px-4 py-3 text-base font-bold text-gray-900">Rp 20,000</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Tickets Generated --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
            <i class="fas fa-ticket-alt mr-2" style="color: #fec401;"></i>Tickets Generated
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Ticket Code</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Category</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Check-in Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Checked-in At</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="ticketsList">
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono text-gray-900">TKT-2026-001-001</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Regular</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Checked In</span></td>
                        <td class="px-4 py-3 text-sm text-gray-500">2026-05-29 09:45:00</td>
                        <td class="px-4 py-3">
                            <button class="text-blue-600 hover:text-blue-800 mr-2" title="Download QR">
                                <i class="fas fa-qrcode"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800" title="Send Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Fade in animation
    $('.bg-white').hide().fadeIn(600);
    
    // Refund/Cancel Button
    $('#btnRefundCancel').on('click', function() {
        const status = 'paid'; // From dummy data
        const action = status === 'paid' ? 'refund' : 'cancel';
        const actionText = action === 'refund' ? 'refund' : 'cancel';
        const actionTitle = action === 'refund' ? 'Refund Transaction' : 'Cancel Transaction';
        
        Swal.fire({
            title: `<span class="font-bold">${actionTitle}</span>`,
            html: `
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full ${action === 'refund' ? 'bg-yellow-100' : 'bg-red-100'} mb-4">
                        <i class="fas ${action === 'refund' ? 'fa-undo text-yellow-600' : 'fa-ban text-red-600'} text-xl"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Are you sure you want to ${actionText} transaction <strong>INV-2026-001</strong>?</p>
                    
                    <div class="bg-blue-50 p-4 rounded-lg mb-4 text-left">
                        <h4 class="font-semibold text-blue-800 mb-2">Transaction Details:</h4>
                        <div class="space-y-1 text-sm text-blue-700">
                            <p><strong>Buyer:</strong> John Doe</p>
                            <p><strong>Event:</strong> PIFF Day 1</p>
                            <p><strong>Amount:</strong> Rp 20,000</p>
                            <p><strong>Payment:</strong> QRIS</p>
                            <p><strong>Status:</strong> Paid</p>
                        </div>
                    </div>
                    
                    <div class="${action === 'refund' ? 'bg-yellow-50' : 'bg-red-50'} p-3 rounded-lg mb-4">
                        <p class="text-sm ${action === 'refund' ? 'text-yellow-800' : 'text-red-800'}">
                            <i class="fas ${action === 'refund' ? 'fa-info-circle' : 'fa-exclamation-triangle'} mr-2"></i>
                            ${action === 'refund' 
                                ? 'This will process a refund and notify the customer.' 
                                : 'This action cannot be undone. The customer will be notified.'}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">${action === 'refund' ? 'Refund' : 'Cancellation'} Reason</label>
                        <textarea id="actionReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter reason for ${actionText} ${action === 'cancel' ? '(required)' : '(optional)'}" ${action === 'cancel' ? 'required' : ''}></textarea>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: `<i class="fas ${action === 'refund' ? 'fa-undo' : 'fa-ban'} mr-2"></i>${action === 'refund' ? 'Process Refund' : 'Cancel Transaction'}`,
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Keep Transaction',
            confirmButtonColor: action === 'refund' ? '#f59e0b' : '#ef4444',
            cancelButtonColor: '#6b7280',
            customClass: {
                confirmButton: 'font-semibold',
                cancelButton: 'font-semibold'
            },
            preConfirm: () => {
                const reason = $('#actionReason').val();
                if (action === 'cancel' && !reason.trim()) {
                    Swal.showValidationMessage('Please provide a reason for cancellation');
                    return false;
                }
                return { reason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: action === 'refund' ? 'Refund Processed!' : 'Transaction Cancelled!',
                    confirmButtonColor: '#27b4f7',
                    timer: 2000
                }).then(() => {
                    window.location.href = '/admin/transaction';
                });
            }
        });
    });
});
</script>
@endsection