@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Online Tickets</h1>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Ticket
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Image</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Name</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Price</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Access Period</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Movies Included</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Status</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($onlineTickets as $ticket)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="py-3 px-4">
                            @if($ticket->image)
                                <img src="{{ asset('storage/' . $ticket->image) }}" alt="Ticket Image" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-800">{{ $ticket->name }}</p>
                            <p class="text-xs text-gray-500">{{ Str::limit($ticket->description, 30) }}</p>
                        </td>
                        <td class="py-3 px-4 font-medium text-green-600">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <div><span class="text-gray-500">Start:</span> {{ \Carbon\Carbon::parse($ticket->access_start_date)->format('d M Y H:i') }}</div>
                            <div><span class="text-gray-500">End:</span> {{ \Carbon\Carbon::parse($ticket->access_end_date)->format('d M Y H:i') }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-gray-100 rounded-lg text-xs font-semibold text-gray-700">
                                {{ $ticket->movies->count() }} Movies
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.online_ticket.toggle', $ticket->id) }}" method="POST" class="toggle-form">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                    onclick="confirmToggle(this, '{{ addslashes($ticket->name) }}', {{ $ticket->is_active ? 'true' : 'false' }})"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $ticket->is_active ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $ticket->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <button onclick="editTicket({{ json_encode($ticket) }}, {{ json_encode($ticket->movies->pluck('id')) }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                    onclick="confirmDelete('{{ route('admin.online_ticket.destroy', $ticket->id) }}', '{{ addslashes($ticket->name) }}')"
                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center shrink-0">
            <h2 class="text-xl font-bold">Add Online Ticket</h2>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('admin.online_ticket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ticket Name</label>
                        <input type="text" name="name" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                        <input type="number" name="price" min="0" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full p-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Access Start Date</label>
                        <input type="datetime-local" name="access_start_date" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Access End Date</label>
                        <input type="datetime-local" name="access_end_date" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-gray-700">Movies Included</label>
                            <label class="flex items-center gap-1.5 text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 select-none">
                                <input type="checkbox" id="add_select_all" class="rounded text-indigo-600" onchange="toggleAllMovies('add_select_all', '.add-movie-checkbox')">
                                Select All
                            </label>
                        </div>
                        <div class="h-32 overflow-y-auto border border-gray-300 rounded-lg p-2 bg-gray-50">
                            @foreach($movies as $movie)
                                <label class="flex items-center gap-2 p-1 hover:bg-gray-100 rounded cursor-pointer">
                                    <input type="checkbox" name="movies[]" value="{{ $movie->id }}" class="rounded text-indigo-600 focus:ring-indigo-500 add-movie-checkbox">
                                    <span class="text-sm">{{ $movie->title }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Select the movies that user can watch with this ticket.</p>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                        <textarea name="tnc" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" id="addSubmitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center shrink-0">
            <h2 class="text-xl font-bold">Edit Online Ticket</h2>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ticket Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                        <input type="number" name="price" id="edit_price" min="0" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full p-3 p-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer">
                        <div id="edit_image_preview" class="mt-2 hidden">
                            <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                            <img id="edit_image_img" src="" alt="Current Image" class="h-20 rounded-lg border border-gray-200">
                        </div>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="2" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Access Start Date</label>
                        <input type="datetime-local" name="access_start_date" id="edit_access_start_date" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Access End Date</label>
                        <input type="datetime-local" name="access_end_date" id="edit_access_end_date" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-gray-700">Movies Included</label>
                            <label class="flex items-center gap-1.5 text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 select-none">
                                <input type="checkbox" id="edit_select_all" class="rounded text-indigo-600" onchange="toggleAllMovies('edit_select_all', '.edit-movie-checkbox')">
                                Select All
                            </label>
                        </div>
                        <div class="h-32 overflow-y-auto border border-gray-300 rounded-lg p-2 bg-gray-50">
                            @foreach($movies as $movie)
                                <label class="flex items-center gap-2 p-1 hover:bg-gray-100 rounded cursor-pointer">
                                    <input type="checkbox" name="movies[]" value="{{ $movie->id }}" class="rounded text-indigo-600 focus:ring-indigo-500 edit-movie-checkbox">
                                    <span class="text-sm">{{ $movie->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                        <textarea name="tnc" id="edit_tnc" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" id="editSubmitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editTicket(ticket, movieIds) {
        document.getElementById('editForm').action = `/admin/online-ticket/${ticket.id}`;
        document.getElementById('edit_name').value = ticket.name;
        document.getElementById('edit_price').value = ticket.price;
        document.getElementById('edit_description').value = ticket.description || '';
        document.getElementById('edit_tnc').value = ticket.tnc || '';
        
        if (ticket.image) {
            document.getElementById('edit_image_preview').classList.remove('hidden');
            document.getElementById('edit_image_img').src = '/storage/' + ticket.image;
        } else {
            document.getElementById('edit_image_preview').classList.add('hidden');
        }
        
        if (ticket.access_start_date) {
            document.getElementById('edit_access_start_date').value = ticket.access_start_date.replace(' ', 'T').slice(0, 16);
        }
        if (ticket.access_end_date) {
            document.getElementById('edit_access_end_date').value = ticket.access_end_date.replace(' ', 'T').slice(0, 16);
        }

        const checkboxes = document.querySelectorAll('.edit-movie-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = movieIds.includes(parseInt(cb.value));
        });

        document.getElementById('editModal').classList.remove('hidden');
    }

    function toggleAllMovies(selectAllId, checkboxClass) {
        const checked = document.getElementById(selectAllId).checked;
        document.querySelectorAll(checkboxClass).forEach(cb => cb.checked = checked);
    }

    // Sync "Select All" state when individual checkboxes change
    document.querySelectorAll('.add-movie-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const all = document.querySelectorAll('.add-movie-checkbox');
            document.getElementById('add_select_all').checked = [...all].every(c => c.checked);
        });
    });
    document.querySelectorAll('.edit-movie-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const all = document.querySelectorAll('.edit-movie-checkbox');
            document.getElementById('edit_select_all').checked = [...all].every(c => c.checked);
        });
    });

    const deleteForm = document.createElement('form');
    deleteForm.id = 'deleteForm';
    deleteForm.method = 'POST';
    deleteForm.style.display = 'none';
    deleteForm.innerHTML = `@csrf @method('DELETE')`;
    document.body.appendChild(deleteForm);

    function confirmDelete(url, name) {
        Swal.fire({
            title: 'Delete Online Ticket?',
            text: `"${name}" will be permanently deleted.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                document.getElementById('deleteForm').action = url;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    function confirmToggle(btn, name, isActive) {
        const action = isActive ? 'Deactivate' : 'Activate';
        Swal.fire({
            title: `${action} Ticket?`,
            text: `"${name}" will be ${isActive ? 'deactivated' : 'activated'}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isActive ? '#ef4444' : '#4f46e5',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${action.toLowerCase()}`,
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                btn.closest('form').submit();
            }
        });
    }

    document.getElementById('addModal').querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('addSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    });

    document.getElementById('editForm').addEventListener('submit', function() {
        const btn = document.getElementById('editSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    });
</script>
@endpush
