@extends('layouts.app')

@section('title', 'Order Details')
@section('header', 'Order Details')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Order #{{ $order->order_number }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Table:</strong> {{ $order->table_number ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Total Amount:</strong> ₨{{ number_format($order->total_amount, 2) }}</p>

            <h6>Items:</h6>
            <ul>
                @foreach($order->orderItems as $item)
                    <li>{{ $item->product->name }} - {{ $item->quantity }} × ₨{{ number_format($item->price, 2) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
