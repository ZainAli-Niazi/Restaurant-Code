@extends('layouts.app')

@section('title', 'Edit Expense')
@section('header', 'Edit Expense')

@section('content')
<div class="container-fluid px-0">
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-pencil-square me-2"></i>Edit Expense
            </h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <!-- Date Field -->
                    <div class="col-md-6">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $expense->date->format('Y-m-d')) }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Reference Field -->
                    <div class="col-md-6">
                        <label for="reference" class="form-label">Reference Number</label>
                        <input type="text" 
                               class="form-control @error('reference') is-invalid @enderror" 
                               id="reference" 
                               name="reference" 
                               value="{{ old('reference', $expense->reference) }}"
                               placeholder="Enter reference number">
                        @error('reference')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Category Field -->
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category" 
                                required>
                            <option value="">Select Category</option>
                            @foreach(['Supplies', 'Utilities', 'Rent', 'Salaries', 'Maintenance', 'Other'] as $category)
                                <option value="{{ $category }}" {{ old('category', $expense->category) == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Amount Field -->
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   value="{{ old('amount', $expense->amount) }}" 
                                   required
                                   placeholder="0.00">
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Description Field -->
                    <div class="col-12">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  required
                                  placeholder="Enter expense details">{{ old('description', $expense->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                            <div>
                                <button type="reset" class="btn btn-secondary me-2">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Expense
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Client-side validation
    (function () {
        'use strict'
        
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
    
    // Set today's date as default if date field is empty
    document.addEventListener('DOMContentLoaded', function() {
        const dateField = document.getElementById('date');
        if (dateField && !dateField.value) {
            const today = new Date().toISOString().split('T')[0];
            dateField.value = today;
        }
        
        // Auto-focus the first field
        const firstInput = document.querySelector('form input:not([type="hidden"])');
        if (firstInput) firstInput.focus();
    });
</script>
@endsection
@endsection