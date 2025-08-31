@extends('layouts.app')

@section('title', 'Sales Report')
@section('header', 'Sales Analytics Report')

@section('content')
<div class="container-fluid px-0">
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Sales Performance
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary" id="printReport">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button class="btn btn-sm btn-outline-primary" id="exportExcel">
                        <i class="fas fa-file-excel me-1"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('reports.sales') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" 
                           value="{{ $dateRange['from_formatted'] }}" max="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" 
                           value="{{ $dateRange['to_formatted'] }}" max="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="group_by" class="form-label">Group By</label>
                    <select class="form-select" id="group_by" name="group_by">
                        <option value="day" {{ request('group_by', 'day') == 'day' ? 'selected' : '' }}>Daily</option>
                        <option value="week" {{ request('group_by') == 'week' ? 'selected' : '' }}>Weekly</option>
                        <option value="month" {{ request('group_by') == 'month' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Revenue</h6>
                            <h3 class="card-title text-primary">Rs {{ number_format($totalRevenue, 2) }}</h3>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up"></i> 
                                {{ $revenueChangePercentage }}% from previous period
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Orders</h6>
                            <h3 class="card-title text-success">{{ $totalOrders }}</h3>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up"></i> 
                                {{ $ordersChangePercentage }}% from previous period
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Avg. Order Value</h6>
                            <h3 class="card-title text-info">Rs {{ number_format($totalRevenue / max($totalOrders, 1), 2) }}</h3>
                            <div class="text-success small">
                                <i class="fas fa-arrow-up"></i> 
                                {{ $aovChangePercentage }}% from previous period
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Best Day</h6>
                            @if($bestDay)
                                <h3 class="card-title text-warning">
                                    {{ $bestDay->date->format('D, M j') }}
                                </h3>
                                <div class="text-muted small">
                                    Rs {{ number_format($bestDay->revenue, 2) }} from {{ $bestDay->orders }} orders
                                </div>
                            @else
                                <h3 class="card-title text-warning">N/A</h3>
                                <div class="text-muted small">No data available</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="salesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="salesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">Date</th>
                                    <th class="text-nowrap text-end">Orders</th>
                                    <th class="text-nowrap text-end">Revenue</th>
                                    <th class="text-nowrap text-end">Avg. Order</th>
                                    <th class="text-nowrap text-end">% of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData as $data)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ $data->date->format('D, M j, Y') }}
                                    </td>
                                    <td class="text-end">{{ $data->orders }}</td>
                                    <td class="text-end">Rs {{ number_format($data->revenue, 2) }}</td>
                                    <td class="text-end">Rs {{ number_format($data->revenue / max($data->orders, 1), 2) }}</td>
                                    <td class="text-end">{{ number_format(($data->revenue / max($totalRevenue, 1)) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">{{ $totalOrders }}</th>
                                    <th class="text-end">Rs {{ number_format($totalRevenue, 2) }}</th>
                                    <th class="text-end">Rs {{ number_format($totalRevenue / max($totalOrders, 1), 2) }}</th>
                                    <th class="text-end">100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($salesData->map(fn($item) => $item->date->format('M j'))) !!},
                datasets: [
                    {
                        label: 'Revenue (Rs)',
                        data: {!! json_encode($salesData->pluck('revenue')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Number of Orders',
                        data: {!! json_encode($salesData->pluck('orders')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (Rs)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += 'Rs ' + context.parsed.y.toFixed(2);
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Print Report
        document.getElementById('printReport').addEventListener('click', function() {
            window.print();
        });

        // Export to Excel
        document.getElementById('exportExcel').addEventListener('click', function() {
            const table = document.getElementById('salesTable');
            const workbook = XLSX.utils.table_to_book(table);
            XLSX.writeFile(workbook, 'Sales_Report_{{ now()->format('Y-m-d') }}.xlsx');
        });

        // Set default date range if empty
        if (!document.getElementById('from_date').value) {
            document.getElementById('from_date').value = '{{ now()->subDays(30)->format('Y-m-d') }}';
        }
        if (!document.getElementById('to_date').value) {
            document.getElementById('to_date').value = '{{ now()->format('Y-m-d') }}';
        }
    });
</script>
@endpush
