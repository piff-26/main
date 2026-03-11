@extends('layouts.admin')
@section('title', 'Manage Vouchers')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
        <div class="flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="flex flex-col md:flex-row items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Manage Vouchers</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola semua voucher termasuk yang sudah dinonaktifkan.</p>
        </div>
        
        <button onclick="openCreateModal()" class="mt-4 md:mt-0 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Voucher
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Voucher Code</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Usage (Used/Max)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Event & Scope</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Expiry</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vouchers ?? [] as $voucher)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ $voucher->trashed() ? 'bg-gray-50 opacity-70' : '' }}">
                        <td class="px-6 py-4 font-mono font-bold">
                            <span class="{{ $voucher->trashed() ? 'text-gray-400' : 'text-indigo-600' }}">{{ $voucher->code }}</span>
                            @if($voucher->trashed())
                                <span class="ml-2 text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full uppercase font-bold">Disabled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                            @if($voucher->discount_type == 'percentage')
                                {{ $voucher->discount_percentage }}%
                            @else
                                IDR {{ number_format($voucher->discount_nominal, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center">
                                <span class="text-sm text-gray-600 mb-1 font-medium">
                                    {{ $voucher->used_count ?? 0 }} / {{ $voucher->max_uses ?? 0 }}
                                </span>
                                <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    @php
                                        $max = $voucher->max_uses > 0 ? $voucher->max_uses : 1;
                                        $percent = (($voucher->used_count ?? 0) / $max) * 100;
                                    @endphp
                                    <div class="bg-indigo-500 h-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{-- Gunakan withTrashed() pada relasi jika model Event/Category juga pakai SoftDelete --}}
                            <span class="block text-sm text-gray-800 font-medium">{{ $voucher->event->name ?? 'Global Event' }}</span>
                            <span class="text-[10px] text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded uppercase font-bold tracking-tighter">
                                {{ $voucher->ticketCategory->name ?? 'All Categories' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{-- Pastikan expired_at sudah di-cast sebagai datetime di Model --}}
                            @if($voucher->expired_at)
                                {{ \Carbon\Carbon::parse($voucher->expired_at)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                @if(!$voucher->trashed())
                                    <button type="button" onclick="editVoucher({{ json_encode($voucher) }})" class="text-blue-600 hover:text-blue-800 font-bold text-sm">Edit</button>
                                @endif
                                
                                <form action="{{ route('admin.voucher.destroy', $voucher->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    @if($voucher->trashed())
                                        <button type="submit" class="text-green-600 hover:text-green-800 font-bold text-sm">Enable</button>
                                    @else
                                        <button type="submit" onclick="return confirm('Disable voucher ini?')" class="text-red-600 hover:text-red-800 font-bold text-sm">Disable</button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-gray-400 italic">Data voucher belum tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TETAP SAMA SEPERTI SEBELUMNYA --}}
<div id="modalCreate" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <form id="voucherForm" action="{{ route('admin.voucher.store') }}" method="POST" class="p-8">
            @csrf
            <div id="methodField"></div>
            <div class="flex justify-between items-center mb-6">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800">New Voucher Code</h3>
                <button type="button" onclick="closeModal('modalCreate')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            {{-- Isi Input Form Anda --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Voucher Code</label>
                    <input type="text" name="code" id="input_code" required class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Type</label>
                        <select name="discount_type" id="input_type" class="w-full border rounded-xl px-4 py-2.5 outline-none bg-white">
                            <option value="nominal">IDR</option>
                            <option value="percentage">%</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Value</label>
                        <input type="number" name="discount_value" id="input_value" required class="w-full border rounded-xl px-4 py-2.5">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Max Uses</label>
                        <input type="number" name="max_uses" id="input_max" required class="w-full border rounded-xl px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Expiry</label>
                        <input type="date" name="expired_at" id="input_expiry" required class="w-full border rounded-xl px-4 py-2.5">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal('modalCreate')" class="flex-1 px-4 py-3 border rounded-xl font-bold">Cancel</button>
                <button type="submit" id="submitBtn" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = "New Voucher Code";
        document.getElementById('voucherForm').action = "{{ route('admin.voucher.store') }}";
        document.getElementById('methodField').innerHTML = "";
        document.getElementById('voucherForm').reset();
        openModal('modalCreate');
    }

    function editVoucher(data) {
        document.getElementById('modalTitle').innerText = "Edit Voucher";
        document.getElementById('voucherForm').action = `/admin/managevouchers/${data.id}`;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        
        document.getElementById('input_code').value = data.code;
        document.getElementById('input_type').value = data.discount_type;
        document.getElementById('input_value').value = (data.discount_type === 'nominal') ? data.discount_nominal : data.discount_percentage;
        document.getElementById('input_max').value = data.max_uses;
        
        if(data.expired_at) {
            document.getElementById('input_expiry').value = data.expired_at.split(' ')[0];
        }
        openModal('modalCreate');
    }
</script>
@endsection