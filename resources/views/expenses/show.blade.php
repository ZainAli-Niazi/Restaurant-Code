@extends('layouts.app')

@section('title', 'Expense Details')
@section('header', 'Expense Details')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Expense Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Date</strong>
                        <span class="fs-5">{{ $expense->date->format('d M Y') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Reference</strong>
                        <span class="fs-5">{{ $expense->reference ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Category</strong>
                        <span class="fs-5 badge bg-primary">{{ $expense->category }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Amount</strong>
                        <span class="fs-5 text-danger">₨{{ number_format($expense->amount, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Added By</strong>
                        <span class="fs-5">{{ $expense->user->name }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Added On</strong>
                        <span class="fs-5">{{ $expense->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <strong class="d-block text-muted small mb-2">Description</strong>
                <p class="mb-0">{{ $expense->description }}</p>
            </div>
            
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Edit Expense
                </a>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Expenses
                </a>
            </div>
        </div>
    </div>
</div>
@endsection