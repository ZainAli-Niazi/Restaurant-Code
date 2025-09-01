@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-4">Shift Details</h1>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow border-0 rounded-3">
        <div class="card-body">
            <div class="row g-3">
                {{-- Shift Name --}}
                <div class="col-md-6">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-tag text-primary me-2"></i> Shift Name:
                    </p>
                    <p class="text-muted">{{ $shift->name }}</p>
                </div>

                {{-- User --}}
                <div class="col-md-6">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-user text-success me-2"></i> User:
                    </p>
                    <p class="text-muted">{{ $shift->user->name ?? 'N/A' }}</p>
                </div>

                {{-- Starting Cash --}}
                <div class="col-md-6">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-wallet text-warning me-2"></i> Starting Cash:
                    </p>
                    <p class="text-muted">₨ {{ number_format($shift->starting_cash, 2) }}</p>
                </div>

                {{-- Ending Cash --}}
                <div class="col-md-6">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-hand-holding-usd text-info me-2"></i> Ending Cash:
                    </p>
                    <p class="text-muted">
                        @if($shift->ending_cash !== null)
                            ₨ {{ number_format($shift->ending_cash, 2) }}
                        @else
                            <span class="badge bg-secondary">Not Closed</span>
                        @endif
                    </p>
                </div>

                {{-- Start Time --}}
                <div class="col-md-6">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-clock text-primary me-2"></i> Start Time:
                    </p>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($shift->start_time)->format('d M Y, h:i A') }}</p>
                </div>

                {{-- End Time --}}
                <div class="col-md-6">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-hourglass-end text-danger me-2"></i> End Time:
                    </p>
                    <p class="text-muted">
                        @if($shift->end_time)
                            {{ \Carbon\Carbon::parse($shift->end_time)->format('d M Y, h:i A') }}
                        @else
                            <span class="badge bg-secondary">Ongoing</span>
                        @endif
                    </p>
                </div>

                {{-- Notes --}}
                <div class="col-12">
                    <p class="mb-2 fw-bold">
                        <i class="fas fa-sticky-note text-dark me-2"></i> Notes:
                    </p>
                    <p class="text-muted">{{ $shift->notes ?? 'No notes provided' }}</p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Shifts
                </a>

                @if(!$shift->end_time)
                    <form action="{{ route('shifts.end', $shift->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to end this shift?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-stop-circle"></i> End Shift
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
