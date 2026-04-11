@php
    $currentRoute = request()->route()?->getName();
    $navItems = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high'],
        ['route' => 'admin.transaction', 'label' => 'Transactions', 'icon' => 'fa-receipt'],
        ['route' => 'admin.event', 'label' => 'Events', 'icon' => 'fa-calendar-days'],
        ['route' => 'admin.category', 'label' => 'Ticket Category', 'icon' => 'fa-tags'],
        ['route' => 'admin.monitor', 'label' => 'Monitor', 'icon' => 'fa-tower-broadcast'],
        ['route' => 'admin.insight', 'label' => 'Insight', 'icon' => 'fa-chart-line'],
        ['route' => 'admin.ticketScan', 'label' => 'Ticket Scan', 'icon' => 'fa-qrcode'],
        ['route' => 'admin.manageVouchers', 'label' => 'Vouchers', 'icon' => 'fa-ticket'],
    ];
@endphp

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside id="sidebar"
    class="fixed left-0 top-0 h-full w-64 bg-white border-r border-gray-100 shadow-xl z-50
           flex flex-col
           -translate-x-full md:translate-x-0
           transition-transform duration-300 ease-in-out">

    {{-- Logo / Brand --}}
    <div class="flex flex-col items-center justify-center px-6 py-7 border-b border-gray-100">
        {{-- <div class="w-10 h-10  flex items-center justify-center mb-3 shadow-md">
            <img src="{{ asset('assets/logo/logo_piff.png') }}" alt="">
        </div> --}}
        <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest text-center leading-tight">Petra
            International<br>Film Festival 2026</p>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] px-3 mb-2">Menu</p>
        @foreach ($navItems as $item)
            @php $isActive = $currentRoute === $item['route']; @endphp
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                       {{ $isActive ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <span
                    class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs
                             {{ $isActive ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                </span>
                {{ $item['label'] }}
                @if ($isActive)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Footer / Logout --}}
    <div class="px-3 py-4 border-t border-gray-100">
        <a href="{{ route('logout') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-400 hover:bg-red-50 hover:text-red-600 transition-all duration-150">
            <span class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center shrink-0 text-xs text-red-400">
                <i class="fa-solid fa-right-from-bracket"></i>
            </span>
            Logout
        </a>
    </div>
</aside>

{{-- Mobile topbar --}}
<header
    class="md:hidden fixed top-0 left-0 right-0 z-30 bg-white border-b border-gray-100 shadow-sm flex items-center justify-between px-4 h-14">
    <button onclick="openSidebar()"
        class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
        <i class="fa-solid fa-bars text-sm"></i>
    </button>
    <p class="text-xs font-black text-gray-800 uppercase tracking-widest">PIFF 2026</p>
    <a href="{{ route('logout') }}"
        class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-red-100 transition">
        <i class="fa-solid fa-right-from-bracket text-sm"></i>
    </a>
</header>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }
</script>
