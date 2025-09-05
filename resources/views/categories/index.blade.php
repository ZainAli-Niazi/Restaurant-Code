@extends('layouts.app')

@section('title', 'Category Management')
@section('header', 'Category Management')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0">Category Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Category Name</th>
                            <th width="120">Status</th>
                            <th width="100">Products</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-folder text-white"></i>
                                    </div>
                                    <span>{{ $category->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $category->status ? 'success' : 'danger' }} rounded-pill">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $category->products_count }}
                                </span>
                            </td>
                           
                             <!-- Actions -->
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Edit Button -->
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                        class="btn-action edit-btn" data-bs-toggle="tooltip" title="Edit">
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
                            <td colspan="5" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-folder-x text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">No categories found</p>
                                    <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Create Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
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
    $(document).ready(function () {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endsection