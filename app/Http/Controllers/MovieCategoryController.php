<?php

namespace App\Http\Controllers;

use App\Models\MovieCategory;
use Illuminate\Http\Request;

class MovieCategoryController extends Controller
{
    public function index()
    {
        $categories = MovieCategory::latest()->get();
        return view('admin.online_pass.movie_category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MovieCategory::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Movie category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = MovieCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Movie category updated successfully.');
    }

    public function destroy($id)
    {
        $category = MovieCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Movie category deleted successfully.');
    }
}
