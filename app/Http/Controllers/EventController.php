<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketCategory;

class EventController extends Controller
{
    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->with(['ticketCategories' => function ($query) {
                $query->orderBy('price', 'asc'); 
            }])
            ->firstOrFail();

        return view('user.event.detail', [
            'title' => $event->name,
            'event' => $event
        ]);
    }
}
