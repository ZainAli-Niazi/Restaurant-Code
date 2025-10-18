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

        // === Dashboard KPIs ===
        $todaySales = Order::whereDate('created_at', $today)
            ->where('status', 'completed') // Only completed orders
            ->sum('total_amount');

        $todayOrders = Order::whereDate('created_at', $today)
            ->where('status', 'completed') // Only completed orders
            ->count();

        $todayExpenses = Expense::whereDate('date', $today)->sum('amount');

        $currentShift = Shift::whereNull('end_time')->first();
        $shiftBalance = $currentShift ? ($currentShift->ending_cash ?? $currentShift->starting_cash) : 0;

        // === Top Selling Products (Today) - Only from completed orders ===
        $topSellingItems = OrderItem::with('product')
            ->whereHas('order', function ($query) use ($today) {
                $query->whereDate('created_at', $today)
                    ->where('status', 'completed'); // Only completed orders
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total) as total_amount')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // === Low Stock Products ===
        $lowStockItems = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        // === Recent Orders - Include all statuses for monitoring ===
        $recentOrders = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // === Sales Chart Data (Last 30 Days) - Only completed orders ===
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $salesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed') // Only completed orders
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'ASC')
            ->get();

        // === Prepare Data for Chart ===
        $chartLabels = [];
        $chartData = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $daySales = $salesData->firstWhere('date', $formattedDate);
            $chartData[] = $daySales ? (float) $daySales->total_sales : 0;
        }

        // === Additional Stats for Better Insights ===
        $totalCompletedOrders = Order::where('status', 'completed')->count();
        $totalHoldOrders = Order::where('status', 'hold')->count();
        $totalPendingOrders = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'todaySales',
            'todayOrders',
            'todayExpenses',
            'shiftBalance',
            'topSellingItems',
            'lowStockItems',
            'recentOrders',
            'chartLabels',
            'chartData',
            'totalCompletedOrders',
            'totalHoldOrders',
            'totalPendingOrders'
        ));
    }
}