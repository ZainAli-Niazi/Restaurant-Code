@extends('layouts.app')

@section('title', 'Add New Expense')
@section('header', 'Record New Expense')

@section('content')
<div class="container-fluid px-0">
    <div class="card">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>New Expense Entry</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <div class="row g-3">
                    <!-- Date Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   required>
                            <label for="date">Expense Date</label>
                            @error('date')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Reference Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('reference') is-invalid @enderror" 
                                   id="reference" 
                                   name="reference" 
                                   value="{{ old('reference') }}"
                                   placeholder="REF-001">
                            <label for="reference">Reference Number (Optional)</label>
                            @error('reference')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Category Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category" 
                                    required>
                                <option value="" disabled selected>Select a category</option>
                                @foreach(['Rent', 'Utilities', 'Salaries', 'Supplies', 'Maintenance', 'Marketing', 'Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <label for="category">Expense Category</label>
                            @error('category')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Amount Field -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   value="{{ old('amount') }}" 
                                   required
                                   placeholder="0.00">
                            <label for="amount">Amount</label>
                            <div class="input-group-text bg-transparent border-0 position-absolute end-0 top-0 h-100">
                                {{ config('restaurant.currency', 'Rs') }}
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Description Field -->
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      style="height: 100px" 
                                      required
                                      placeholder="Enter expense details">{{ old('description') }}</textarea>
                            <label for="description">Expense Description</label>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Attachment Field -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Receipt/Attachment (Optional)</label>
                            <input class="form-control @error('attachment') is-invalid @enderror" 
                                   type="file" 
                                   id="attachment" 
                                   name="attachment"
                                   accept="image/*,.pdf">
                            <div class="form-text">Upload JPG, PNG or PDF (Max 2MB)</div>
                            @error('attachment')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
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
    
    // Auto-focus on first field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('date').focus();
    });
</script>
@endsection
@endsection