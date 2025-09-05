@extends('layouts.app')

@section('title', 'Order Details')
@section('header', 'Order Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <div class="card shadow-lg border-0 rounded-4">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-receipt-cutoff me-2"></i> Order #{{ $order->order_number }}
                    </h4>
                    <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm rounded-pill shadow-sm">
                        <i class="bi bi-arrow-left-circle me-1"></i> Back
                    </a>
                </div>

                <div class="card-body px-4 py-4">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Date:</strong> <span class="text-muted">{{ $order->created_at->format('d M, Y h:i A') }}</span></p>
                            <p><strong>Table:</strong> <span class="badge bg-dark">{{ $order->table_number ?? '—' }}</span></p>
                            <p><strong>Customer:</strong> <span class="text-primary fw-semibold">{{ $order->customer_name ?? 'Walk-in' }}</span></p>
                        </div>

                        <div class="col-md-6">
                            <p>
                                <strong>Status:</strong>
                                <span class="badge rounded-pill px-3 py-2 bg-{{ 
                                    $order->status == 'completed' ? 'success' : 
                                    ($order->status == 'pending' ? 'warning' : 'info') 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p>
                                <strong>Order Type:</strong>
                                <span class="badge rounded-pill px-3 py-2 bg-{{ 
                                    $order->order_type == 'dine-in' ? 'primary' :
                                    ($order->order_type == 'takeaway' ? 'warning' : 'success')
                                }}">
                                    {{ ucfirst($order->order_type) }}
                                </span>
                            </p>
                            <p>
                                <strong>Total Amount:</strong> 
                                <span class="fw-bold fs-5 text-success">
                                    ₨{{ number_format($order->total_amount, 2) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="my-3">

                    <!-- Order Items Table -->
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-basket2-fill me-2 text-primary"></i> Order Items
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle rounded-3 shadow-sm">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Qty</th>
                                    <th class="text-end">Price (₨)</th>
                                    <th class="text-end">Subtotal (₨)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->product->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-dark">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end">₨{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end text-success fw-bold">₨{{ number_format($item->quantity * $item->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end fs-5 fw-bold text-success">₨{{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('pos.invoice', $order->id) }}" target="_blank" class="btn btn-info shadow-sm rounded-pill px-4">
                            <i class="bi bi-printer-fill me-1"></i> Print Invoice
                        </a>
                        <a href="{{ route('pos.kot', $order->id) }}" target="_blank" class="btn btn-secondary shadow-sm rounded-pill px-4">
                            <i class="bi bi-printer me-1"></i> Print KOT
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-dark shadow-sm rounded-pill px-4">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back to Orders
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }
    .card {
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
    table th, table td {
        vertical-align: middle !important;
    }
</style>
@endsection
