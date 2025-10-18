@extends('layouts.app')

@section('title', 'POS')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')

@section('content')
    <div class="pos-container">
        <div id="alertContainer"></div>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <input type="hidden" id="orderStoreRoute" value="{{ isset($order) ? route('pos.order.update', $order->id) : route('pos.order.store') }}">
        <input type="hidden" id="isEditMode" value="{{ isset($order) ? 'true' : 'false' }}">
        <input type="hidden" id="currentOrderId" value="{{ $order->id ?? '' }}">

        @if(isset($order))
        <input type="hidden" id="orderData" value="{{ json_encode([
            'order_number' => $order->order_number,
            'table_number' => $order->table_number,
            'status' => $order->status,
            'sub_total' => $order->sub_total,
            'service_charges' => $order->service_charges,
            'discount_amount' => $order->discount_amount,
            'total_amount' => $order->total_amount,
            'paid_amount' => $order->paid_amount,
            'items' => $order->orderItems->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'discount_percentage' => $item->discount_percentage,
                    'total' => $item->total
                ];
            })->toArray()
        ]) }}">
        @endif

        <div class="pos-flex col-12">
            <!-- ===== Left: Order List (40% width) ===== -->
            <div class="pos-left card-soft col-5">
                <div class="order-header">
                    <h6 class="mb-0">Order List {{ isset($order) ? '(Editing: ' . $order->order_number . ')' : '' }}</h6>
                    <div class="order-tools">
                        <select id="tableSelect" class="form-select form-select-sm">
                            <option value="T1">T1</option>
                            <option value="T2">T2</option>
                            <option value="T3">T3</option>
                            <option value="T4">T4</option>
                        </select>
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Search products…">
                    </div>
                </div>

                <div class="order-table-wrap">
                    <table class="table align-middle mb-0" id="orderTable">
                        <thead>
                            <tr>
                                <th style="width:26%">Item</th>
                                <th style="width:16%" class="text-end">Price</th>
                                <th style="width:10%" class="text-center">Qty</th>
                                <th style="width:26%" class="text-center">Discount</th>
                                <th style="width:20%" class="text-end">Total</th>
                                <th style="width:10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- JS injects order rows -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===== Right: Categories + Products ===== -->
            <div class="pos-right col-7">
                <!-- Categories -->
                <div class="card-soft p-3 category-card">
                    <div class="cat-arrows">
                        <button type="button" class="cat-arrow" id="catPrev">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <button type="button" class="cat-arrow" id="catNext">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>

                    <div id="categoryButtons" class="category-row">
                         <button class="category-btn hover-effect" data-id="all">
                            All
                        </button>
                        @foreach ($categories as $category)
                            <button class="category-btn hover-effect" data-id="{{ $category->id }}">
                                <div class="category-icon-wrapper">
                                    <img src="{{ asset('category-icons/' . $category->icon) }}" alt="{{ $category->name }}"
                                        class="category-icon">
                                </div>
                                <span class="category-name">{{ $category->name }}</span>
                            </button>
                        @endforeach
                    </div>

                </div>


                <!-- Products -->
                <div class="card-soft p-3">
                    <div class="product-grid" id="productGrid">
                        @foreach ($categories as $category)
                            @foreach ($category->products as $product)
                                <div class="product-item" data-category-id="{{ $category->id }}">
                                    <div class="card-soft product-card" data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                        <div class="product-content">
                                            <div class="product-name">{{ $product->name }}</div>
                                            <div class="btn-add">
                                                <i class="bi bi-plus"></i>
                                                <span>ADD</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Sticky Bottom Bar ===== -->
    <div class="sticky-bottom-bar">
        <div class="container-fluid">
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

            <!-- Action Buttons -->
            <div class="row g-2 g-sm-3 action-buttons">
                <div><button id="btnPaySave" class="btn btn-success w-100 btn-pill">
                    <i class="bi bi-credit-card me-1"></i> {{ isset($order) ? 'Update & Save' : 'Pay & Save' }}
                </button></div>
                <div><button id="btnHold" class="btn btn-primary w-100 btn-pill"><i
                            class="bi bi-pause-circle me-1"></i> Hold</button></div>
                <div><button id="btnKOT" class="btn btn-secondary w-100 btn-pill"><i class="bi bi-receipt me-1"></i>
                        KOT</button></div>
                <div><button id="btnPrint" class="btn btn-warning w-100 btn-pill"><i class="bi bi-printer me-1"></i>
                        Print</button></div>
                <div><button id="btnReset" class="btn btn-danger w-100 btn-pill"><i
                            class="bi bi-arrow-counterclockwise me-1"></i> Reset</button></div>
            </div>
        </div>
    </div>
@endsection
 