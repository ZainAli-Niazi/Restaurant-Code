@extends('layouts.app')

@section('title', 'Category Management')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 fw-semibold">
                <i class="bi bi-tags me-2"></i> Category Management
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <!-- Add Category -->
            <a href="{{ route('categories.create') }}" class="btn btn-success shadow px-4 py-2 rounded-pill me-2">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>

            <!-- Add Product -->
            <a href="{{ route('products.create') }}" class="btn btn-primary shadow px-4 py-2 rounded-pill me-2">
                <i class="bi bi-box-seam me-1"></i> Add Product
            </a>

            <!-- All Products -->
            <a href="{{ route('products.index') }}" class="btn btn-primary shadow px-4 py-2 rounded-pill">
                <i class="bi bi-card-list me-1"></i> All Products
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Icon</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Created</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>

                            <!-- Category Icon -->
                            <td>
                                @if($category->icon)
                                    <img src="{{ asset('category-icons/' . $category->icon) }}"
                                        alt="{{ $category->name }}"
                                        width="40" height="40"
                                        class="rounded border"
                                        style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center border"
                                        style="width:40px;height:40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Category Name -->
                            <td class="fw-semibold text-dark">
                                {{ $category->name }}
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="badge rounded-pill bg-{{ $category->status ? 'success' : 'secondary' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <!-- Product Count -->
                            <td>
                                
                                <span class="badge bg-primary rounded-pill">
                                    {{ $category->products_count }}
                                </span>
                          
                            </td>
 

                            <!-- Created Date -->
                            <td>
                                <small class="text-muted" data-bs-toggle="tooltip"
                                    title="{{ $category->created_at->format('M j, Y g:i A') }}">
                                    {{ $category->created_at->diffForHumans() }}
                                </small>
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Edit -->
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="btn-action edit-btn"
                                        data-bs-toggle="tooltip" title="Edit Category">
                                        <i class="bi bi-pencil"></i>
                                    </a>
 

                                  
                                     
                                       <!-- Delete Button -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            data-bs-toggle="tooltip" title="Delete  ">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x display-6 d-block mb-2"></i>
                                No categories found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
 
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Action Buttons */
    .btn-action {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 16px;
        box-shadow: 2px 2px 6px rgba(0,0,0,0.08),
                    -2px -2px 6px rgba(255,255,255,0.8);
        transition: all 0.25s ease-in-out;
    }

    .btn-action:hover {
        transform: scale(1.15);
        color: #fff;
    }

    .edit-btn:hover {
        background-color: #0d6efd;
    }

    .delete-btn:hover {
        background-color: #dc3545;
    }

    .info-btn:hover {
        background-color: #0dcaf0;
    }

  
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
