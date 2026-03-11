@extends('layouts.admin')
@section('title', 'Category')

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Ticket Categories</h1>
                <p class="text-sm text-gray-600 mt-1">Create and manage ticket categories for events</p>
            </div>
            <button id="btnCreateCategory" class="px-4 py-2 text-white rounded-lg hover:shadow-md transition font-semibold" style="background-color: #27b4f7;">
                <i class="fas fa-plus mr-2"></i>Create Category
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter by Event</label>
                <select id="filterEvent" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">All Events</option>
                    @foreach($events as $id => $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button id="btnResetFilter" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="categoryTable" class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sold</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remaining</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
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
const categoriesData = {!! json_encode($categories->map(function($cat) {
    return [
        'id' => $cat->id,
        'event' => $cat->event->name,
        'eventId' => $cat->event_id,
        'name' => $cat->name,
        'price' => $cat->price,
        'quota' => $cat->quota,
        'sold_count' => $cat->sold_count
    ];
})) !!};

const events = {!! json_encode($categories->pluck('event')->unique('id')->map(function($event) {
    return ['id' => $event->id, 'name' => $event->name];
})->values()) !!};

$(document).ready(function() {
    const table = $('#categoryTable').DataTable({
        data: categoriesData,
        columns: [
            { data: 'event' },
            { 
                data: 'name',
                render: (data) => `<span class="font-semibold text-gray-900">${data}</span>`
            },
            { 
                data: 'price',
                render: (data) => `<span class="font-bold" style="color: #27b4f7;">Rp ${data.toLocaleString('id-ID')}</span>`
            },
            { 
                data: 'quota',
                render: (data) => `<span class="font-semibold">${data}</span>`
            },
            { 
                data: 'sold_count',
                render: (data) => `<span class="font-semibold text-green-600">${data}</span>`
            },
            { 
                data: null,
                render: (data) => {
                    const remaining = data.quota - data.sold_count;
                    return `<span class="font-semibold text-gray-700">${remaining}</span>`;
                }
            },
            { 
                data: null,
                render: (data) => {
                    const percentage = ((data.sold_count / data.quota) * 100).toFixed(1);
                    const color = percentage >= 80 ? '#10b981' : percentage >= 50 ? '#fec401' : '#27b4f7';
                    return `
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all" style="width: ${percentage}%; background-color: ${color};"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-600">${percentage}%</span>
                        </div>
                    `;
                }
            },
            { 
                data: null,
                render: (data) => `
                    <button class="text-green-600 hover:text-green-800 mr-2 btn-edit" data-id="${data.id}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="hover:text-red-800 btn-delete" style="color: #ff362d;" data-id="${data.id}" data-name="${data.name}" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                `
            }
        ],
        pageLength: 10,
        order: [[0, 'asc']],
        drawCallback: function() {
            $('.btn-edit').off('click').on('click', function() {
                const catId = $(this).data('id');
                editCategory(catId);
            });
            
            $('.btn-delete').off('click').on('click', function() {
                const catId = $(this).data('id');
                const catName = $(this).data('name');
                deleteCategory(catId, catName);
            });
        }
    });

    $('#btnCreateCategory').on('click', function() {
        Swal.fire({
            title: '<span class="font-bold">Create Ticket Category</span>',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event</label>
                        <select id="catEvent" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Event</option>
                            ${events.map(e => `<option value="${e.id}">${e.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                        <input id="catName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g. VIP, Regular, Premium">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price (Rp)</label>
                            <input id="catPrice" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="50000" min="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quota</label>
                            <input id="catQuota" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="100" min="1">
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Create Category',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#27b4f7',
            preConfirm: () => {
                const eventId = $('#catEvent').val();
                const name = $('#catName').val();
                const price = $('#catPrice').val();
                const quota = $('#catQuota').val();
                
                if (!eventId || !name || !price || !quota) {
                    Swal.showValidationMessage('Please fill all required fields');
                    return false;
                }
                
                if (parseFloat(price) < 0) {
                    Swal.showValidationMessage('Price cannot be negative');
                    return false;
                }
                
                if (parseInt(quota) < 1) {
                    Swal.showValidationMessage('Quota must be at least 1');
                    return false;
                }
                
                return { event_id: eventId, name, price, quota };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/category',
                    method: 'POST',
                    data: result.value,
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Success!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to create category', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    });

    function editCategory(catId) {
        const category = categoriesData.find(c => c.id === catId);
        if (!category) return;
        
        Swal.fire({
            title: '<span class="font-bold">Edit Ticket Category</span>',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event</label>
                        <select id="editCatEvent" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            ${events.map(e => `<option value="${e.id}" ${e.id === category.eventId ? 'selected' : ''}>${e.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                        <input id="editCatName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${category.name}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price (Rp)</label>
                            <input id="editCatPrice" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${category.price}" min="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quota</label>
                            <input id="editCatQuota" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${category.quota}" min="${category.sold_count}">
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Save Changes',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#27b4f7',
            preConfirm: () => {
                const newQuota = parseInt($('#editCatQuota').val());
                
                if (newQuota < category.sold_count) {
                    Swal.showValidationMessage(`Quota cannot be less than sold tickets (${category.sold_count})`);
                    return false;
                }
                
                const price = parseFloat($('#editCatPrice').val());
                if (price < 0) {
                    Swal.showValidationMessage('Price cannot be negative');
                    return false;
                }
                
                return {
                    event_id: $('#editCatEvent').val(),
                    name: $('#editCatName').val(),
                    price: price,
                    quota: newQuota
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/category/${catId}`,
                    method: 'PUT',
                    data: result.value,
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Success!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to update category', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    }

    function deleteCategory(catId, catName) {
        Swal.fire({
            title: '<span class="font-bold">Delete Category</span>',
            html: `<p class="text-gray-600 mb-4">Are you sure you want to delete category <strong>"${catName}"</strong>?</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, Delete',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#ef4444',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/category/${catId}`,
                    method: 'DELETE',
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Deleted!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to delete category', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    }

    $('#filterEvent').on('change', function() {
        const eventName = $(this).val();
        if (eventName) {
            table.column(0).search(eventName).draw();
        } else {
            table.column(0).search('').draw();
        }
    });

    $('#btnResetFilter').on('click', function() {
        $('#filterEvent').val('');
        table.column(0).search('').draw();
    });
});
</script>
@endsection