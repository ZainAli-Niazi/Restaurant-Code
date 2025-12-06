  <header class="main-header d-flex align-items-center justify-content-between">
      <!-- Left: Logo + Restaurant Name -->
      <div class="d-flex align-items-center">
          @if (!empty($restaurantSettings['restaurant_logo']))
              <img src="{{ asset('storage/' . $restaurantSettings['restaurant_logo']) }}" alt="Logo" class="me-2"
                  style="height:42px; width:auto; border-radius:8px;" />
          @endif

          <h3 class="fw-bold mb-0">{{ strtoupper($restaurantSettings['restaurant_name'] ?? '') }}</h3>
      </div>



      <!-- Right: User Dropdown -->
      <div class="user-dropdown dropdown">
         <div class="header-icons d-flex align-items-center gap-3">
    <!-- Notification Dropdown -->
    <div class="dropdown notification-dropdown">
        <button class="icon-btn" type="button" id="notificationDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="bi bi-bell-fill"></i>
            <!-- Optional: Badge for held orders count -->
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                  id="heldOrdersBadge" style="display: none;">
                0
            </span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationDropdown" style="min-width: 400px;">
            <li class="dropdown-header text-primary fw-bold px-3">Held Orders</li>
            <li>
                <div class="notification-table">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small">Order ID</th>
                                <th class="small">Time</th>
                                <th class="small">Total</th>
                                <th class="small text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- JS will populate this -->
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No held orders</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
        </ul>
    </div>
</div>


          <button class="btn btn-user dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              <strong>{{ ucfirst(Auth::user()->username) }}</strong>
          </button>



          <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
              <!-- Dashboard -->
              <li>
                  <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                      <i class="bi bi-speedometer2 me-2"></i> Dashboard
                  </a>
              </li>
              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- POS -->
              <li>
                  <a href="{{ route('pos.index') }}" class="dropdown-item">
                      <i class="bi bi-cash-coin me-2"></i> POS
                  </a>
              </li>
              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Orders -->
              <li>
                  <a href="{{ route('orders.index') }}" class="dropdown-item">
                      <i class="bi bi-cart4 me-2"></i> Orders
                  </a>
              </li>
              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Products -->
              <li>
                  <a href="{{ route('products.index') }}" class="dropdown-item">
                      <i class="bi bi-box-seam me-2"></i> Products
                  </a>
              </li>
              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Shifts -->
              <li>
                  <a href="{{ route('shifts.index') }}" class="dropdown-item">
                      <i class="bi bi-clock-history me-2"></i> Shifts
                  </a>
              </li>
              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Reports (Submenu) -->
              <li class="dropdown-submenu">
                  <a href="#" class="dropdown-item dropdown-toggle">
                      <i class="bi bi-bar-chart me-2"></i> Reports
                  </a>
                  <ul class="dropdown-menu">
                      <li><a href="{{ route('reports.sales') }}" class="dropdown-item">Sales Report</a></li>
                      <li><a href="{{ route('reports.expenses') }}" class="dropdown-item">Expense Report</a></li>
                      <li><a href="{{ route('reports.profit-loss') }}" class="dropdown-item">Profit & Loss</a></li>
                      <li><a href="{{ route('reports.products') }}" class="dropdown-item">Product Report</a></li>
                  </ul>
              </li>

              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Expenses -->
              <li>
                  <a href="{{ route('expenses.index') }}" class="dropdown-item">
                      <i class="bi bi-currency-dollar me-2"></i> Expenses
                  </a>
              </li>

              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Settings -->
              <li>
                  <a href="{{ route('settings.index') }}" class="dropdown-item">
                      <i class="fas fa-cog me-2"></i> Settings
                  </a>
              </li>

              <li>
                  <hr class="dropdown-divider">
              </li>

              <!-- Logout -->
              <li>
                  <a href="{{ route('admin.logout') }}" class="dropdown-item text-danger">
                      <i class="fas fa-sign-out-alt me-2"></i> Logout
                  </a>
              </li>
          </ul>
      </div>
  </header>





  

 
