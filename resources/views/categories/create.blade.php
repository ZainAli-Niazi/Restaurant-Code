@extends('layouts.app')

@section('title', 'Add New Category')
@section('header', 'Add New Category')

@section('content')
    <div class="container-fluid">
        <div>
            <div class="col-12">
                <!-- Header -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Create New Category</h5>

                            </div>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Categories
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('categories.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <h6 class="section-title mb-3">Basic Information</h6>

                                        <!-- Category Name -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">
                                                Category Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-tag text-muted"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    name="name" value="{{ old('name') }}"
                                                    placeholder="Enter category name" required>
                                            </div>
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Choose a unique and descriptive name for your category
                                            </div>
                                        </div>

                                        <!-- Status Toggle -->
                                        <div class="mb-4">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status"
                                                    name="status" value="1"
                                                    {{ old('status', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">
                                                    <span class="fw-medium">Active Category</span>
                                                </label>
                                            </div>
                                            <div class="form-text">Inactive categories won't be available for product
                                                assignment</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Icon Selection -->
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        {{-- icon search --}}
                                        <h6 class="section-title mb-3">
                                            <input type="text" id="iconFilter" class="form-control"
                                                placeholder="Filter icons...">
                                        </h6>

                                        @error('icon')
                                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror


                                        <div class="icon-selection-grid">
                                            @foreach ($icons as $icon)
                                                <div class="icon-option-wrapper">
                                                    <input type="radio" name="icon" value="{{ $icon }}"
                                                        id="icon-{{ $icon }}" class="icon-option-input"
                                                        {{ old('icon') === $icon ? 'checked' : '' }} required>
                                                    <label for="icon-{{ $icon }}" class="icon-option-label">
                                                        <div class="icon-preview">
                                                            <img src="{{ asset('category-icons/' . $icon) }}"
                                                                alt="{{ $icon }}" class="icon-image">
                                                        </div>
                                                        <span
                                                            class="icon-name">{{ pathinfo($icon, PATHINFO_FILENAME) }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if ($icons->isEmpty())
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                No icons found. Please add SVG icons to the category-icons directory.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 border-top pt-4">

                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-check-circle me-2"></i>Create Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('styles')
    <style>
        .section-title {
            color: #2d3748;
            font-weight: 600;
            font-size: 1rem;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        input {
            border: none !important;
            outline: none !important;
        }

        input::placeholder {
            color: #2d3748;
            font-weight: 600;
            font-size: 1rem;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .icon-selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
            max-height: 400px;
            overflow-y: auto;
            padding: 1rem;
            border: 1px solid #e3e6f0;
            border-radius: 0.75rem;
            background: #f8f9fc;
        }

        .icon-option-wrapper {
            position: relative;
        }

        .icon-option-input {
            position: absolute;
            opacity: 0;
        }

        .icon-option-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0.5rem;
            border: 2px solid transparent;
            border-radius: 0.75rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .icon-option-input:checked+.icon-option-label {
            border-color: #4e73df;
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
        }

        .icon-option-label:hover {
            border-color: #aab7d1;
            transform: translateY(-1px);
        }

        .icon-preview {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            border-radius: 12px;
            padding: 8px;
        }

        .icon-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .icon-name {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
            word-break: break-word;
        }

        .form-check-input:checked {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .input-group-text {
            background-color: #f8f9fc;
            border-color: #d1d3e2;
        }
    </style>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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

            // Real-time input validation
            const nameInput = document.getElementById('name');
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });
            }
        })();

        // jQuery Filter for icons
        $(document).ready(function() {
            $('#iconFilter').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.icon-option-wrapper').filter(function() {
                    $(this).toggle($(this).find('.icon-name').text().toLowerCase().indexOf(value) >
                        -1);
                });
            });
        });
    </script>
@endsection
