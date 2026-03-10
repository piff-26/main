@extends('layouts.admin')
@section('title', 'Manage Vouchers')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row items-center justify-between bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Manage Vouchers</h1>
            <p class="text-gray-500 text-sm mt-1">Buat kode promo baru atau kelola voucher yang sudah ada.</p>
        </div>
        
        <button onclick="openModal('modalCreate')" class="mt-4 md:mt-0 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
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
                    {{-- Loop data voucher di sini nantinya --}}
                    @forelse($vouchers ?? [] as $voucher)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $voucher->code }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                            {{ $voucher->discount_type == 'percentage' ? $voucher->discount_value.'%' : 'IDR '.number_format($voucher->discount_value) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center">
                                <span class="text-sm text-gray-600 mb-1 font-medium">{{ $voucher->used_count }} / {{ $voucher->max_uses }}</span>
                                <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="bg-indigo-500 h-full" style="width: 20%"></div> {{-- Logika persen di sini --}}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-sm text-gray-800 font-medium">{{ $voucher->event ?? 'Global' }}</span>
                            <span class="text-[10px] text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded uppercase font-bold tracking-tighter">{{ $voucher->category_scope }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $voucher->expired_at }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <button class="text-blue-600 hover:text-blue-800 font-bold text-sm">Edit</button>
                                
                                {{-- Form Action Kosong Siap Pakai --}}
                                <form action="#" method="POST" onsubmit="return confirm('Apakah anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-sm">Disable</button>
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

<div id="modalCreate" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all">
        <form action="#" method="POST" class="p-8">
            @csrf
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">New Voucher Code</h3>
                <button type="button" onclick="closeModal('modalCreate')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Voucher Code</label>
                    <input type="text" name="code" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="MISAL: HEMAT50">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Discount Type</label>
                        <select name="discount_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 outline-none bg-white">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Value</label>
                        <input type="number" name="discount_value" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Max Uses</label>
                        <input type="number" name="max_uses" placeholder="0 = Unlimited" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Expiry Date</label>
                        <input type="date" name="expired_at" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 outline-none text-gray-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-tighter">Category Scope</label>
                    <input type="text" name="category_scope" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 outline-none" placeholder="Workshop, Webinar, etc.">
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal('modalCreate')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">Create Now</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection