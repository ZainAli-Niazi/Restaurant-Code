@extends('layouts.app')

@section('title', 'Product Management')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0 fw-semibold">
                <i class="bi bi-box-seam me-2"></i> Product Management
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <!-- Add Product Button -->
            <a href="{{ route('products.create') }}" class="btn btn-success shadow px-4 py-2 rounded-pill me-2">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
            <!-- Add Category Button -->
            <a href="{{ route('categories.create') }}" class="btn btn-primary shadow px-4 py-2 rounded-pill">
                <i class="bi bi-tags me-1"></i> Add Category
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <!-- Serial Number -->
                            <td class="ps-4">{{ $loop->iteration }}</td>

                            <!-- Product Name with Image -->
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}" class="rounded me-3 border"
                                            width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center border"
                                            style="width:40px;height:40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <span class="fw-semibold">{{ $product->name }}</span>
                                </div>
                            </td>

                            <!-- Product Code -->
                            <td><code>{{ $product->code }}</code></td>

                            <!-- Product Price -->
                            <td>₨{{ number_format($product->price, 2) }}</td>

                            <!-- Product Stock -->
                            <td>
                                <span class="fw-semibold {{ $product->stock < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $product->stock }}
                                </span>
                                @if ($product->stock < 10)
                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                                        data-bs-toggle="tooltip" title="Low Stock"></i>
                                @endif
                            </td>

                            <!-- Category -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $product->category->name }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="badge rounded-pill bg-{{ $product->status ? 'success' : 'secondary' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Edit Button -->
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn-action edit-btn" data-bs-toggle="tooltip" title="Edit Product">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            data-bs-toggle="tooltip" title="Delete Product">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam display-6 d-block mb-2"></i>
                                No products found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
            @endif
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
</style>
@endsection

@section('scripts')
<script>
    // Enable Bootstrap Tooltips
    document.addEventListener("DOMContentLoaded", function() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
