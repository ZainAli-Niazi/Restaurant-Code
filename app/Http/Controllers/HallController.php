<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index()
    {
        $halls = Hall::withCount('tables')->latest()->paginate(10);
        return view('halls.index', compact('halls'));
    }

    public function create()
    {
        return view('halls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:halls',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        Hall::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('halls.index')->with('success', 'Hall created successfully.');
    }

    public function show(Hall $hall)
    {
        $hall->load('tables');
        return view('halls.show', compact('hall'));
    }

    public function edit(Hall $hall)
    {
        return view('halls.edit', compact('hall'));
    }

    public function update(Request $request, Hall $hall)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:halls,name,'.$hall->id,
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $hall->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('halls.index')->with('success', 'Hall updated successfully.');
    }

    public function destroy(Hall $hall)
    {
        if ($hall->tables()->count() > 0) {
            return redirect()->route('halls.index')->with('error', 'Cannot delete hall with associated tables.');
        }

        $hall->delete();
        return redirect()->route('halls.index')->with('success', 'Hall deleted successfully.');
    }

    public function tables(Hall $hall)
    {
        return response()->json($hall->tables);
    }
}