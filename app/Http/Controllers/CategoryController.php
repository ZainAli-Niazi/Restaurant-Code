<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $icons = collect(File::files(public_path('category-icons')))
                    ->map(fn($f) => $f->getFilename());
        return view('categories.create', compact('icons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'required|string',
            'status' => 'boolean',
        ]);

        Category::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        $icons = collect(File::files(public_path('category-icons')))
                    ->map(fn($f) => $f->getFilename());
        return view('categories.edit', compact('category', 'icons'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'icon' => 'required|string',
            'status' => 'boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category with associated products.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}