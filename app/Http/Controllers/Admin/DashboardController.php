<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Shift;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Today's sales
        $todaySales = Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total_amount');
        
        // Today's orders
        $todayOrders = Order::whereDate('created_at', $today)->count();
        
        // Today's expenses
        $todayExpenses = Expense::whereDate('date', $today)->sum('amount');
        
        // Current shift balance
        $currentShift = Shift::whereNull('end_time')->first();
        $shiftBalance = $currentShift ? ($currentShift->ending_cash ?? $currentShift->starting_cash) : 0;
        
        // Top selling items today
        $topSellingItems = OrderItem::with('product')
            ->whereHas('order', function($query) use ($today) {
                $query->whereDate('created_at', $today)
                      ->where('status', 'completed');
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_amount'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
        
        // Low stock items
        $lowStockItems = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->limit(5)
            ->get();
        
        // Recent orders
        $recentOrders = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'todaySales',
            'todayOrders',
            'todayExpenses',
            'shiftBalance',
            'topSellingItems',
            'lowStockItems',
            'recentOrders'
        ));
    }
}