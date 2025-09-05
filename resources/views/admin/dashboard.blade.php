@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-0">

    {{-- Dashboard Stats --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow h-100 py-2">
                <div class="card-body">
                    <h5>Today's Sales</h5>
                    <h3>₨ {{ number_format($todaySales, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white shadow h-100 py-2">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h3>{{ $todayOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white shadow h-100 py-2">
                <div class="card-body">
                    <h5>Today's Expenses</h5>
                    <h3>₨ {{ number_format($todayExpenses, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-dark shadow h-100 py-2">
                <div class="card-body">
                    <h5>Shift Balance</h5>
                    <h3>₨ {{ number_format($shiftBalance, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Chart --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Sales (Last 30 Days)</h5>
                </div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="salesChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">Recent Orders</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>₨ {{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Selling Items --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">Top Selling Products</div>
                <ul class="list-group list-group-flush">
                    @foreach($topSellingItems as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $item->product->name ?? 'N/A' }}
                            <span class="badge bg-primary">{{ $item->total_quantity }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Low Stock Items --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">Low Stock Products</div>
                <ul class="list-group list-group-flush">
                    @foreach($lowStockItems as $product)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $product->name }}
                            <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Sales (PKR)',
            data: @json($chartData),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            fill: true,
            tension: 0.3,
            pointRadius: 5,
            pointBackgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
