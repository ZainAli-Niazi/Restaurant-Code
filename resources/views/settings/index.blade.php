@extends('layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <h2 class="mb-4">Restaurant POS Settings</h2>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Restaurant Info --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-store me-2"></i>Restaurant Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.restaurant.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Restaurant Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $settings['restaurant']['name'] ?? '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone', $settings['restaurant']['phone'] ?? '') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $settings['restaurant']['address'] ?? '') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $settings['restaurant']['email'] ?? '') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" id="website" name="website" 
                                   value="{{ old('website', $settings['restaurant']['website'] ?? '') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tax_id" class="form-label">Tax ID</label>
                            <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                   value="{{ old('tax_id', $settings['restaurant']['tax_id'] ?? '') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Restaurant Information
                    </button>
                </form>
            </div>
        </div>

        {{-- POS Settings --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-cash-register me-2"></i>POS Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.pos.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="default_tax" class="form-label">Default Tax Rate (%)</label>
                            <input type="number" class="form-control" id="default_tax" name="default_tax" 
                                   value="{{ old('default_tax', $settings['pos']['default_tax'] ?? 0) }}" min="0" max="30" step="0.1">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="service_charge" class="form-label">Service Charge (%)</label>
                            <input type="number" class="form-control" id="service_charge" name="service_charge" 
                                   value="{{ old('service_charge', $settings['pos']['service_charge'] ?? 0) }}" min="0" max="20" step="0.1">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="default_discount" class="form-label">Default Discount (%)</label>
                            <input type="number" class="form-control" id="default_discount" name="default_discount" 
                                   value="{{ old('default_discount', $settings['pos']['default_discount'] ?? 0) }}" min="0" max="100" step="0.1">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <select class="form-select" id="currency" name="currency">
                                <option value="₹" {{ (old('currency', $settings['pos']['currency'] ?? '₹') == '₹') ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                <option value="$" {{ (old('currency', $settings['pos']['currency'] ?? '$') == '$') ? 'selected' : '' }}>US Dollar ($)</option>
                                <option value="€" {{ (old('currency', $settings['pos']['currency'] ?? '€') == '€') ? 'selected' : '' }}>Euro (€)</option>
                                <option value="£" {{ (old('currency', $settings['pos']['currency'] ?? '£') == '£') ? 'selected' : '' }}>British Pound (£)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="currency_position" class="form-label">Currency Position</label>
                            <select class="form-select" id="currency_position" name="currency_position">
                                <option value="left" {{ (old('currency_position', $settings['pos']['currency_position'] ?? 'left') == 'left') ? 'selected' : '' }}>Left (₹100)</option>
                                <option value="right" {{ (old('currency_position', $settings['pos']['currency_position'] ?? 'right') == 'right') ? 'selected' : '' }}>Right (100₹)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="stock_management" name="stock_management" 
                                   value="1" {{ (old('stock_management', $settings['pos']['stock_management'] ?? 1)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="stock_management">Enable Stock Management</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="low_stock_alert" name="low_stock_alert" 
                                   value="1" {{ (old('low_stock_alert', $settings['pos']['low_stock_alert'] ?? 1)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="low_stock_alert">Enable Low Stock Alerts</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info text-white">
                        <i class="fas fa-save me-1"></i> Save POS Settings
                    </button>
                </form>
            </div>
        </div>

        {{-- Receipt Settings --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Receipt Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.receipt.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="receipt_header" class="form-label">Receipt Header</label>
                            <textarea class="form-control" id="receipt_header" name="receipt_header" rows="3">{{ old('receipt_header', $settings['receipt']['header'] ?? '') }}</textarea>
                            <small class="form-text text-muted">Text to appear at the top of receipts</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="receipt_footer" class="form-label">Receipt Footer</label>
                            <textarea class="form-control" id="receipt_footer" name="receipt_footer" rows="3">{{ old('receipt_footer', $settings['receipt']['footer'] ?? 'Thank you for your visit!') }}</textarea>
                            <small class="form-text text-muted">Text to appear at the bottom of receipts</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="receipt_width" class="form-label">Receipt Width (mm)</label>
                            <select class="form-select" id="receipt_width" name="receipt_width">
                                <option value="58" {{ (old('receipt_width', $settings['receipt']['width'] ?? 58) == 58) ? 'selected' : '' }}>58mm</option>
                                <option value="80" {{ (old('receipt_width', $settings['receipt']['width'] ?? 80) == 80) ? 'selected' : '' }}>80mm</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="print_kitchen_orders" class="form-label">Print Kitchen Orders</label>
                            <select class="form-select" id="print_kitchen_orders" name="print_kitchen_orders">
                                <option value="auto" {{ (old('print_kitchen_orders', $settings['receipt']['print_kitchen_orders'] ?? 'auto') == 'auto') ? 'selected' : '' }}>Automatically</option>
                                <option value="manual" {{ (old('print_kitchen_orders', $settings['receipt']['print_kitchen_orders'] ?? 'manual') == 'manual') ? 'selected' : '' }}>Manually</option>
                                <option value="none" {{ (old('print_kitchen_orders', $settings['receipt']['print_kitchen_orders'] ?? 'none') == 'none') ? 'selected' : '' }}>Don't Print</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="print_customer_copy" class="form-label">Print Customer Copy</label>
                            <select class="form-select" id="print_customer_copy" name="print_customer_copy">
                                <option value="auto" {{ (old('print_customer_copy', $settings['receipt']['print_customer_copy'] ?? 'auto') == 'auto') ? 'selected' : '' }}>Automatically</option>
                                <option value="manual" {{ (old('print_customer_copy', $settings['receipt']['print_customer_copy'] ?? 'manual') == 'manual') ? 'selected' : '' }}>Manually</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_tax_details" name="show_tax_details" 
                                   value="1" {{ (old('show_tax_details', $settings['receipt']['show_tax_details'] ?? 1)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_tax_details">Show Tax Details on Receipt</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save Receipt Settings
                    </button>
                </form>
            </div>
        </div>

        {{-- Table Management --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-chair me-2"></i>Table Management</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.tables.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="default_hall" class="form-label">Default Hall</label>
                            <select class="form-select" id="default_hall" name="default_hall">
                                <option value="">-- Select Default Hall --</option>
                                @foreach($halls as $hall)
                                    <option value="{{ $hall->id }}" {{ (old('default_hall', $settings['tables']['default_hall'] ?? '') == $hall->id) ? 'selected' : '' }}>
                                        {{ $hall->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="auto_table_assignment" class="form-label">Auto Table Assignment</label>
                            <select class="form-select" id="auto_table_assignment" name="auto_table_assignment">
                                <option value="enabled" {{ (old('auto_table_assignment', $settings['tables']['auto_assignment'] ?? 'enabled') == 'enabled') ? 'selected' : '' }}>Enabled</option>
                                <option value="disabled" {{ (old('auto_table_assignment', $settings['tables']['auto_assignment'] ?? 'disabled') == 'disabled') ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_table_status" name="show_table_status" 
                                   value="1" {{ (old('show_table_status', $settings['tables']['show_status'] ?? 1)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_table_status">Show Table Status on POS</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Save Table Settings
                    </button>
                </form>
            </div>
        </div>

        {{-- Backup & Reset --}}
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i>Backup & Reset</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <form action="{{ route('settings.backup.create') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Create Backup</label>
                                <p class="text-muted">Create a backup of your current settings and data</p>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Create Backup
                            </button>
                        </form>
                    </div>

                    <div class="col-md-6 mb-3">
                        <form action="{{ route('settings.reset') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset all settings to default? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <div class="mb-3">
                                <label class="form-label">Reset to Default</label>
                                <p class="text-muted">Reset all settings to their default values</p>
                            </div>
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-undo me-1"></i> Reset Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-header {
            font-weight: 600;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
@endpush