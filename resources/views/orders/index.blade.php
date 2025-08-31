@extends('layouts.app')

@section('title', 'Order Management')
@section('header', 'Order Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Order Management</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Order
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Order Records</h5>
                <form method="GET" action="{{ route('orders.index') }}" class="form-inline">
                    <div class="input-group">
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                      
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>Hold</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date/Time</th>
                            <th>Type</th>
                            <th>Table</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->order_type == 'dine-in' ? 'primary' : 
                                    ($order->order_type == 'takeaway' ? 'warning' : 'success') 
                                }}">
                                    {{ ucfirst($order->order_type) }}
                                </span>
                            </td>
                            <td>{{ $order->table_number ?? '-' }}</td>
                            <td>{{ $order->customer_name ?? 'Walk-in' }}</td>
                            <td>₨{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status == 'completed' ? 'success' : 
                                    ($order->status == 'pending' ? 'warning' : 'info') 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('pos.invoice', $order->id) }}" target="_blank" class="btn btn-info" title="Print Invoice">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    <a href="{{ route('pos.kot', $order->id) }}" target="_blank" class="btn btn-secondary" title="Print KOT">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
