<!DOCTYPE html>
<html>
<head>
    <title>KOT - Order #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .kot-box { width: 100%; padding: 20px; border: 1px dashed #000; }
        .header { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <div class="kot-box">
        <div class="header">
            <h2>KOT (Kitchen Order Ticket)</h2>
            <p>Order #{{ $order->order_number }}</p>
        </div>
        <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Table:</strong> {{ $order->table_number ?? '-' }}</p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->remarks ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
