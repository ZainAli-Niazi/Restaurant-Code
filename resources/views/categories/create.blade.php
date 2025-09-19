@extends('layouts.app')

@section('title', 'Add New Category')
@section('header', 'Add New Category')

@section('content')
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0">Create New Category</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Enter category name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                        value="1" {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active Status
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i> Save Category
                                </button>
                                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                </a>
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
        (function() {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

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
    </script>
@endsection
@endsection
