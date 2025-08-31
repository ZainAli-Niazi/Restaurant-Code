<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <span class="sidebar-brand">KEY-POS</span>
        <button class="toggle-btn" id="toggleSidebar" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="right"
            title="Dashboard">
            <i class="bi bi-speedometer2"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- POS -->
        <a href="{{ route('pos.index') }}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="right"
            title="POS (F1)">
            <i class="bi bi-cash-coin"></i>
            <span class="sidebar-text">POS</span>
        </a>

        <!-- Products & Categories -->
        <div class="sidebar-item submenu-toggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Products">
            <div class="d-flex align-items-center w-100">
                <i class="bi bi-box-seam"></i>
                <span class="sidebar-text">Products</span>
                <i class="bi bi-chevron-right dropdown-icon"></i>
            </div>
        </div>
        <div class="submenu">
            <a href="{{ route('products.index') }}" class="submenu-item">All Products</a>
            <a href="{{ route('products.create') }}" class="submenu-item">Add Product</a>
            <a href="{{ route('categories.index') }}" class="submenu-item">Categories</a>
        </div>

        <!-- Orders -->
        <div class="sidebar-item submenu-toggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Orders">
            <div class="d-flex align-items-center w-100">
                <i class="bi bi-cart4"></i>
                <span class="sidebar-text">Orders</span>
                <i class="bi bi-chevron-right dropdown-icon"></i>
            </div>
        </div>
        <div class="submenu">
            <a href="{{ route('orders.index') }}" class="submenu-item">All Orders</a>

        </div>

        <!-- Expenses -->
        <a href="{{ route('expenses.index') }}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="right"
            title="Expenses">
            <i class="bi bi-currency-dollar"></i>
            <span class="sidebar-text">Expenses</span>
        </a>

        <!-- Halls & Tables -->
        {{-- <div class="sidebar-item submenu-toggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Halls & Tables">
            <div class="d-flex align-items-center w-100">
              <i class="bi bi-people"></i>
                <span class="sidebar-text">Halls & Tables</span>
                <i class="bi bi-chevron-right dropdown-icon"></i>
            </div>
        </div>
        <div class="submenu">
            <a href="{{ route('halls.index') }}" class="submenu-item">All Halls</a>
            <a href="{{ route('tables.index') }}" class="submenu-item">All Tables</a>
        </div> --}}

        <!-- Shifts -->
        <a href="{{ route('shifts.index') }}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="right"
            title="Shifts">
            <i class="bi bi-clock-history"></i>
            <span class="sidebar-text">Shifts</span>
        </a>

        <!-- Reports -->
        <div class="sidebar-item submenu-toggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Reports">
            <div class="d-flex align-items-center w-100">
                <i class="bi bi-bar-chart"></i>
                <span class="sidebar-text">Reports</span>
                <i class="bi bi-chevron-right dropdown-icon"></i>
            </div>
        </div>
        <div class="submenu">
            <a href="{{ route('reports.sales') }}" class="submenu-item">Sales Report</a>
            <a href="{{ route('reports.products') }}" class="submenu-item">Product Report</a>
            <a href="{{ route('reports.expenses') }}" class="submenu-item">Expense Report</a>
            <a href="{{ route('reports.profit-loss') }}" class="submenu-item">Profit & Loss</a>
        </div>


     
        <a href="{{route ('settings.index')}}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Setting">
            <i class="bi bi-gear"></i>

            <span class="sidebar-text">Settings</span>
        </a>


        <!-- Logout -->
        <a href="{{ route('admin.logout') }}" class="sidebar-item" data-bs-toggle="tooltip" data-bs-placement="right"
            title="Logout">
            <i class="bi bi-power"></i>

            <span class="sidebar-text">Logout</span>
        </a>
    </div>
</div>
