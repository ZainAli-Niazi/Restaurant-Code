<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('status', true);
        }])->where('status', true)->get();

        return view('pos.index', compact('categories'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'table_number' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'sub_total' => 'required|numeric|min:0',
            'service_charges' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,completed,hold'
        ]);

        DB::beginTransaction();

        try {
            // Generate unique order number
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_number' => $orderNumber,
                'table_number' => $request->table_number,
                'status' => $request->status,
                'sub_total' => $request->sub_total,
                'service_charges' => $request->service_charges ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount ?? 0,
                'balance_amount' => max(0, $request->total_amount - ($request->paid_amount ?? 0)),
                'return_amount' => max(0, ($request->paid_amount ?? 0) - $request->total_amount)
            ]);

            foreach ($request->items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'total' => $item['quantity'] * $item['price'] * (1 - ($item['discount_percentage'] ?? 0) / 100)
                ]);

                // Update product stock and sold count
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);

                // Create stock log entry
                StockLog::create([
                    'product_id' => $item['product_id'],
                    'user_id' => optional(auth())->id(),
                    'order_id' => $order->id,
                    'quantity' => -$item['quantity'],
                    'action_type' => 'sale',
                    'reference' => $orderNumber
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order saved successfully',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getHeldOrders()
    {
        $heldOrders = Order::with('orderItems.product')
            ->where('status', 'hold')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($heldOrders);
    }

    public function invoice($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('pos.invoice', compact('order'));
    }

    public function kot($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('pos.kot', compact('order'));
    }
}
