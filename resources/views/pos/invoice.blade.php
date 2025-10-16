<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ strtoupper($restaurantSettings['restaurant_name'] ?? '') }}</title>
    <style>
        @page {
            margin: 0;
              width: 80mm;
        }

        body {
            margin: 10px;
           
            width: 80mm;
            font-family: Arial, sans-serif;
        }

        * {
            font-size: 12px;
            line-height: 1.4;
        }

        .text-center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        /* Restaurant Header Styling */
        .restaurant-header {
            margin-bottom: 5px;
        }

        .restaurant-logo {
            max-height: 40px;
            margin-bottom: 5px;
        }

        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .restaurant-contact {
            font-size: 12px;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 3px 0;
            text-align: left;
            font-size: 12px;
        }

        .summary td {
            padding: 3px 0;
            font-size: 12px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 14px;
        }

        /* Thank You Message */
        .thank-you {
            margin-top: 6px;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="text-center restaurant-header">
        @if (!empty($restaurantSettings['restaurant_logo']))
            <img src="{{ asset('storage/' . $restaurantSettings['restaurant_logo']) }}" alt="Logo" class="restaurant-logo">
        @endif
        <div class="restaurant-name">{{ strtoupper($restaurantSettings['restaurant_name'] ?? '') }}</div>
        <div class="restaurant-contact">Phone: {{ $restaurantSettings['restaurant_phone'] ?? '' }}</div>
        <div class="restaurant-contact">{{ $restaurantSettings['restaurant_address'] ?? '' }}</div>
    </div>

    <div class="line"></div>

    <!-- ORDER INFO -->
    <div>
        <div><strong>Order #:</strong> {{ $order->id }}</div>
        <div><strong>Table:</strong> {{ $order->table_number ?? '-' }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</div>
    </div>
    <div class="line"></div>

    <!-- ORDER ITEMS -->
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>

    <!-- SUMMARY -->
    <table class="table summary">
        <tr>
            <td>Sub Total</td>
            <td>{{ number_format($order->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td>Service Charges</td>
            <td>{{ number_format($order->service_charges, 2) }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td>- {{ number_format($order->discount_amount, 2) }}</td>
        </tr>
        <tr class="grand-total">
            <td>Grand Total</td>
            <td>{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>
    <div class="line"></div>

    <!-- FOOTER -->
    <div class="text-center thank-you">Thank you for dining with us!</div>

</body>
</html>
