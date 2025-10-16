@extends('layouts.app')

@section('title', 'Order Management')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark">
                <i class="bi bi-card-checklist me-2"></i> Order Management
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('pos.index') }}" class="btn btn-create px-4 py-2 rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Create New Order
            </a>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 rounded-top-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold">Order Records</h5>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('orders.index') }}" class="d-flex align-items-center gap-2">
                    <input type="date" class="form-control filter-input" 
                           name="from_date" value="{{ request('from_date') }}" placeholder="dd/mm/yyyy">

                    <span class="fw-bold">to</span>

                    <input type="date" class="form-control filter-input" 
                           name="to_date" value="{{ request('to_date') }}" placeholder="dd/mm/yyyy">

                    <select class="form-select filter-input" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>Hold</option>
                    </select>

                    <button type="submit" class="btn btn-filter d-flex align-items-center gap-1 rounded-pill px-4">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date / Time</th>
                            <th>Table</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="fw-bold">{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d M Y - h:i A') }}</td>
                                <td>{{ $order->table_number ?? '-' }}</td>
                                <td>{{ $order->customer_name ?? 'Walk-in' }}</td>
                                <td class="fw-bold text-success">₨ {{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <!-- View -->
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn-action view-btn" title="View Order">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Invoice -->
                                        <a href="{{ route('pos.invoice', $order->id) }}"   class="btn-action invoice-btn" title="Print Invoice">
                                            <i class="bi bi-receipt"></i>
                                        </a>

                                        <!-- KOT -->
                                        <a href="{{ route('pos.kot', $order->id) }}" class="btn-action kot-btn" title="Print KOT">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this order?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action delete-btn" title="Delete Order">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Create New Order Button */
    .btn-create {
        background-color: #198754;
        border: none;
        color: #fff;
        font-weight: 500;
        transition: 0.2s ease-in-out;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn-create:hover {
        background-color: #146c43;
        color: #fff;
    }

    /* Inputs aur Select */
    .filter-input {
        height: 42px;
        border-radius: 50px !important;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        padding: 0 15px;
        box-shadow: none !important;
    }

    .filter-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13,110,253,.15) !important;
    }

    /* Filter Button */
    .btn-filter {
        background-color: #0066ff;
        border: none;
        font-weight: 500;
        color: #fff;
        height: 42px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        transition: 0.2s ease-in-out;
    }
    .btn-filter:hover {
        background-color: #004ecc;
        color: #fff;
    }

    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 18px;
        box-shadow: 3px 3px 8px rgba(0,0,0,0.1),
                    -3px -3px 8px rgba(255,255,255,0.9);
        transition: all 0.25s ease-in-out;
    }
    .btn-action:hover { transform: scale(1.15); color: #fff; }
    .view-btn:hover { background-color: #0d6efd; }
    .invoice-btn:hover { background-color: #198754; }
    .kot-btn:hover { background-color: #6c757d; }
    .delete-btn:hover { background-color: #dc3545; }

    /* Card Styling */
    .card { border-radius: 16px; }

    /* Table hover effects */
    table.table tbody tr:hover {
        background-color: #f1f5ff;
        transition: 0.2s ease-in-out;
    }
</style>
@endsection
