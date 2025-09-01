@extends('layouts.app')

@section('title', 'Manage Shifts')

@section('content')
<style>
    /* Page Styling */
    body {
        background-color: #f8f9fa;
    }

    .shift-container {
        padding: 25px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.4s ease-in-out;
        margin-bottom: 30px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Heading */
    .shift-heading {
        font-size: 28px;
        font-weight: 700;
        color: #34495e;
        margin-bottom: 20px;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056d2);
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        transition: 0.3s;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0056d2, #003f99);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 123, 255, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1e7e34, #155d27);
        transform: translateY(-2px);
    }

    /* Flash Messages */
    .alert {
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
    }

    /* Filter Section */
    .filter-section {
        background: #fdfdfd;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    /* Table Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

   

    .table td {
        background: #fff;
        text-align: center;
        padding: 12px;
        border-top: 1px solid #f1f1f1;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.2s ease-in-out;
    }

    .table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    /* Badges */
    .badge {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge.bg-success {
        background-color: #28a745 !important;
    }

    .badge.bg-secondary {
        background-color: #6c757d !important;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: #007bff;
        color: #fff;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .modal-footer .btn-danger {
        background-color: #dc3545;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    .modal-footer .btn-secondary {
        border-radius: 8px;
    }
</style>

<div class="shift-container">
    <h1 class="shift-heading">Manage Shifts</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    {{-- Start New Shift Button --}}
    <a href="{{ route('shifts.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle me-1"></i> Start New Shift
    </a>

    {{-- Filter Section --}}
    <form method="GET" action="{{ route('shifts.index') }}" class="row g-3 filter-section">
        <div class="col-md-3">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" id="from_date" name="from_date" class="form-control"
                value="{{ request('from_date') }}">
        </div>
        <div class="col-md-3">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" id="to_date" name="to_date" class="form-control"
                value="{{ request('to_date') }}">
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select">
                <option value="">All</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                <option value="closed" {{ request('status')=='closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-filter me-1"></i> Apply Filters
            </button>
        </div>
    </form>

    {{-- Shifts Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Shift Name</th>
                    <th>User</th>
                    <th>Starting Cash</th>
                    <th>Ending Cash</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                    <tr>
                        <td>{{ $shift->id }}</td>
                        <td>{{ $shift->name }}</td>
                        <td>{{ $shift->user->name ?? 'N/A' }}</td>
                        <td>₨ {{ number_format($shift->starting_cash, 2) }}</td>
                        <td>{{ $shift->ending_cash ? '₨ ' . number_format($shift->ending_cash, 2) : '-' }}</td>
                        <td>{{ $shift->start_time }}</td>
                        <td>{{ $shift->end_time ?? '-' }}</td>
                        <td>
                            @if($shift->isActive())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Closed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('shifts.show', $shift->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($shift->isActive())
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#endShiftModal" data-shift-id="{{ $shift->id }}">
                                    <i class="fas fa-stop-circle"></i> End
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No Shifts Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $shifts->links() }}
</div>

{{-- End Shift Modal --}}
<div class="modal fade" id="endShiftModal" tabindex="-1" aria-labelledby="endShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="endShiftForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="endShiftModalLabel"><i class="fas fa-stop-circle me-1"></i> End Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Ending Cash</label>
                        <input type="number" name="ending_cash" step="0.01" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">End Shift</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const endShiftModal = document.getElementById('endShiftModal');
    endShiftModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const shiftId = button.getAttribute('data-shift-id');
        const form = document.getElementById('endShiftForm');
        form.action = "/shifts/" + shiftId + "/end";
    });
});
</script>
@endsection
