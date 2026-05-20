<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\OnlineTicket;
use Illuminate\Http\Request;

class OnlineTicketController extends Controller
{
    public function index()
    {
        $onlineTickets = OnlineTicket::with('movies')->latest()->get();
        $movies = Movie::where('is_active', true)->get();
        return view('admin.online_pass.online_ticket', compact('onlineTickets', 'movies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'access_start_date' => 'required|date',
            'access_end_date' => 'required|date|after:access_start_date',
            'tnc' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'movies' => 'array',
            'movies.*' => 'exists:movies,id'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('online_tickets', 'public');
        }

        $onlineTicket = OnlineTicket::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . uniqid(),
            'image' => $imagePath,
            'description' => $request->description,
            'price' => $request->price,
            'access_start_date' => $request->access_start_date,
            'access_end_date' => $request->access_end_date,
            'tnc' => $request->tnc,
            'is_active' => true,
        ]);

        if ($request->has('movies')) {
            $onlineTicket->movies()->sync($request->movies);
        }

        return redirect()->back()->with('success', 'Online Ticket created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'access_start_date' => 'required|date',
            'access_end_date' => 'required|date|after:access_start_date',
            'tnc' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'movies' => 'array',
            'movies.*' => 'exists:movies,id'
        ]);

        $onlineTicket = OnlineTicket::findOrFail($id);
        
        $imagePath = $onlineTicket->image;
        if ($request->hasFile('image')) {
            if ($imagePath && \Storage::disk('public')->exists($imagePath)) {
                \Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('online_tickets', 'public');
        }

        $onlineTicket->update([
            'name' => $request->name,
            'image' => $imagePath,
            'description' => $request->description,
            'price' => $request->price,
            'access_start_date' => $request->access_start_date,
            'access_end_date' => $request->access_end_date,
            'tnc' => $request->tnc,
        ]);

        if ($request->has('movies')) {
            $onlineTicket->movies()->sync($request->movies);
        } else {
            $onlineTicket->movies()->detach();
        }

        return redirect()->back()->with('success', 'Online Ticket updated successfully.');
    }

    public function destroy($id)
    {
        $onlineTicket = OnlineTicket::findOrFail($id);
        $onlineTicket->movies()->detach();
        $onlineTicket->delete();

        return redirect()->back()->with('success', 'Online Ticket deleted successfully.');
    }

    public function toggle($id)
    {
        $onlineTicket = OnlineTicket::findOrFail($id);
        $onlineTicket->update(['is_active' => !$onlineTicket->is_active]);

        return redirect()->back()->with('success', 'Online Ticket status updated successfully.');
    }
}
