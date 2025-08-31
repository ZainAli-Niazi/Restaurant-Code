<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Hall;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with('hall')->latest()->paginate(10);
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        $halls = Hall::where('status', true)->get();
        return view('tables.create', compact('halls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hall_id' => 'required|exists:halls,id',
            'capacity' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        Table::create([
            'name' => $request->name,
            'hall_id' => $request->hall_id,
            'capacity' => $request->capacity,
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('tables.index')->with('success', 'Table created successfully.');
    }

    public function edit(Table $table)
    {
        $halls = Hall::where('status', true)->get();
        return view('tables.edit', compact('table', 'halls'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hall_id' => 'required|exists:halls,id',
            'capacity' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        $table->update([
            'name' => $request->name,
            'hall_id' => $request->hall_id,
            'capacity' => $request->capacity,
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('tables.index')->with('success', 'Table updated successfully.');
    }

    public function destroy(Table $table)
    {
        if ($table->orders()->count() > 0) {
            return redirect()->route('tables.index')->with('error', 'Cannot delete table with associated orders.');
        }

        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully.');
    }
}