@extends('layouts.app')

@section('title', 'Product Management')
@section('header', 'Product Management')

@section('content')
<div class="container-fluid px-0">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="mb-0">Product Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                        <tr class="align-middle">
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" 
                                         class="rounded me-3" width="40" height="40">
                                    @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width:40px;height:40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                    @endif
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td><code>{{ $product->code }}</code></td>
                            <td>₨{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="{{ $product->stock < 10 ? 'text-danger fw-bold' : '' }}">
                                    {{ $product->stock }}
                                    @if($product->stock < 10)
                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $product->status ? 'success' : 'secondary' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this product?')"
                                                data-bs-toggle="tooltip" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-box-seam display-6 d-block mb-2"></i>
                                No products found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
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

@section('scripts')
<script>
    $(document).ready(function () {
        // Enable tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Low stock warning
        $('.text-danger').closest('tr').addClass('bg-light-warning');
    });
</script>
@endsection