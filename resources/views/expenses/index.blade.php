@extends('layouts.app')

@section('title', 'Expense Management')
@section('header', 'Expense Management')

@section('content')
<div class="container-fluid px-0">
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Expense Records
                </h5>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Expense
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            <!-- Filter Section -->
            <div class="border-bottom p-3 bg-light">
                <form method="GET" action="{{ route('expenses.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" 
                                   value="{{ request('from_date') }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" 
                                   value="{{ request('to_date') }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Summary Card -->
            <div class="p-3 border-bottom">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Expenses</h6>
                                        <h4 class="mb-0 text-danger">₨{{ number_format($expenses->sum('amount'), 2) }}</h4>
                                    </div>
                                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-money-bill-wave text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-muted">Records Found</h6>
                                        <h4 class="mb-0">{{ $expenses->total() }}</h4>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-list text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-muted">Average Daily</h6>
                                        <h4 class="mb-0">Rs{{ number_format($averageDaily, 2) }}</h4>
                                    </div>
                                    <div class="bg-success bg-opacity-10 p-3 rounded">
                                        <i class="fas fa-chart-line text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expenses Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Date</th>
                            <th class="text-nowrap">Reference</th>
                            <th class="text-nowrap">Category</th>
                            <th>Description</th>
                            <th class="text-nowrap text-end">Amount</th>
                            <th class="text-nowrap">Added By</th>
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td class="text-nowrap">{{ $expense->date->format('d M Y') }}</td>
                            <td class="text-nowrap">
                                <span class="badge bg-light text-dark">{{ $expense->reference ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="text-truncate" style="max-width: 250px;" title="{{ $expense->description }}">
                                {{ $expense->description }}
                            </td>
                            <td class="text-end text-danger fw-bold">
                                ₨{{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <span class="avatar-title rounded-circle bg-light text-dark">
                                            {{ substr($expense->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    {{ $expense->user->name }}
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('expenses.edit', $expense->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this expense?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('expenses.show', $expense->id) }}" 
                                       class="btn btn-sm btn-outline-secondary"
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                No expenses found matching your criteria
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination and Summary -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted">
                    Showing {{ $expenses->firstItem() }} to {{ $expenses->lastItem() }} of {{ $expenses->total() }} entries
                </div>
                <div>
                    {{ $expenses->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-title {
        font-size: 0.8rem;
        font-weight: 600;
    }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Set max date for "to_date" when "from_date" changes
        document.getElementById('from_date').addEventListener('change', function() {
            const fromDate = this.value;
            const toDateInput = document.getElementById('to_date');
            if (fromDate) {
                toDateInput.min = fromDate;
                if (toDateInput.value && toDateInput.value < fromDate) {
                    toDateInput.value = fromDate;
                }
            }
        });
        
        // Set min date for "from_date" when "to_date" changes
        document.getElementById('to_date').addEventListener('change', function() {
            const toDate = this.value;
            const fromDateInput = document.getElementById('from_date');
            if (toDate) {
                fromDateInput.max = toDate;
                if (fromDateInput.value && fromDateInput.value > toDate) {
                    fromDateInput.value = toDate;
                }
            }
        });
    });
</script>
@endpush