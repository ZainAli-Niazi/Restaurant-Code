@extends('layouts.app')

@section('title', 'Profit & Loss Report')
@section('header', 'Profit & Loss Analysis')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-chart-line me-2 text-primary"></i> Profit & Loss Report
                <small class="text-muted fs-6">(Completed Orders Only)</small>
            </h5>
            <a href="{{ route('reports.profit-loss') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-sync-alt"></i> Reset
            </a>
        </div>
        
        <div class="card-body">
            <!-- Info Alert -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Revenue calculations include <strong>completed orders only</strong>. Hold orders are excluded from profit calculations.
            </div>

            <!-- Date Filter -->
            <form method="GET" action="{{ route('reports.profit-loss') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <!-- From Date -->
                    <div class="col-md-5">
                        <label for="from_date" class="form-label fw-bold">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                            value="{{ $dateRange['from_formatted'] }}" max="{{ date('Y-m-d') }}">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-5">
                        <label for="to_date" class="form-label fw-bold">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                            value="{{ $dateRange['to_formatted'] }}" max="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="row g-3">
                <!-- Total Revenue -->
                <div class="col-md-4">
                    <div class="card border-success shadow-sm h-100">
                        <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-arrow-up me-2"></i>Total Revenue</span>
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-success fw-bold">Rs {{ number_format($revenue, 2) }}</h3>
                            <small class="text-muted">From Completed Orders</small>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="col-md-4">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-header bg-danger text-white fw-bold d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-arrow-down me-2"></i>Total Expenses</span>
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-danger fw-bold">Rs {{ number_format($expenses, 2) }}</h3>
                            <small class="text-muted">Operational Costs</small>
                        </div>
                    </div>
                </div>

                <!-- Net Profit / Loss -->
                <div class="col-md-4">
                    <div class="card border-{{ $profit >= 0 ? 'success' : 'danger' }} shadow-sm h-100">
                        <div class="card-header bg-{{ $profit >= 0 ? 'success' : 'danger' }} text-white fw-bold d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-balance-scale me-2"></i>
                                Net {{ $profit >= 0 ? 'Profit' : 'Loss' }}
                            </span>
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-{{ $profit >= 0 ? 'success' : 'danger' }} fw-bold">
                                Rs {{ number_format(abs($profit), 2) }}
                            </h3>
                            <div class="small text-muted">
                                {{ $profitMargin = $revenue ? round(($profit/$revenue)*100, 2) : 0 }}% Margin
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- If No Data -->
            @if($revenue == 0 && $expenses == 0)
                <div class="alert alert-warning text-center mt-4 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    No data available for the selected date range.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection