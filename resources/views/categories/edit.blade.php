@extends('layouts.app')

@section('title', 'Edit Category')
@section('header', 'Edit Category')

@section('content')
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Category: {{ $category->name }}</h5>
                    <div class="badge bg-primary">
                        ID: {{ $category->id }}
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $category->name) }}" required
                                    placeholder="Enter category name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="status"
                                        name="status" value="1"
                                        {{ old('status', $category->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active Status
                                    </label>
                                </div>

                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-2"></i> Update Category
                                </button>

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
