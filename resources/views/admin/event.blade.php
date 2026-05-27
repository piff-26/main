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
const eventsData = {!! json_encode($events->map(function($event) {
    return [
        'id' => $event->id,
        'name' => $event->name,
        'image' => $event->image ? asset('storage/' . $event->image) : null,
        'seatMapImage' => $event->seat_map_image ? asset('storage/' . $event->seat_map_image) : null,
        'description' => $event->description,
        'tnc' => $event->tnc,
        'date' => $event->event_date->format('d M Y'),
        'startTime' => $event->start_time->format('H:i'),
        'endTime' => $event->end_time ? $event->end_time->format('H:i') : '',
        'location' => $event->location,
        'eventClosed' => $event->event_closed ? $event->event_closed->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') : '',
        'isEventClosed' => $event->isClosed(),
        'detailUrl' => route('admin.event.detail', $event->id),
        'ticketCategories' => $event->ticketCategories->map(function($cat) {
            return [
                'name' => $cat->name,
                'price' => $cat->price,
                'sold' => $cat->sold_count,
                'quota' => $cat->quota
            ];
        }),
        'stats' => $event->stats
    ];
})) !!};

// Global image dropzone helpers — must be global for onclick attributes in Swal HTML
function imageDropzone(inputId, existingUrl = null) {
    const previewId = inputId + '_preview';
    const zoneId    = inputId + '_zone';
    const emptyHtml = `<div class="text-gray-400 py-4"><i class="fas fa-cloud-upload-alt text-3xl mb-2 block"></i><p class="text-sm">Click or drag &amp; drop image here</p><p class="text-xs mt-1">Max 2MB</p></div>`;
    const existingHtml = existingUrl
        ? `<div class="relative inline-block"><img src="${existingUrl}" class="max-h-32 mx-auto rounded object-contain"><button type="button" onclick="event.stopPropagation();clearImage('${inputId}')" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">&times;</button></div>`
        : emptyHtml;
    return `<div id="${zoneId}" class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-400 transition bg-gray-50" ondragover="event.preventDefault();this.classList.add('border-blue-400','bg-blue-50')" ondragleave="this.classList.remove('border-blue-400','bg-blue-50')" ondrop="event.preventDefault();this.classList.remove('border-blue-400','bg-blue-50');handleDrop(event,'${inputId}')" onclick="document.getElementById('${inputId}').click()"><div id="${previewId}">${existingHtml}</div><input id="${inputId}" type="file" accept="image/*" class="hidden" onchange="previewImage(this,'${previewId}')"></div>`;
}

function handleDrop(ev, inputId) {
    const file = ev.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    previewImage(input, inputId + '_preview');
}

function previewImage(input, previewId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(previewId).innerHTML =
            `<div class="relative inline-block"><img src="${e.target.result}" class="max-h-32 mx-auto rounded object-contain"><button type="button" onclick="event.stopPropagation();clearImage('${input.id}')" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">&times;</button></div>`;
    };
    reader.readAsDataURL(file);
}

function clearImage(inputId) {
    document.getElementById(inputId).value = '';
    document.getElementById(inputId + '_preview').innerHTML =
        `<div class="text-gray-400 py-4"><i class="fas fa-cloud-upload-alt text-3xl mb-2 block"></i><p class="text-sm">Click or drag &amp; drop image here</p><p class="text-xs mt-1">Max 2MB</p></div>`;
}

