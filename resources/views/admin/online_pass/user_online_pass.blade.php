@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Online Passes</h1>
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
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">User</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Online Ticket</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Transaction Ref</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Status</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Purchased At</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 text-sm w-40">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passes as $pass)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-800">{{ $pass->user->name ?? 'Unknown User' }}</p>
                            <p class="text-xs text-gray-500">{{ $pass->user->email ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-indigo-600">{{ $pass->onlineTicket->name ?? 'Unknown Ticket' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.transaction.detail', $pass->transaction->invoice_code ?? '') }}" class="text-sm text-blue-500 hover:underline">
                                {{ $pass->transaction->invoice_code ?? '-' }}
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            @if($pass->status->value === 'active' || $pass->status === 'active')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $pass->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.user_online_pass.update_status', $pass->id) }}" method="POST" class="pass-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ ($pass->status->value ?? $pass->status) === 'active' ? 'inactive' : 'active' }}">
                                @if(($pass->status->value ?? $pass->status) === 'active')
                                    <button type="button"
                                        onclick="confirmPassStatus(this, '{{ $pass->user->name ?? 'User' }}', true)"
                                        class="text-xs px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-ban mr-1"></i> Deactivate
                                    </button>
                                @else
                                    <button type="button"
                                        onclick="confirmPassStatus(this, '{{ $pass->user->name ?? 'User' }}', false)"
                                        class="text-xs px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-check mr-1"></i> Activate
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            No user online passes found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmPassStatus(btn, userName, isActive) {
        const action = isActive ? 'Deactivate' : 'Activate';
        Swal.fire({
            title: `${action} Online Pass?`,
            text: `${action} the online pass for "${userName}"?`,
            icon: isActive ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isActive ? '#ef4444' : '#16a34a',
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
</script>
@endpush
@endsection

