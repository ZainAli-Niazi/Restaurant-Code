@extends('layouts.app')

@section('title', 'Product Sales Report')
@section('header', 'Product Sales Report')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>Product Sales Report
            </h5>
        </div>
        
        <div class="card-body">
            <!-- Filters Section -->
            <form method="GET" action="{{ route('reports.products') }}" class="mb-4">
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
            
            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Quantity Sold</th>
                            <th class="text-end">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productData as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td class="text-end">{{ $data->sold_quantity }}</td>
                            <td class="text-end">Rs{{ number_format($data->total_revenue, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">No sales data found for the selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($productData->isNotEmpty())
                    <tfoot class="table-light">
                        <tr>
                            <th>Total</th>
                            <th class="text-end">{{ $summary['total_quantity'] }}</th>
                            <th class="text-end">Rs{{ number_format($summary['total_revenue'], 2) }}</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection