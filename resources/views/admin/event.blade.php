@extends('layouts.admin')
@section('title', 'Manage Events')

@section('content')
    <div class="w-full mb-6">
        <div class="bg-white px-6 py-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Events</h1>
                <p class="text-sm text-gray-600 mt-1">Create and manage event details</p>
            </div>
            <button id="btnCreateEvent" class="px-4 py-2 text-white rounded-lg hover:shadow-md transition font-semibold" style="background-color: #27b4f7;">
                <i class="fas fa-plus mr-2"></i>Create Event
            </button>
        </div>
    </div>

    {{-- Events List --}}
    <div id="eventsList" class="grid grid-cols-1 gap-6"></div>
@endsection

@section('script')
<script>
const eventsData = [
    {
        id: 1,
        name: 'Screening Session-Student Gap Standers',
        date: '29 May 2026',
        startTime: '09:30',
        endTime: '12:00',
        location: 'Auditorium Gedung Q, Petra Christian University',
        ticketCategories: [
            { name: 'Regular', price: 20000, sold: 200, quota: 600 }
        ],
        stats: {
            revenue: 4000000,
            ticketsSold: 200,
            transactions: 20,
            checkins: 80
        }
    },
    {
        id: 2,
        name: 'Final Day and Talkshow With Bayu Skak',
        date: '30 May 2026',
        startTime: '12:00',
        endTime: '15:00',
        location: 'Auditorium Gedung Q, Petra Christian University',
        ticketCategories: [
            { name: 'Platinum', price: 79000, sold: 15, quota: 100 },
            { name: 'Gold', price: 59000, sold: 10, quota: 200 },
            { name: 'Silver', price: 49000, sold: 5, quota: 300 }
        ],
        stats: {
            revenue: 1830000,
            ticketsSold: 30,
            transactions: 10,
            checkins: 12
        }
    }
];

