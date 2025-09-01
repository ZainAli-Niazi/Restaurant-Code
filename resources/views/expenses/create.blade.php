@extends('layouts.app')

@section('title', 'Add New Expense')
@section('header', 'Record New Expense')

@section('content')
<div class="container-fluid px-0">
    <div class="card">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> New Expense Entry</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">
                    <!-- Date -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" name="date" id="date"
                                class="form-control @error('date') is-invalid @enderror"
                                value="{{ old('date', date('Y-m-d')) }}" required>
                            <label for="date">Expense Date</label>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Reference -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="reference" id="reference"
                                class="form-control @error('reference') is-invalid @enderror"
                                value="{{ old('reference') }}" placeholder="REF-001">
                            <label for="reference">Reference Number (Optional)</label>
                            @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="category" id="category"
                                class="form-select @error('category') is-invalid @enderror" required>
                                <option value="" disabled selected>Select a category</option>
                                @foreach(['Rent','Utilities','Salaries','Supplies','Maintenance','Marketing','Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="category">Expense Category</label>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.01" name="amount" id="amount"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old('amount') }}" required placeholder="0.00">
                            <label for="amount">Amount ({{ config('restaurant.currency', 'Rs') }})</label>
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                style="height: 100px" required
                                placeholder="Enter expense details">{{ old('description') }}</textarea>
                            <label for="description">Expense Description</label>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Attachment -->
                    <div class="col-12">
                        <label for="attachment" class="form-label">Receipt/Attachment (Optional)</label>
                        <input type="file" name="attachment" id="attachment"
                            class="form-control @error('attachment') is-invalid @enderror"
                            accept="image/*,.pdf">
                        <div class="form-text">Upload JPG, PNG, or PDF (Max 2MB)</div>
                        @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times-circle me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Bootstrap Client-side Validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Auto-focus on first input
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('date').focus();
    });
</script>
@endsection
