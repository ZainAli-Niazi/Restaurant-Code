@extends('layouts.app')

@section('title', 'Profit & Loss Report')
@section('header', 'Profit & Loss Analysis')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>Profit & Loss Report
            </h5>
        </div>
        
        <div class="card-body">
            <!-- Date Filter -->
            <form method="GET" action="{{ route('reports.profit-loss') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                               value="{{ $dateRange['from_formatted'] }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                               value="{{ $dateRange['to_formatted'] }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-rupee-sign me-2"></i>Total Revenue</h6>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-success">Rs {{ number_format($revenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-danger h-100">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0"><i class="fas fa-rupee-sign me-2"></i>Total Expenses</h6>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-danger">Rs {{ number_format($expenses, 2) }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card border-{{ $profit >= 0 ? 'success' : 'danger' }} h-100">
                        <div class="card-header bg-{{ $profit >= 0 ? 'success' : 'danger' }} text-white">
                            <h6 class="mb-0"><i class="fas fa-rupee-sign me-2"></i>Net {{ $profit >= 0 ? 'Profit' : 'Loss' }}</h6>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-{{ $profit >= 0 ? 'success' : 'danger' }}">
                                Rs {{ number_format(abs($profit), 2) }}
                            </h3>
                            <div class="small text-muted">
                                {{ $profitMargin = $revenue ? round(($profit/$revenue)*100, 2) : 0 }}% Margin
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