$(document).ready(function() {
    function renderEvents() {
        let html = '';
        eventsData.forEach((event, index) => {
            let categoriesHtml = '';
            event.ticketCategories.forEach(cat => {
                const percentage = (cat.sold / cat.quota * 100).toFixed(1);
                categoriesHtml += `
                    <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-sm font-semibold text-gray-800">${cat.name}</div>
                            <div class="text-sm font-bold" style="color: #27b4f7;">Rp ${cat.price.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="text-xs text-gray-600">Sold: ${cat.sold} / ${cat.quota}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="h-2 rounded-full transition-all duration-500" style="width: ${percentage}%; background-color: #27b4f7;"></div>
                        </div>
                    </div>
                `;
            });

            html += `
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 event-card" style="opacity: 0; transform: translateY(20px);">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-800 mb-2">${event.name}</h2>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                    <div><i class="fas fa-calendar mr-2 text-gray-400"></i>${event.date}</div>
                                    <div><i class="fas fa-clock mr-2 text-gray-400"></i>${event.startTime} - ${event.endTime}</div>
                                    <div><i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>${event.location}</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="px-3 py-2 hover:bg-blue-50 rounded-lg transition btn-view" style="color: #27b4f7;" data-id="${event.id}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="px-3 py-2 text-green-600 hover:bg-green-50 rounded-lg transition btn-edit" data-id="${event.id}" title="Edit Event">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="px-3 py-2 hover:bg-red-50 rounded-lg transition btn-delete" style="color: #ff362d;" data-id="${event.id}" data-name="${event.name}" title="Delete Event">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Ticket Categories</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                ${categoriesHtml}
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <div class="text-xs text-gray-600">Total Revenue</div>
                                    <div class="text-lg font-bold text-gray-900">Rp ${event.stats.revenue.toLocaleString('id-ID')}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Tickets Sold</div>
                                    <div class="text-lg font-bold text-gray-900">${event.stats.ticketsSold}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Transactions</div>
                                    <div class="text-lg font-bold text-gray-900">${event.stats.transactions}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Check-ins</div>
                                    <div class="text-lg font-bold text-gray-900">${event.stats.checkins}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#eventsList').html(html);

        $('.event-card').each(function(index) {
            $(this).delay(index * 150).animate({
                opacity: 1
            }, 600).css('transform', 'translateY(0)');
        });
    }

    renderEvents();

    // Event handlers
    $(document).on('click', '.btn-view', function() {
        const eventId = $(this).data('id');
        const event = eventsData.find(e => e.id === eventId);
        
        let categoriesHtml = '';
        event.ticketCategories.forEach(cat => {
            const percentage = (cat.sold / cat.quota * 100).toFixed(1);
            categoriesHtml += `
                <div class="bg-gray-50 p-3 rounded-lg mb-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold text-sm">${cat.name}</span>
                        <span class="font-bold text-sm" style="color: #27b4f7;">Rp ${cat.price.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="text-xs text-gray-600 mb-1">Sold: ${cat.sold} / ${cat.quota} (${percentage}%)</div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full" style="width: ${percentage}%; background-color: #27b4f7;"></div>
                    </div>
                </div>
            `;
        });
        
        Swal.fire({
            title: `<span class="font-bold">${event.name}</span>`,
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-2" style="color: #27b4f7;"></i>${event.date}
                        </div>
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-clock mr-2" style="color: #27b4f7;"></i>${event.startTime} - ${event.endTime}
                        </div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2" style="color: #27b4f7;"></i>${event.location}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Ticket Categories</h4>
                        ${categoriesHtml}
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <div class="text-xs text-gray-600">Revenue</div>
                            <div class="font-bold text-lg" style="color: #27b4f7;">Rp ${event.stats.revenue.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <div class="text-xs text-gray-600">Tickets Sold</div>
                            <div class="font-bold text-lg text-green-600">${event.stats.ticketsSold}</div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <div class="text-xs text-gray-600">Transactions</div>
                            <div class="font-bold text-lg text-purple-600">${event.stats.transactions}</div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <div class="text-xs text-gray-600">Check-ins</div>
                            <div class="font-bold text-lg" style="color: #fec401;">${event.stats.checkins}</div>
                        </div>
                    </div>
                </div>
            `,
            width: '700px',
            confirmButtonText: '<i class="fas fa-times mr-2"></i>Close',
            confirmButtonColor: '#6b7280'
        });
    });

    $('#btnCreateEvent').on('click', function() {
        Swal.fire({
            title: '<span class="font-bold">Create New Event</span>',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                        <input id="eventName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter event name">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date</label>
                        <input id="eventDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                            <input id="startTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                            <input id="endTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input id="eventLocation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter location">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="eventDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter event description"></textarea>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Create Event',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#27b4f7',
            cancelButtonColor: '#6b7280',
            customClass: {
                confirmButton: 'font-semibold',
                cancelButton: 'font-semibold'
            },
            preConfirm: () => {
                const name = $('#eventName').val();
                const date = $('#eventDate').val();
                const startTime = $('#startTime').val();
                const endTime = $('#endTime').val();
                const location = $('#eventLocation').val();
                const description = $('#eventDescription').val();
                
                if (!name || !date || !startTime || !endTime || !location) {
                    Swal.showValidationMessage('Please fill all required fields');
                    return false;
                }
                
                return { name, date, startTime, endTime, location, description };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',

                    confirmButtonColor: '#27b4f7',
                    timer: 2000
                });
            }
        });
    });

    // Edit Event
    $(document).on('click', '.btn-edit', function() {
        const eventId = $(this).data('id');
        const event = eventsData.find(e => e.id === eventId);
        
        if (!event) return;
        
        Swal.fire({
            title: '<span class="font-bold">Edit Event</span>',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                        <input id="editEventName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="${event.name}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date</label>
                        <input id="editEventDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="2026-05-${eventId === 1 ? '29' : '30'}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                            <input id="editStartTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="${event.startTime}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                            <input id="editEndTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="${event.endTime}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input id="editEventLocation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="${event.location}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="editEventDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter event description"></textarea>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <p class="text-xs text-yellow-800"><i class="fas fa-info-circle mr-1"></i>Current tickets sold: ${event.stats.ticketsSold}</p>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Save Changes',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#27b4f7',
            cancelButtonColor: '#6b7280',
            customClass: {
                confirmButton: 'font-semibold',
                cancelButton: 'font-semibold'
            },
            preConfirm: () => {
                const name = $('#editEventName').val();
                const date = $('#editEventDate').val();
                const startTime = $('#editStartTime').val();
                const endTime = $('#editEndTime').val();
                const location = $('#editEventLocation').val();
                const description = $('#editEventDescription').val();
                
                if (!name || !date || !startTime || !endTime || !location) {
                    Swal.showValidationMessage('Please fill all required fields');
                    return false;
                }
                
                return { name, date, startTime, endTime, location, description };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    confirmButtonColor: '#27b4f7',
                    timer: 2000
                });
            }
        });
    });

    // Delete Event
    $(document).on('click', '.btn-delete', function() {
        const eventId = $(this).data('id');
        const eventName = $(this).data('name');
        
        Swal.fire({
            title: '<span class="font-bold">Delete Event</span>',
            html: `
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Are you sure you want to delete <strong>"${eventName}"</strong>?</p>
                    <div class="bg-red-50 p-3 rounded-lg mb-4">
                        <p class="text-sm text-red-800">
                            <i class="fas fa-warning mr-2"></i>
                            This action cannot be undone. All associated ticket categories and transactions will also be affected.
                        </p>
                    </div>
                </div>
            `,
            width: '500px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, Delete',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            customClass: {
                confirmButton: 'font-semibold',
                cancelButton: 'font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    confirmButtonColor: '#27b4f7',
                    timer: 2000
                });
            }
        });
    });
});
</script>
@endsection