@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <h1>Shifts</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('shifts.create') }}" class="btn btn-primary mb-3">Start New Shift</a>

    {{-- 🔹 Filter Section --}}
    <form method="GET" action="{{ route('shifts.index') }}" class="row g-3 mb-4">
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
    <table class="table table-bordered">
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
                    <td>{{ $shift->starting_cash }}</td>
                    <td>{{ $shift->ending_cash ?? '-' }}</td>
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
                        <a href="{{ route('shifts.show', $shift->id) }}" class="btn btn-info btn-sm">View</a>
                        @if($shift->isActive())
                        <button 
                            class="btn btn-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#endShiftModal" 
                            data-shift-id="{{ $shift->id }}">
                            End Shift
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

    {{ $shifts->links() }}
</div>

{{-- End Shift Modal (same as before) --}}
<div class="modal fade" id="endShiftModal" tabindex="-1" aria-labelledby="endShiftModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="endShiftForm" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="endShiftModalLabel">End Shift</h5>
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
        form.action = "/shifts/" + shiftId + "/end"; // dynamic action
    });
});
</script>
@endsection
