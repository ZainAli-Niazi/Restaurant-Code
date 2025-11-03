<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of completed orders with filters and pagination.
     */
    public function index(Request $request)
    {
        $orders = Order::with('orderItems.product')
            ->where('status', 'completed') // ✅ Only show completed orders
            ->when($request->from_date, fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load('orderItems.product');
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,hold',
            'paid_amount' => 'required|numeric|min:0'
        ]);

        $order->update([
            'status' => $request->status,
            'paid_amount' => $request->paid_amount,
            'balance_amount' => max(0, $order->total_amount - $request->paid_amount),
            'return_amount' => max(0, $request->paid_amount - $order->total_amount)
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->stockLogs()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    
}
