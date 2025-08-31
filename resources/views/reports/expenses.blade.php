@extends('layouts.app')

@section('title', 'Expense Report')
@section('header', 'Expense Analysis')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-chart-pie me-2"></i>Expense Report
            </h5>
        </div>
        
        <div class="card-body">
            <!-- Filters Section -->
            <form method="GET" action="{{ route('reports.expenses') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                               value="{{ $dateRange['from_formatted'] }}" max="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                               value="{{ $dateRange['to_formatted'] }}" max="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Summary Card -->
            <div class="alert alert-info mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Total Expenses:</strong> 
                        <span class="fs-4">Rs{{ number_format($totalExpenses, 2) }}</span>
                    </div>
                    <div>
                        <strong>Period:</strong> 
                        {{ $dateRange['from_formatted'] }} to {{ $dateRange['to_formatted'] }}
                    </div>
                </div>
            </div>
            
            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="70%">Category</th>
                            <th width="30%" class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseData as $data)
                        <tr>
                            <td>{{ $data->category }}</td>
                            <td class="text-end">Rs{{ number_format($data->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-4">No expenses found for the selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($expenseData->isNotEmpty())
                    <tfoot class="table-light">
                        <tr>
                            <th>Total</th>
                            <th class="text-end">Rs{{ number_format($totalExpenses, 2) }}</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .alert-info {
        background-color: #e7f5ff;
        border-color: #d0ebff;
    }
</style>
@endpush