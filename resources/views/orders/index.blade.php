@extends('layouts.app')

@section('title', 'Order Management')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')

@section('content')
    <div class="container-fluid py-2">

        <!-- Page Header -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark">
                     Order Management
                </h2>

            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('pos.index') }}" class="btn btn-create px-4 py-2 rounded-pill">
                    <i class="bi bi-plus-circle me-2"></i> Create New Order
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Orders</h6>
                                <h3 class="mb-0 fw-bold">{{ $orders->total() }}</h3>
                            </div>
                            <i class="bi bi-cart-check display-6 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Completed</h6>
                                <h3 class="mb-0 fw-bold">{{ $orders->where('status', 'completed')->count() }}</h3>
                            </div>
                            <i class="bi bi-check-circle display-6 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>

        <!-- Orders Card -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 rounded-top-4 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-check me-2 text-primary"></i>Order Records
                    </h5>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('orders.index') }}"
                        class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar"></i></span>
                            <input type="date" class="form-control filter-input border-start-0" name="from_date"
                                value="{{ request('from_date') }}">
                        </div>

                        <span class="fw-bold text-muted">to</span>

                        <div class="input-group input-group-sm" style="width: 120px;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar"></i></span>
                            <input type="date" class="form-control filter-input border-start-0" name="to_date"
                                value="{{ request('to_date') }}">
                        </div>

                        <select class="form-select filter-input" name="status" style="width: 150px;">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>On Hold</option>
                        </select>

                        <button type="submit" class="btn btn-filter d-flex align-items-center gap-2 rounded-pill px-3">
                            <i class="bi bi-funnel"></i> Filter
                        </button>

                        @if (request()->hasAny(['from_date', 'to_date', 'status']))
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-gradient">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Date / Time</th>
                                <th>Table</th>

                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th width="280" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="order-row">
                                    <td class="fw-bold ps-4">
                                        <div class="d-flex align-items-center">
                                            <span class="order-badge">#{{ $order->id }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                            <small class="fw-bold">{{ $order->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($order->table_number)
                                            <span class="table-badge">{{ $order->table_number }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $order->orderItems->count() }} items
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success fs-6">
                                        $ {{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge status-badge status-{{ $order->status }}">
                                            <i
                                                class="bi bi-{{ $order->status === 'completed' ? 'check-circle' : ($order->status === 'pending' ? 'clock' : 'pause-circle') }} me-1"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">

                                          
                                            <a  
                                                href="{{ route('pos.kot', $order->id) }}" class="btn-action kot-btn">
                                                <i class="bi bi-printer me-2"></i> 
                                            </a>
                                            <a  
                                                href="{{ route('pos.invoice', $order->id) }}" class="btn-action invoice-btn">
                                                <i class="bi bi-receipt me-2"></i> 
                                            </a>

                                            <!-- View Details -->
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn-action view-btn"
                                                title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Edit Order -->
                                            <a href="{{ route('pos.edit', $order->id) }}" class="btn-action edit-btn"
                                                title="Edit Order">
                                                <i class="bi bi-pencil"></i>
                                            </a>



                                            <!-- Delete -->
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action delete-btn"
                                                    title="Delete Order">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox display-1 d-block mb-3 opacity-25"></i>
                                        <h5>No orders found</h5>
                                        <p class="mb-0">Start by creating a new order from the POS system.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="card-footer bg-white border-0 rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of
                                {{ $orders->total() }} results
                            </div>
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .table-responsive {
       overflow: hidden;
      -webkit-overflow-scrolling: hidden;
    }
    /* Create New Order Button */
    .btn-create {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.25);
        position: relative;
        overflow: hidden;
    }

    .btn-create:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 25px rgba(25, 135, 84, 0.35);
        color: #fff;
    }

    /* Stat Cards with Glass Morphism */
    .stat-card {
        border-radius: 20px;
        border: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(10px);
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.6s;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Filter Section */
    .filter-input {
        height: 42px;
        border-radius: 12px;
        border: 1.5px solid #e8f5e8;
        font-size: 14px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }

    .filter-input:focus {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
        transform: translateY(-1px);
        background: white;
    }

    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
        color: #fff;
        height: 42px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-radius: 12px;
    }

    .btn-filter:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    /* Premium Table Header */
    .table-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #764ba2 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .table-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .table-gradient th {
        border: none;
        font-weight: 600;
        padding: 1.25rem 0.75rem;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(5px);
    }

    /* Enhanced Order Rows */
    .order-row {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-left: 4px solid transparent;
    }

    .order-row:hover {
        background: linear-gradient(135deg, #f8fff8 0%, #f0fff0 100%) !important;
        transform: translateX(8px) scale(1.005);
        border-left-color: #198754;
        box-shadow: 0 5px 20px rgba(25, 135, 84, 0.1);
    }

    /* Premium Badge Designs */
    .order-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .table-badge {
        background: linear-gradient(135deg, #198754, #20c997);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        box-shadow: 0 3px 10px rgba(25, 135, 84, 0.2);
    }

    /* Enhanced Status Badges */
    .status-badge {
        padding: 0.6rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .status-completed {
        background: linear-gradient(135deg, #198754, #20c997);
        color: #fff;
    }

    .status-hold {
        background: linear-gradient(135deg, #6c757d, #a0a0a0);
        color: #fff;
    }

    /* Premium Action Buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transition: all 0.4s ease;
        transform: translate(-50%, -50%);
    }

    .btn-action:hover::before {
        width: 100%;
        height: 100%;
        border-radius: 12px;
    }

    .btn-action:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }

    .kot-btn {
        background: linear-gradient(135deg, #17a2b8, #20c997);
        color: white;
    }

    .invoice-btn {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }

    .view-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .edit-btn {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
    }

    .delete-btn {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        color: white;
    }

    /* Enhanced Card Styling */
    .card {
        border-radius: 24px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        transition: all 0.4s ease;
    }

    .card:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    /* Empty State Enhancement */
    .bi-inbox {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endsection