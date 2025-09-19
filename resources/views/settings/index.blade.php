@extends('layouts.app')
@section('title', 'POS Settings')
@section('header', $restaurantSettings['restaurant_name'] ?? 'Restaurant')

@section('content')
    <style>
        /* Page Styling */
        body {
            background-color: #f8f9fa;
        }

        .settings-container {
            padding: 25px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Heading */
        .settings-heading {
            font-size: 26px;
            font-weight: 700;
            color: #34495e;
            margin-bottom: 20px;
        }

        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s;
            padding: 10px 18px;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        /* Card Styling */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #fff !important;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        /* Form Fields */
        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-control, .form-select {
            border-radius: 8px;
            box-shadow: none;
            border: 1px solid #ced4da;
            padding: 10px 12px;
            transition: 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.25);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-darker));
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 123, 255, 0.4);
        }

        /* Image Preview */
        .logo-preview img {
            max-height: 80px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        }
        
        /* Color input styling */
        .color-input-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: inline-block;
        }
    </style>

    <div class="settings-container">
        <h2 class="settings-heading">Restaurant POS Settings</h2>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="restaurant-tab" data-bs-toggle="tab" data-bs-target="#restaurant" type="button" role="tab">Restaurant Information</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax" type="button" role="tab">Tax & Charges</button>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabsContent">
            {{-- Restaurant Info Tab --}}
            <div class="tab-pane fade show active" id="restaurant" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Restaurant Details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.restaurant.update') }}" method="POST" enctype="multipart/form-data" id="restaurantForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Restaurant Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $restaurantSettings['restaurant_name'] ?? '') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone *</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $restaurantSettings['restaurant_phone'] ?? '') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $restaurantSettings['restaurant_address'] ?? '') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $restaurantSettings['restaurant_email'] ?? '') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $restaurantSettings['restaurant_website'] ?? '') }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="tax_id" class="form-label">Tax ID</label>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id', $restaurantSettings['restaurant_tax_id'] ?? '') }}">
                                </div>
                                
                                
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Restaurant Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                @if(isset($restaurantSettings['restaurant_logo']))
                                    <div class="logo-preview">
                                        <img src="{{ asset('storage/' . $restaurantSettings['restaurant_logo']) }}" alt="Restaurant Logo">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Restaurant Information
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tax & Charges Tab --}}
            <div class="tab-pane fade" id="tax" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Tax & Service Charges</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.tax.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tax_rate" class="form-label">Tax Rate (%) *</label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $taxSettings['tax_rate'] ?? 0) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="service_charge" class="form-label">Service Charge (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="service_charge" name="service_charge" value="{{ old('service_charge', $taxSettings['service_charge'] ?? 0) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tax_type" class="form-label">Tax Type *</label>
                                <select class="form-select" id="tax_type" name="tax_type" required>
                                    <option value="inclusive" {{ (old('tax_type', $taxSettings['tax_type'] ?? '') == 'inclusive') ? 'selected' : '' }}>Inclusive (Tax included in price)</option>
                                    <option value="exclusive" {{ (old('tax_type', $taxSettings['tax_type'] ?? '') == 'exclusive') ? 'selected' : '' }}>Exclusive (Tax added at checkout)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Tax Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Bootstrap Tabs Initialization
    const triggerTabList = document.querySelectorAll('#settingsTabs button');
    triggerTabList.forEach(triggerEl => {
        new bootstrap.Tab(triggerEl);
    });

    // Color picker functionality
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color_id');
        const colorPreview = document.getElementById('colorPreview');
        const primaryColor = '{{ $restaurantSettings["restaurant_color_id"] ?? "#007bff" }}';
        
        // Apply the saved color to the UI
        applyThemeColor(primaryColor);
        
        // Update preview when color changes
        colorInput.addEventListener('input', function() {
            colorPreview.style.backgroundColor = this.value;
            applyThemeColor(this.value);
        });
        
        // Reset to saved color when form is reset
        document.getElementById('restaurantForm').addEventListener('reset', function() {
            setTimeout(function() {
                applyThemeColor(primaryColor);
            }, 0);
        });
    });
    
    // Function to apply theme color to UI elements
    function applyThemeColor(color) {
        document.documentElement.style.setProperty('--primary-color', color);
        
        // Calculate darker shades for gradients
        const darkerColor = shadeColor(color, -20);
        const darkestColor = shadeColor(color, -40);
        
        document.documentElement.style.setProperty('--primary-dark', darkerColor);
        document.documentElement.style.setProperty('--primary-darker', darkestColor);
    }
    
    // Helper function to lighten or darken a color
    function shadeColor(color, percent) {
        let R = parseInt(color.substring(1, 3), 16);
        let G = parseInt(color.substring(3, 5), 16);
        let B = parseInt(color.substring(5, 7), 16);

        R = parseInt(R * (100 + percent) / 100);
        G = parseInt(G * (100 + percent) / 100);
        B = parseInt(B * (100 + percent) / 100);

        R = (R < 255) ? R : 255;
        G = (G < 255) ? G : 255;
        B = (B < 255) ? B : 255;

        R = (R < 0) ? 0 : R;
        G = (G < 0) ? 0 : G;
        B = (B < 0) ? 0 : B;

        const RR = ((R.toString(16).length === 1) ? "0" + R.toString(16) : R.toString(16));
        const GG = ((G.toString(16).length === 1) ? "0" + G.toString(16) : G.toString(16));
        const BB = ((B.toString(16).length === 1) ? "0" + B.toString(16) : B.toString(16));

        return "#" + RR + GG + BB;
    }
</script>
@endsection