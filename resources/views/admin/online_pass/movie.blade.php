@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Movies</h1>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Movie
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-48">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="movieSearch" placeholder="Search by title..." oninput="filterMovies()"
                class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-indigo-400">
        </div>
        <select id="filterCategory" onchange="filterMovies()" class="py-2 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-indigo-400">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select id="filterType" onchange="filterMovies()" class="py-2 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-indigo-400">
            <option value="">All Types</option>
            <option value="live">Live</option>
            <option value="vod">VOD</option>
        </select>
        <select id="filterStatus" onchange="filterMovies()" class="py-2 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-indigo-400">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <button onclick="resetMovieFilter()" class="text-sm text-gray-400 hover:text-gray-600 flex items-center gap-1">
            <i class="fas fa-times-circle"></i> Reset
        </button>
    </div>

    <div id="movieEmptyState" class="hidden text-center py-12 text-gray-400">
        <i class="fas fa-film text-3xl mb-2"></i>
        <p>No movies match your filter.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Thumbnail</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Title</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Category</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Type</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Status</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movies as $movie)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 movie-row"
                        data-title="{{ strtolower($movie->title) }}"
                        data-category="{{ $movie->category->name }}"
                        data-type="{{ $movie->is_live ? 'live' : 'vod' }}"
                        data-status="{{ $movie->is_active ? 'active' : 'inactive' }}">
                        <td class="py-3 px-4">
                            @if($movie->thumbnail)
                                <img src="{{ asset('storage/' . $movie->thumbnail) }}" alt="{{ $movie->title }}" class="w-16 h-24 object-cover rounded shadow-sm">
                            @else
                                <div class="w-16 h-24 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                                    <i class="fas fa-film"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-800">{{ $movie->title }}</p>
                            @if($movie->video_url)
                                <a href="{{ $movie->video_url }}" target="_blank" class="text-xs text-indigo-500 hover:underline"><i class="fas fa-link"></i> Video Link</a>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $movie->category->name }}</td>
                        <td class="py-3 px-4">
                            @if($movie->is_live)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">
                                    <i class="fas fa-circle text-[8px] mr-1"></i> Live
                                </span>
                                @if($movie->scheduled_at)
                                    <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($movie->scheduled_at)->format('d M Y H:i') }}</div>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                    VOD
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.movie.toggle', $movie->id) }}" method="POST" class="toggle-form">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                    onclick="confirmToggle(this, '{{ addslashes($movie->title) }}', {{ $movie->is_active ? 'true' : 'false' }})"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $movie->is_active ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $movie->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <button onclick="editMovie({{ json_encode($movie) }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                    onclick="confirmDelete('{{ route('admin.movie.destroy', $movie->id) }}', '{{ addslashes($movie->title) }}')"
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
            <h2 class="text-xl font-bold">Add Movie</h2>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('admin.movie.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (Image)</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full p-3 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
                        <input type="url" name="video_url" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://...">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Is Live Streaming?</label>
                        <select name="is_live" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="0">No (VOD)</option>
                            <option value="1">Yes (Live)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled At (for Live)</label>
                        <input type="datetime-local" name="scheduled_at" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
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
            <h2 class="text-xl font-bold">Edit Movie</h2>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="edit_title" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="edit_category_id" required class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (Leave blank to keep current)</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full p-3 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
                        <input type="url" name="video_url" id="edit_video_url" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Is Live Streaming?</label>
                        <select name="is_live" id="edit_is_live" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="0">No (VOD)</option>
                            <option value="1">Yes (Live)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled At</label>
                        <input type="datetime-local" name="scheduled_at" id="edit_scheduled_at" class="w-full p-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
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
    function editMovie(movie) {
        document.getElementById('editForm').action = `/admin/movie/${movie.id}`;
        document.getElementById('edit_title').value = movie.title;
        document.getElementById('edit_category_id').value = movie.category_id;
        document.getElementById('edit_description').value = movie.description || '';
        document.getElementById('edit_video_url').value = movie.video_url || '';
        document.getElementById('edit_is_live').value = movie.is_live ? '1' : '0';
        
        if (movie.scheduled_at) {
            const date = new Date(movie.scheduled_at);
            const formatted = date.toISOString().slice(0, 16);
            document.getElementById('edit_scheduled_at').value = formatted;
        } else {
            document.getElementById('edit_scheduled_at').value = '';
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    // Hidden delete form
    const deleteForm = document.createElement('form');
    deleteForm.id = 'deleteForm';
    deleteForm.method = 'POST';
    deleteForm.style.display = 'none';
    deleteForm.innerHTML = `@csrf @method('DELETE')`;
    document.body.appendChild(deleteForm);

    function confirmDelete(url, name) {
        Swal.fire({
            title: 'Delete Movie?',
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
            title: `${action} Movie?`,
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
        document.getElementById('addSubmitBtn').disabled = true;
        document.getElementById('addSubmitBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    });

    document.getElementById('editForm').addEventListener('submit', function() {
        document.getElementById('editSubmitBtn').disabled = true;
        document.getElementById('editSubmitBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    });

    function filterMovies() {
        const q      = document.getElementById('movieSearch').value.toLowerCase().trim();
        const cat    = document.getElementById('filterCategory').value;
        const type   = document.getElementById('filterType').value;
        const status = document.getElementById('filterStatus').value;
        let visible  = 0;
        document.querySelectorAll('.movie-row').forEach(row => {
            const matchQ      = !q      || row.dataset.title.includes(q);
            const matchCat    = !cat    || row.dataset.category === cat;
            const matchType   = !type   || row.dataset.type    === type;
            const matchStatus = !status || row.dataset.status  === status;
            const show = matchQ && matchCat && matchType && matchStatus;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('movieEmptyState').classList.toggle('hidden', visible > 0);
    }

    function resetMovieFilter() {
        document.getElementById('movieSearch').value = '';
        document.getElementById('filterCategory').value = '';
        document.getElementById('filterType').value = '';
        document.getElementById('filterStatus').value = '';
        filterMovies();
    }
</script>
@endpush
