<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query()->with('user');
        
        // Set default date range if not provided
        $fromDate = $request->has('from_date') ? $request->from_date : Carbon::now()->subDays(30)->format('Y-m-d');
        $toDate = $request->has('to_date') ? $request->to_date : Carbon::now()->format('Y-m-d');
        
        // Filter by date range
        $query->whereBetween('date', [$fromDate, $toDate]);
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        $expenses = $query->latest()->paginate(20);
        
        // Calculate total expenses for the period
        $totalExpenses = $query->sum('amount');
        
        // Calculate average daily expenses
        $daysDifference = max(1, Carbon::parse($fromDate)->diffInDays(Carbon::parse($toDate)));
        $averageDaily = $totalExpenses / $daysDifference;
        
        // Get unique categories for filter dropdown
        $categories = Expense::select('category')->distinct()->pluck('category');
        
        return view('expenses.index', compact('expenses', 'categories', 'averageDaily', 'totalExpenses', 'fromDate', 'toDate'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Expense::create([
            'date' => $request->date,
            'reference' => $request->reference,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load('user');
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense->update([
            'date' => $request->date,
            'reference' => $request->reference,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}