$(document).ready(function() {
    const MAX_MB = 2;
    const MAX_BYTES = MAX_MB * 1024 * 1024;

    function validateImageSize(inputId, label) {
        const file = document.getElementById(inputId)?.files[0];
        if (file && file.size > MAX_BYTES) {
            Swal.showValidationMessage(`${label} must be under ${MAX_MB}MB (current: ${(file.size / 1024 / 1024).toFixed(1)}MB)`);
            return false;
        }
        return true;
    }
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
                    ${event.image ? `<img src="${event.image}" alt="${event.name}" class="w-full h-40 object-cover">` : `<div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400"><i class="fas fa-image text-4xl"></i></div>`}
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h2 class="text-xl font-bold text-gray-800">${event.name}</h2>
                                    ${event.isEventClosed ? '<span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700"><i class="fas fa-lock mr-1"></i>Sales Closed</span>' : ''}
                                </div>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                    <div><i class="fas fa-calendar mr-2 text-gray-400"></i>${event.date}</div>
                                    <div><i class="fas fa-clock mr-2 text-gray-400"></i>${event.startTime} - ${event.endTime}</div>
                                    <div><i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>${event.location}</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="${event.detailUrl}" class="px-3 py-2 hover:bg-blue-50 rounded-lg transition" style="color: #27b4f7;" title="View Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="px-3 py-2 text-green-600 hover:bg-green-50 rounded-lg transition btn-edit" data-id="${event.id}" title="Edit Event">
                                    <i class="fas fa-edit"></i>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Banner Image <span class="text-gray-400 font-normal">(optional)</span></label>
                        ${imageDropzone('eventImage')}
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Seat Map Image <span class="text-gray-400 font-normal">(optional)</span></label>
                        ${imageDropzone('eventSeatMap')}
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="eventDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none" placeholder="Deskripsi event..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Syarat & Ketentuan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="eventTnc" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none" placeholder="Syarat dan ketentuan..."></textarea>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Create Event',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#27b4f7',
            cancelButtonColor: '#6b7280',
            customClass: { confirmButton: 'font-semibold', cancelButton: 'font-semibold' },
            preConfirm: () => {
                const name = $('#eventName').val();
                const date = $('#eventDate').val();
                const startTime = $('#startTime').val();
                const endTime = $('#endTime').val();
                const location = $('#eventLocation').val();

                if (!name || !date || !startTime || !endTime || !location) {
                    Swal.showValidationMessage('Please fill all required fields');
                    return false;
                }

                const selectedDate = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (selectedDate < today) {
                    Swal.showValidationMessage('Event date cannot be in the past');
                    return false;
                }

                if (endTime <= startTime) {
                    Swal.showValidationMessage('End time must be after start time');
                    return false;
                }

                if (!validateImageSize('eventImage', 'Banner Image')) return false;
                if (!validateImageSize('eventSeatMap', 'Seat Map Image')) return false;

                const formData = new FormData();
                formData.append('name', name);
                formData.append('event_date', date);
                formData.append('start_time', startTime);
                formData.append('end_time', endTime);
                formData.append('location', location);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                const imageFile = document.getElementById('eventImage').files[0];
                if (imageFile) formData.append('image', imageFile);
                const seatMapFile = document.getElementById('eventSeatMap').files[0];
                if (seatMapFile) formData.append('seat_map_image', seatMapFile);
                formData.append('description', document.getElementById('eventDescription').value);
                formData.append('tnc', document.getElementById('eventTnc').value);

                return formData;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/event',
                    method: 'POST',
                    data: result.value,
                    processData: false,
                    contentType: false,
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Success!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to create event', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    });

    // Edit Event
    $(document).on('click', '.btn-edit', function() {
        const eventId = $(this).data('id');
        const event = eventsData.find(e => e.id === eventId);
        if (!event) return;

        const dateParts = event.date.split(' ');
        const months = {'Jan':'01','Feb':'02','Mar':'03','Apr':'04','May':'05','Jun':'06','Jul':'07','Aug':'08','Sep':'09','Oct':'10','Nov':'11','Dec':'12'};
        const dateValue = `${dateParts[2]}-${months[dateParts[1]]}-${dateParts[0].padStart(2, '0')}`;

        Swal.fire({
            title: '<span class="font-bold">Edit Event</span>',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                        <input id="editEventName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${event.name}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date</label>
                        <input id="editEventDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${dateValue}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                            <input id="editStartTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${event.startTime}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                            <input id="editEndTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${event.endTime}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input id="editEventLocation" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${event.location}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ticket Sales Close Date & Time <span class="text-gray-400 font-normal">(optional — leave empty to keep open)</span></label>
                        <input id="editEventClosed" type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${event.eventClosed}">
                        <p class="text-xs text-gray-500 mt-1">After this date & time, all ticket categories of this event will be automatically closed.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Banner Image <span class="text-gray-400 font-normal">(leave empty to keep current)</span></label>
                        ${imageDropzone('editEventImage', event.image)}
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Seat Map Image <span class="text-gray-400 font-normal">(leave empty to keep current)</span></label>
                        ${imageDropzone('editEventSeatMap', event.seatMapImage)}
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="editEventDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none">${event.description ?? ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Syarat & Ketentuan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="editEventTnc" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none">${event.tnc ?? ''}</textarea>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Save Changes',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#27b4f7',
            preConfirm: () => {
                if (!validateImageSize('editEventImage', 'Banner Image')) return false;
                if (!validateImageSize('editEventSeatMap', 'Seat Map Image')) return false;

                const formData = new FormData();
                formData.append('name', $('#editEventName').val());
                formData.append('event_date', $('#editEventDate').val());
                formData.append('start_time', $('#editStartTime').val());
                formData.append('end_time', $('#editEndTime').val());
                formData.append('location', $('#editEventLocation').val());
                formData.append('event_closed', $('#editEventClosed').val());
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('_method', 'PUT');
                const imageFile = document.getElementById('editEventImage').files[0];
                if (imageFile) formData.append('image', imageFile);
                const seatMapFile = document.getElementById('editEventSeatMap').files[0];
                if (seatMapFile) formData.append('seat_map_image', seatMapFile);
                formData.append('description', document.getElementById('editEventDescription').value);
                formData.append('tnc', document.getElementById('editEventTnc').value);
                return formData;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                $.ajax({
                    url: `/admin/event/${eventId}`,
                    method: 'POST',
                    data: result.value,
                    processData: false,
                    contentType: false,
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Success!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to update event', confirmButtonColor: '#ef4444' });
                    }
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
            html: `<p class="text-gray-600 mb-4">Are you sure you want to delete <strong>"${eventName}"</strong>?</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, Delete',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/event/${eventId}`,
                    method: 'DELETE',
                    success: function() {
                        Swal.fire({ icon: 'success', title: 'Deleted!', confirmButtonColor: '#27b4f7', timer: 2000 })
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to delete event', confirmButtonColor: '#ef4444' });
                    }
                });
            }
        });
    });
});
</script>
@endsection