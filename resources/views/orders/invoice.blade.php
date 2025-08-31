<!DOCTYPE html>
<html>
<head>
    <title>Invoice - Order #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { width: 100%; padding: 20px; border: 1px solid #ddd; }
        .header { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h2>Invoice</h2>
            <p>Order #{{ $order->order_number }}</p>
        </div>
        <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Table:</strong> {{ $order->table_number ?? '-' }}</p>
        <p><strong>Customer:</strong> {{ $order->customer_name ?? 'Walk-in' }}</p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="text-align:right; margin-top:20px;">
            Grand Total: ₹{{ number_format($order->total_amount, 2) }}
        </h4>
    </div>
</body>
</html>
