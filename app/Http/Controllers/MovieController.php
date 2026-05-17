<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with('category')->latest()->get();
        $categories = MovieCategory::all();
        return view('admin.online_pass.movie', compact('movies', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:movie_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url',
            'is_live' => 'boolean',
            'scheduled_at' => 'nullable|date',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('movies', 'public');
        }

        Movie::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'video_url' => $request->video_url,
            'is_live' => $request->boolean('is_live'),
            'scheduled_at' => $request->scheduled_at,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Movie created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:movie_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url',
            'is_live' => 'boolean',
            'scheduled_at' => 'nullable|date',
        ]);

        $movie = Movie::findOrFail($id);
        $thumbnailPath = $movie->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $thumbnailPath = $request->file('thumbnail')->store('movies', 'public');
        }

        $movie->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'video_url' => $request->video_url,
            'is_live' => $request->boolean('is_live'),
            'scheduled_at' => $request->scheduled_at,
        ]);

        return redirect()->back()->with('success', 'Movie updated successfully.');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        if ($movie->thumbnail && Storage::disk('public')->exists($movie->thumbnail)) {
            Storage::disk('public')->delete($movie->thumbnail);
        }
        $movie->delete();

        return redirect()->back()->with('success', 'Movie deleted successfully.');
    }

    public function toggle($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->update(['is_active' => !$movie->is_active]);

        return redirect()->back()->with('success', 'Movie status updated successfully.');
    }
}
