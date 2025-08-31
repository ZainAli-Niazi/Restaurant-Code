<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        // Group By (day, week, month)
        $groupBy = $request->get('group_by', 'day');

        $salesQuery = DB::table('orders')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->groupBy('date')
            ->orderBy('date');

        $salesData = $salesQuery->get()->map(function ($item) {
            $item->date = \Carbon\Carbon::parse($item->date);
            return $item;
        });

        // Totals
        $totalRevenue = $salesData->sum('revenue');
        $totalOrders  = $salesData->sum('orders');

        // Previous Period for Comparison
        $previousFrom = \Carbon\Carbon::parse($dateRange['from'])->subDays($dateRange['days']);
        $previousTo   = \Carbon\Carbon::parse($dateRange['from'])->subDay();

        $previousSales = DB::table('orders')
            ->selectRaw('COUNT(*) as orders, SUM(total_amount) as revenue')
            ->whereBetween('created_at', [$previousFrom, $previousTo])
            ->first();

        $previousRevenue = $previousSales->revenue ?? 0;
        $previousOrders  = $previousSales->orders ?? 0;

        // Change Percentages
        $revenueChangePercentage = $previousRevenue > 0
            ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
            : 100;

        $ordersChangePercentage = $previousOrders > 0
            ? round((($totalOrders - $previousOrders) / $previousOrders) * 100, 1)
            : 100;

        $aovCurrent = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $aovPrevious = $previousOrders > 0 ? $previousRevenue / $previousOrders : 0;

        $aovChangePercentage = $aovPrevious > 0
            ? round((($aovCurrent - $aovPrevious) / $aovPrevious) * 100, 1)
            : 100;

        // Best Day
        $bestDay = $salesData->sortByDesc('revenue')->first();

        return view('reports.sales', compact(
            'salesData',
            'dateRange',
            'totalRevenue',
            'totalOrders',
            'revenueChangePercentage',
            'ordersChangePercentage',
            'aovChangePercentage',
            'bestDay'
        ));
    }

    public function productReport(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $productData = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateRange['from'], $dateRange['to']])
            ->selectRaw('products.name, SUM(order_items.quantity) as sold_quantity, SUM(order_items.total) as total_revenue')
            ->groupBy('products.name')
            ->orderByDesc('sold_quantity')
            ->get();

        // Summary row (total of all products)
        $summary = [
            'total_quantity' => $productData->sum('sold_quantity'),
            'total_revenue'  => $productData->sum('total_revenue'),
        ];

        return view('reports.products', compact('productData', 'dateRange', 'summary'));
    }

    public function expenseReport(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $expenseData = Expense::whereBetween('date', [$dateRange['from'], $dateRange['to']])
            ->selectRaw('category, SUM(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        $totalExpenses = $expenseData->sum('total_amount');

        return view('reports.expenses', compact('expenseData', 'totalExpenses', 'dateRange'));
    }

    public function profitLossReport(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        // Calculate total revenue
        $revenue = Order::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
            ->sum('total_amount');

        // Calculate total expenses
        $expenses = Expense::whereBetween('date', [$dateRange['from'], $dateRange['to']])
            ->sum('amount');

        $profit = $revenue - $expenses;

        return view('reports.profit-loss', compact('revenue', 'expenses', 'profit', 'dateRange'));
    }

    private function getDateRange(Request $request)
    {
        $fromDate = $request->input('from_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->format('Y-m-d'));

        return [
            'from' => Carbon::parse($fromDate)->startOfDay(),
            'to' => Carbon::parse($toDate)->endOfDay(),
            'from_formatted' => $fromDate,
            'to_formatted' => $toDate,
            'days' => Carbon::parse($fromDate)->diffInDays(Carbon::parse($toDate)) + 1,
        ];
    }
}
