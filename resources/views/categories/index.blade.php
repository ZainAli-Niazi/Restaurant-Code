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
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(145deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
        color: var(--muted);
        font-size: 16px;
        box-shadow: var(--shadow),
                    inset 1px 1px 2px rgba(255,255,255,0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s ease;
    }

    .btn-action:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4),
                    inset 1px 1px 2px rgba(255,255,255,0.15);
        color: var(--white);
    }

    .btn-action:hover::before {
        left: 100%;
    }

    .edit-btn {
        background: linear-gradient(145deg, rgba(81,226,245,0.15), rgba(81,226,245,0.05));
        border: 1px solid rgba(81,226,245,0.2);
        color: var(--primary);
    }

    .edit-btn:hover {
        background: linear-gradient(135deg, var(--primary), rgba(81,226,245,0.8));
        box-shadow: 0 8px 25px rgba(81,226,245,0.3),
                    inset 1px 1px 2px rgba(255,255,255,0.2);
        border-color: var(--primary);
    }

    .delete-btn {
        background: linear-gradient(145deg, rgba(229,112,112,0.15), rgba(229,112,112,0.05));
        border: 1px solid rgba(229,112,112,0.2);
        color: #e57070;
    }

    .delete-btn:hover {
        background: linear-gradient(135deg, #e57070, rgba(229,112,112,0.8));
        box-shadow: 0 8px 25px rgba(229,112,112,0.3),
                    inset 1px 1px 2px rgba(255,255,255,0.2);
        border-color: #e57070;
    }

    .info-btn {
        background: linear-gradient(145deg, rgba(157,249,239,0.15), rgba(157,249,239,0.05));
        border: 1px solid rgba(157,249,239,0.2);
        color: var(--secondary);
    }

    .info-btn:hover {
        background: linear-gradient(135deg, var(--secondary), rgba(157,249,239,0.8));
        box-shadow: 0 8px 25px rgba(157,249,239,0.3),
                    inset 1px 1px 2px rgba(255,255,255,0.2);
        border-color: var(--secondary);
    }

    /* Success button variant */
    .success-btn {
        background: linear-gradient(145deg, rgba(207,255,99,0.15), rgba(207,255,99,0.05));
        border: 1px solid rgba(207,255,99,0.2);
        color: var(--accent);
    }

    .success-btn:hover {
        background: linear-gradient(135deg, var(--accent), rgba(207,255,99,0.8));
        box-shadow: 0 8px 25px rgba(207,255,99,0.3),
                    inset 1px 1px 2px rgba(255,255,255,0.2);
        border-color: var(--accent);
        color: var(--dark);
    }

    /* Warning button variant */
    .warning-btn {
        background: linear-gradient(145deg, rgba(255,193,7,0.15), rgba(255,193,7,0.05));
        border: 1px solid rgba(255,193,7,0.2);
        color: #ffc107;
    }

    .warning-btn:hover {
        background: linear-gradient(135deg, #ffc107, rgba(255,193,7,0.8));
        box-shadow: 0 8px 25px rgba(255,193,7,0.3),
                    inset 1px 1px 2px rgba(255,255,255,0.2);
        border-color: #ffc107;
    }

    /* Button group styling */
    .btn-group-action {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* Loading state */
    .btn-action.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-action.loading::after {
        content: '';
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Pulse animation for important actions */
    .btn-action.pulse {
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% {
            box-shadow: var(--shadow),
                       inset 1px 1px 2px rgba(255,255,255,0.1),
                       0 0 0 0 rgba(81,226,245,0.4);
        }
        70% {
            box-shadow: var(--shadow),
                       inset 1px 1px 2px rgba(255,255,255,0.1),
                       0 0 0 8px rgba(81,226,245,0);
        }
        100% {
            box-shadow: var(--shadow),
                       inset 1px 1px 2px rgba(255,255,255,0.1),
                       0 0 0 0 rgba(81,226,245,0);
        }
    }

    /* Disabled state */
    .btn-action:disabled {
        opacity: 0.5;
        pointer-events: none;
        transform: none;
        box-shadow: none;
    }

    /* Focus state for accessibility */
    .btn-action:focus {
        outline: none;
        box-shadow: var(--shadow),
                   inset 1px 1px 2px rgba(255,255,255,0.1),
                   0 0 0 3px rgba(81,226,245,0.3);
    }

    /* Small size variant */
    .btn-action-sm {
        width: 30px;
        height: 30px;
        font-size: 14px;
        padding: 8px;
    }

    /* Large size variant */
    .btn-action-lg {
        width: 44px;
        height: 44px;
        font-size: 18px;
        padding: 12px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-group-action {
            gap: 6px;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
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
