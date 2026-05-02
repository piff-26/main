@extends('layouts.admin')
@section('title', 'Broadcast Email')

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800">Broadcast Email</h1>
            <p class="text-sm text-gray-600 mt-1">Kirim pesan khusus ke peserta berdasarkan filter event, status transaksi, atau kota.</p>
        </div>
    </div>

    {{-- Form Area --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Pesan Email</h2>
        <form action="{{ route('admin.email.send') }}" method="POST" id="formBroadcast">
            @csrf
            <input type="hidden" name="emails" id="hiddenEmails">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Masukkan judul email" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                <textarea name="message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Tuliskan pesan broadcast disini..." required></textarea>
                <p class="text-xs text-gray-500 mt-1">Pesan ini akan dikirim ke email user yang dipilih di tabel bawah.</p>
            </div>

            <div class="flex items-center gap-4 mt-4">
                <button type="submit" id="btnSendEmail" class="px-6 py-2 text-white rounded-lg hover:shadow-md transition font-semibold" style="background-color: #27b4f7;">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Broadcast
                </button>
                <span class="text-sm font-semibold text-gray-700" id="selectedCountText">0 penerima dipilih</span>
            </div>
        </form>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Pilih Penerima</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Transaksi</label>
                <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Status</option>
                    @foreach(App\Enums\TransactionStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                    @endforeach
                </select>
            </div>
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
        <div class="mt-4 flex gap-2">
            <button id="btnApplyFilter" class="px-4 py-2 text-white rounded-lg hover:shadow-md transition font-semibold" style="background-color: #27b4f7;">
                <i class="fas fa-filter mr-2"></i>Apply Filters
            </button>
            <button id="btnResetFilter" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                <i class="fas fa-redo mr-2"></i>Reset
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
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                <label for="selectAllCheckbox" class="ml-2 text-sm font-semibold text-gray-700 cursor-pointer">Pilih Semua (Terfilter)</label>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="usersTable" class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-10">Pilih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pembeli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script>
const transactionsData = {!! json_encode($transactions) !!};
let selectedEmails = new Set();

function showLoading(text) {
    document.getElementById('loadingText').innerText = text || 'Memproses...';
    document.getElementById('loadingOverlay').classList.remove('hidden');
}
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

function updateSelectedCount() {
    $('#selectedCountText').text(`${selectedEmails.size} penerima dipilih`);
    $('#hiddenEmails').val(Array.from(selectedEmails).join(','));
}

$(document).ready(function() {
    const table = $('#usersTable').DataTable({
        data: transactionsData,
        columns: [
            { 
                data: 'email',
                orderable: false,
                render: function(data, type, row) {
                    if (type === 'display') {
                        const isChecked = selectedEmails.has(data) ? 'checked' : '';
                        return `<input type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer" value="${data}" ${isChecked}>`;
                    }
                    return data;
                }
            },
            { data: 'buyer_name' },
            { data: 'email' },
            { data: 'event_name' },
            { data: 'city' },
            { 
                data: 'transaction_status', 
                render: (data) => {
                    const badges = {
                        paid:    '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>',
                        pending: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pending</span>',
                        draft:   '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>',
                        expired: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>',
                        failed:  '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Failed</span>'
                    };
                    return badges[data] || data;
                }
            }
        ],
        pageLength: 10,
        drawCallback: function() {
            // Re-bind click event on checkboxes whenever table draws
            $('.row-checkbox').off('change').on('change', function() {
                const email = $(this).val();
                if (this.checked) {
                    selectedEmails.add(email);
                } else {
                    selectedEmails.delete(email);
                }
                updateSelectedCount();
                checkSelectAllStatus();
            });
            checkSelectAllStatus();
        }
    });

    // Handle Select All logic
    $('#selectAllCheckbox').on('change', function() {
        const isChecked = this.checked;
        
        // Get all data currently visible/filtered by DataTables
        const filteredData = table.rows({ search: 'applied' }).data().toArray();
        
        filteredData.forEach(row => {
            if (row.email) {
                if (isChecked) {
                    selectedEmails.add(row.email);
                } else {
                    selectedEmails.delete(row.email);
                }
            }
        });
        
        // Force table redraw to update checkboxes visually
        table.rows().invalidate().draw(false);
        updateSelectedCount();
    });

    function checkSelectAllStatus() {
        // Prevent ReferenceError during initial draw by fetching the instance safely
        if (!$.fn.DataTable.isDataTable('#usersTable')) return;
        const dt = $('#usersTable').DataTable();
        const filteredData = dt.rows({ search: 'applied' }).data().toArray();
        let allChecked = true;
        let count = 0;
        
        if (filteredData.length === 0) {
            allChecked = false;
        } else {
            for (let i = 0; i < filteredData.length; i++) {
                if (filteredData[i].email) {
                    count++;
                    if (!selectedEmails.has(filteredData[i].email)) {
                        allChecked = false;
                        break;
                    }
                }
            }
        }
        
        $('#selectAllCheckbox').prop('checked', count > 0 && allChecked);
    }

    // Filters
    $('#btnApplyFilter').on('click', function() {
        const event = $('#filterEvent').val();
        const status = $('#filterStatus').val();
        const city = $('#filterCity').val();

        table.columns().search('');
        
        if (event) table.column(3).search(event);
        if (city) table.column(4).search(city);
        if (status) table.column(5).search(status);
        
        table.draw();
    });

    $('#btnResetFilter').on('click', function() {
        $('#filterEvent, #filterStatus, #filterCity').val('');
        table.columns().search('').draw();
    });

    // Form Submission
    $('#formBroadcast').on('submit', function(e) {
        if (selectedEmails.size === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Ada Penerima',
                text: 'Silakan pilih minimal 1 penerima dari tabel di bawah sebelum mengirim.',
                confirmButtonColor: '#27b4f7'
            });
            return;
        }
        
        showLoading('Mengirim broadcast email...');
    });
});
</script>
@endsection
