@extends('layouts.app')

@section('title', 'POS')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')


@section('content')
    <div class="container-fluid px-0">
        <div id="alertContainer"></div>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <input type="hidden" id="orderStoreRoute" value="{{ route('pos.order.store') }}">

        <div class="row g-3 g-lg-4">
            <!-- Products & Categories (Right Side) -->
            <div class="col-12 col-lg-6 order-1 order-lg-2">
                <!-- Categories -->
                <div class="card-soft p-3 mb-3">
                    <div class="d-flex flex-wrap gap-2" id="categoryButtons">
                        @foreach ($categories as $category)
                            <button class="btn btn-outline-primary btn-pill category-btn" data-id="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Products -->
                <div class="card-soft p-3">
                    <div class="row g-3" id="productGrid">
                        @foreach ($categories as $category)
                            @foreach ($category->products as $product)
                                <div class="col-6 col-sm-4 col-xl-3  product-item" data-category-id="{{ $category->id }}">
                                    <div class="card-soft product-card" data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                        <div class="product-content">
                                            <div class="product-name">{{ $product->name }}</div>
                                            {{-- <div class="product-price">₨ {{ number_format($product->price, 2) }}</div> --}}
                                            <div class="btn-add"><i class="bi bi-plus"></i><span>ADD</span></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order List (Left Side) -->
            <div class="col-12 col-lg-6 order-2 order-lg-1">
                <div class="card-soft p-3 d-flex flex-column" style="height: 100%">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Order List</h6>
                        <div class="d-flex align-items-center gap-2">
                            <select id="tableSelect" class="form-select form-select-sm" style="width:auto;">
                                <option value="T1">T1</option>
                                <option value="T2">T2</option>
                                <option value="T3">T3</option>
                                <option value="T4">T4</option>
                            </select>
                            <input type="text" id="searchInput" class="form-control form-control-sm"
                                placeholder="Search products…" style="width: 180px;">
                        </div>
                    </div>

                    <!-- Scrollable Order Table -->
                    <div class="order-table-wrap">
                        <table class="table align-middle mb-0" id="orderTable">
                            <thead>
                                <tr>
                                    <th style="width:26%">Item</th>
                                    <th style="width:16%" class="text-end">Price</th>
                                    <th style="width:12%" class="text-center">Qty</th>
                                    <th style="width:22%" class="text-center">Discount %</th>
                                    <th style="width:20%" class="text-end">Total</th>
                                    <th style="width:10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- JS will inject order rows here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom: Calculation + Actions -->
    <div class="sticky-bottom-bar">
        <div class="container-fluid">
            <!-- First Row: Inputs + Totals -->
            <div class="row m-0 align-items-end total-calculations-wrap">
                <div class="col-6 col-sm-2">
                    <div class="calc-label">Quantity (items)</div>
                    <div class="calc-total" id="totalItems">0</div>
                </div>
                <div class="col-6 col-sm-2">
                    <div class="calc-label">Sub Total</div>
                    <div class="calc-total" id="subTotal">₨ 0</div>
                </div>
                <div class="col-6 col-sm-2">
                    <label class="form-label calc-label mb-1">Service Charges</label>
                    <input type="number" class="form-control form-control-sm" id="serviceCharges" value="0"
                        min="0">
                </div>

                <!-- Discount with % shown on right side -->
                <div class="col-6 col-sm-2">
                    <label class="form-label calc-label mb-1 d-flex justify-content-between">
                        <span class="text-secondary">Disc %: <span id="discountPercentAuto">0%</span></span>
                    </label>
                    <input type="number" class="form-control form-control-sm" id="discountAmount" value="0"
                        min="0">
                </div>


                <div class="col-6 col-sm-2">
                    <div class="calc-label">Total Amount</div>
                    <div class="calc-total" id="totalAmount">₨ 0</div>
                </div>

                <!-- Paid with Balance/Return on right side -->
                <div class="col-6 col-sm-2">
                    <label class="form-label calc-label mb-1 calc-paid">
                        <span>Paid</span>
                        <span class="small text-secondary mt-1 text-end">
                            Balance: <span id="balanceAmount">₨ 0</span> |
                            Return: <span id="returnAmount">₨ 0</span>
                        </span>
                    </label>
                    <input type="number" class="form-control form-control-sm" id="paidAmount" value="0"
                        min="0">
                </div>
            </div>

            <!-- Second Row: Action Buttons -->
            <div class="row g-2 g-sm-3 action-buttons">
                <div class="col-6 col-sm-4 col-lg-2">
                    <button id="btnPaySave" class="btn btn-success w-100 btn-pill">
                        <i class="bi bi-credit-card me-1"></i> Pay & Save
                    </button>
                </div>
                <div class="col-6 col-sm-4 col-lg-2">
                    <button id="btnHold" class="btn btn-primary w-100 btn-pill">
                        <i class="bi bi-pause-circle me-1"></i> Hold
                    </button>
                </div>

                <div class="col-6 col-sm-4 col-lg-3">
                    <button id="btnKOT" class="btn btn-secondary w-100 btn-pill">
                        <i class="bi bi-receipt me-1"></i> KOT
                    </button>
                </div>
                <div class="col-6 col-sm-4 col-lg-2">
                    <button id="btnPrint" class="btn btn-warning w-100 btn-pill">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>

                <div class="col-6 col-sm-4 col-lg-2">
                    <button id="btnReset" class="btn btn-danger w-100 btn-pill">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.restaurantInfo = {
            name: "{{ $restaurantSettings['restaurant_name'] ?? 'Restaurant' }}",
            address: "{{ $restaurantSettings['restaurant_address'] ?? '' }}",
            phone: "{{ $restaurantSettings['restaurant_phone'] ?? '' }}",

        };
    </script>

    <script src="{{ asset('assets/js/pos.js') }}"></script>
@endpush
