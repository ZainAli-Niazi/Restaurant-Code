<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>KOT - Order #{{ $order->id }}</title>
  <style>
    /* ===== Global Styling ===== */
    * {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    body {
      width: 58mm;
      margin: 0 auto;
      padding: 5px;
    }

    .text-center {
      text-align: center;
    }

    .line {
      border-top: 1px dashed #000;
      margin: 5px 0;
    }

    /* ===== KOT Header ===== */
    .kot-title {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      margin-bottom: 2px;
    }

    .order-info {
      font-size: 13px;
      font-weight: bold;
      margin-top: 2px;
      margin-bottom: 2px;
    }

    .order-meta {
      font-size: 12px;
      margin-bottom: 2px;
    }

    /* ===== Table Styling ===== */
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 4px;
    }

    .table th {
      text-align: left;
      padding: 4px 0;
      font-weight: bold;
      font-size: 13px;
      border-bottom: 1px solid #000;
    }

    .table td {
      padding: 3px 0;
      font-size: 13px;
    }

    /* ===== Highlighted Qty ===== */
    .qty {
      font-weight: bold;
      text-align: center;
    }

    /* ===== Footer Styling ===== */
    .footer {
      margin-top: 6px;
      font-size: 11px;
      text-align: center;
      font-style: italic;
    }
  </style>
</head>
<body onload="window.print()">

  <!-- KOT Header -->
  <div class="text-center">
    <div class="kot-title">Kitchen Order Ticket</div>
    <div class="order-info">Order #{{ $order->id }}</div>
    <div class="order-meta">Table: <strong>{{ $order->table_number ?? '-' }}</strong></div>
    <div class="order-meta">Time: {{ $order->created_at->format('d M Y - H:i') }}</div>
  </div>

  <div class="line"></div>

  <!-- KOT Items Table -->
  <table class="table">
    <thead>
      <tr>
        <th>Item</th>
        <th style="text-align:center;">Qty</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($order->orderItems as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td class="qty">{{ $item->quantity }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="line"></div>

  <!-- Footer -->
  <div class="footer">
    Please prepare the order quickly.
  </div>

</body>
</html>
