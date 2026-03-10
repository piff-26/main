@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Overview of PIFF 2026 Event Analytics</p>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 transform hover:scale-105 transition-all duration-300 cursor-pointer stat-card" style="border-left: 4px solid #27b4f7;">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Total Revenue</div>
                <i class="fas fa-money-bill-wave text-2xl" style="color: #27b4f7;"></i>
            </div>
            <div class="text-2xl font-bold revenue-value" style="color: #27b4f7;" id="totalRevenue">Rp 0</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 transform hover:scale-105 transition-all duration-300 cursor-pointer stat-card" style="border-left: 4px solid #10b981;">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Tickets Sold</div>
                <i class="fas fa-ticket-alt text-2xl text-green-600"></i>
            </div>
            <div class="text-2xl font-bold text-green-600"><span id="ticketsSold">0</span> / <span id="totalTickets">0</span></div>
            <div class="text-xs text-gray-500 mt-1" id="ticketsPercentage">0% sold</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 transform hover:scale-105 transition-all duration-300 cursor-pointer stat-card" style="border-left: 4px solid #8b5cf6;">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Total Transactions</div>
                <i class="fas fa-receipt text-2xl text-purple-600"></i>
            </div>
            <div class="text-2xl font-bold text-purple-600" id="totalTransactions">0</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 transform hover:scale-105 transition-all duration-300 cursor-pointer stat-card" style="border-left: 4px solid #fec401;">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Total Check-in</div>
                <i class="fas fa-check-circle text-2xl" style="color: #fec401;"></i>
            </div>
            <div class="text-2xl font-bold" style="color: #fec401;"><span id="totalCheckin">0</span> / <span id="ticketsSoldTotal">0</span></div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 transform hover:scale-105 transition-all duration-300 cursor-pointer stat-card" style="border-left: 4px solid #ff362d;">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Total Users</div>
                <i class="fas fa-users text-2xl" style="color: #ff362d;"></i>
            </div>
            <div class="text-2xl font-bold" style="color: #ff362d;" id="totalUsers">0</div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 chart-container" style="opacity: 0;">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Ticket Sold per Category</h2>
                <i class="fas fa-chart-pie" style="color: #27b4f7;"></i>
            </div>
            <canvas id="pieChart" class="max-h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 chart-container" style="opacity: 0;">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Check-in Progress</h2>
                <i class="fas fa-chart-bar" style="color: #27b4f7;"></i>
            </div>
            <canvas id="barChart" class="max-h-64"></canvas>
        </div>
    </div>

    {{-- Quick Numbers --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600 mb-1">Paid Transactions</div>
                    <div class="text-2xl font-bold text-green-600" id="paidTransactions">0</div>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-double text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600 mb-1">Failed / Expired Transactions</div>
                    <div class="text-2xl font-bold" style="color: #ff362d;" id="failedTransactions">0</div>
                </div>
                <div class="p-3 rounded-full" style="background-color: #ffe5e5;">
                    <i class="fas fa-times-circle text-xl" style="color: #ff362d;"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
const dashboardData = {
    totalRevenue: 1580000, // Based on seeder: 20k*200 + 79k*15 + 59k*10 + 49k*5
    ticketsSold: 230, // Total sold tickets
    totalTickets: 1200, // Total quota from seeder: 600+100+200+300
    totalTransactions: 30,
    totalCheckin: 92, // 40% of sold tickets
    totalUsers: 20,
    paidTransactions: 21,
    failedTransactions: 9,
    ticketCategories: [
        { name: 'Regular', sold: 200 }, // Day 1
        { name: 'Platinum', sold: 15 }, // Day 2
        { name: 'Gold', sold: 10 }, // Day 2
        { name: 'Silver', sold: 5 } // Day 2
    ]
};

$(document).ready(function() {
    function animateCounter(element, target) {
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            if (element.id === 'totalRevenue') {
                element.textContent = 'Rp ' + Math.floor(current).toLocaleString('id-ID');
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }

    // Update all statistics
    animateCounter(document.getElementById('totalRevenue'), dashboardData.totalRevenue);
    animateCounter(document.getElementById('ticketsSold'), dashboardData.ticketsSold);
    animateCounter(document.getElementById('totalTransactions'), dashboardData.totalTransactions);
    animateCounter(document.getElementById('totalCheckin'), dashboardData.totalCheckin);
    animateCounter(document.getElementById('totalUsers'), dashboardData.totalUsers);
    animateCounter(document.getElementById('paidTransactions'), dashboardData.paidTransactions);
    animateCounter(document.getElementById('failedTransactions'), dashboardData.failedTransactions);
    
    document.getElementById('totalTickets').textContent = dashboardData.totalTickets;
    document.getElementById('ticketsSoldTotal').textContent = dashboardData.ticketsSold;
    
    const percentage = ((dashboardData.ticketsSold / dashboardData.totalTickets) * 100).toFixed(1);
    document.getElementById('ticketsPercentage').textContent = percentage + '% sold';

    $('.stat-card').each(function(index) {
        $(this).delay(index * 100).animate({ opacity: 1 }, 600);
    });

    setTimeout(() => $('.chart-container').animate({ opacity: 1 }, 800), 500);

    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: dashboardData.ticketCategories.map(cat => cat.name),
            datasets: [{
                data: dashboardData.ticketCategories.map(cat => cat.sold),
                backgroundColor: ['#27b4f7', '#8b5cf6', '#fec401', '#6b7280'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: context => context.label + ': ' + context.parsed + ' tickets'
                    }
                }
            },
            animation: { duration: 1500 }
        }
    });

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: dashboardData.ticketCategories.map(cat => cat.name),
            datasets: [
                {
                    label: 'Checked In',
                    data: [80, 6, 4, 2], // Total 92 sesuai dengan statistik
                    backgroundColor: '#10b981',
                    borderRadius: 5
                },
                {
                    label: 'Not Checked In',
                    data: [120, 9, 6, 3], // Sisa dari yang belum check-in
                    backgroundColor: '#e5e7eb',
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true }
            },
            plugins: { legend: { position: 'bottom' } },
            animation: { duration: 1500 }
        }
    });
});
</script>
@endsection