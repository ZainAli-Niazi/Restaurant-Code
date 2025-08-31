<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shift::with('user')->latest();

        // 🔹 Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('end_time');
            } elseif ($request->status === 'closed') {
                $query->whereNotNull('end_time');
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_time', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('start_time', '<=', $request->to_date);
        }

        $shifts = $query->paginate(20);
        $currentShift = Shift::whereNull('end_time')->first();

        return view('shifts.index', compact('shifts', 'currentShift'));
    }


    public function create()
    {
        $activeShift = Shift::whereNull('end_time')->first();
        if ($activeShift) {
            return redirect()->route('shifts.index')->with('error', 'There is already an active shift. Please end it before starting a new one.');
        }

        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'starting_cash' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            Shift::create([
                'name' => $request->name,
                'user_id' => Auth::id(),
                'starting_cash' => $request->starting_cash,
                'start_time' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('shifts.index')->with('success', 'Shift started successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error starting shift: ' . $e->getMessage());
        }
    }

    public function endShift(Request $request, Shift $shift)
    {
        if ($shift->end_time) {
            return redirect()->route('shifts.index')->with('error', 'This shift has already ended.');
        }

        $request->validate([
            'ending_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $shift->update([
                'ending_cash' => $request->ending_cash,
                'end_time' => Carbon::now(),
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('shifts.index')->with('success', 'Shift ended successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error ending shift: ' . $e->getMessage());
        }
    }

    public function show(Shift $shift)
    {
        $shift->load(['orders', 'user']);

        $totalSales = $shift->orders->sum('total_amount');
        $totalOrders = $shift->orders->count();

        return view('shifts.show', compact('shift', 'totalSales', 'totalOrders'));
    }
}
