@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-4">Start New Shift</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow border-0 rounded-3">
        <div class="card-body">
            <form method="POST" action="{{ route('shifts.store') }}">
                @csrf
                <div class="row g-3">
                    {{-- Shift Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">Shift Name <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror" 
                            placeholder="Enter shift name"
                            value="{{ old('name') }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Starting Cash --}}
                    <div class="col-md-6">
                        <label for="starting_cash" class="form-label fw-bold">Starting Cash <span class="text-danger">*</span></label>
                        <input 
                            type="number" 
                            id="starting_cash"
                            name="starting_cash" 
                            step="0.01" 
                            class="form-control @error('starting_cash') is-invalid @enderror"
                            placeholder="Enter starting cash amount"
                            value="{{ old('starting_cash') }}"
                            required>
                        @error('starting_cash')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label for="notes" class="form-label fw-bold">Notes</label>
                        <textarea 
                            id="notes"
                            name="notes" 
                            class="form-control" 
                            rows="3"
                            placeholder="Optional notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-play"></i> Start Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
