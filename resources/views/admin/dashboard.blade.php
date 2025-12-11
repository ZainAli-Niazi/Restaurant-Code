@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="pos-container  py-2">

    {{-- Dashboard Stats --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Today's Sales</h6>
                            <h3 class="mb-0">${{ number_format($todaySales, 2) }}</h3>
                           
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Today's Orders</h6>
                            <h3 class="mb-0">{{ $todayOrders }}</h3>
                           
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Today's Expenses</h6>
                            <h3 class="mb-0">$ {{ number_format($todayExpenses, 2) }}</h3>
                       
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Shift Balance</h6>
                            <h3 class="mb-0">$ {{ number_format($shiftBalance, 2) }}</h3>
                         
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Sales Chart --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Sales Performance (Last 30 Days)</h6>
                    <small class="opacity-75">Completed Orders Only</small>
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
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <span>Recent Orders</span>
                    <small>All Statuses</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Table</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number ?? $order->id }}</td>
                                        <td>{{ $order->table_number ?? 'N/A' }}</td>
                                        <td>$ {{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $order->status == 'completed' ? 'bg-success' : 
                                                   ($order->status == 'hold' ? 'bg-warning' : 'bg-secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d M, Y H:i') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Section --}}
    <div class="row">
        {{-- Top Selling Items --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span>Top Selling Products</span>
                    <small>Today - Completed Orders</small>
                </div>
                <div class="card-body p-0">
                    @if($topSellingItems->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($topSellingItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">$ {{ number_format($item->total_amount, 2) }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $item->total_quantity }} sold</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No sales from completed orders today
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Low Stock Items --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    Low Stock Products
                </div>
                <div class="card-body p-0">
                    @if($lowStockItems->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockItems as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $product->stock }} left</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle me-2"></i>
                            All products have sufficient stock
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Sales (PKR) - Completed Orders',
                data: @json($chartData),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    labels: {
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            return 'Sales: $ ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$ ' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endsection