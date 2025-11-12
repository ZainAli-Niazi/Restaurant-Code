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

                  </button>

                  <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notificationDropdown">
                      <li class="dropdown-header text-primary fw-bold px-3">Recent Orders</li>
                      <li>
                          <div class="notification-table">
                              <table class="table table-sm mb-0">
                                  <thead class="table-light">
                                      <tr>
                                          <th>Order ID</th>
                                          <th>Time</th>
                                          <th>Total</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                
                                    
                                    
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






  <script>
$(document).ready(function() {

    // Store held orders temporarily
    let heldOrders = [];
    let orderCounter = 1;

    // Function to get current time
    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Get all current order data from POS form
    function getCurrentOrderData() {
        let items = [];
        $("#orderTable tbody tr").each(function() {
            const item = {
                name: $(this).find("td:eq(0)").text().trim(),
                price: parseFloat($(this).find("td:eq(1)").text()) || 0,
                qty: parseInt($(this).find("td:eq(2) input").val()) || 1,
                discount: $(this).find("td:eq(3)").text().trim(),
                total: parseFloat($(this).find("td:eq(4)").text()) || 0
            };
            items.push(item);
        });

        return {
            id: "H" + orderCounter++,
            time: getCurrentTime(),
            items: items,
            totals: {
                subTotal: $("#subTotal").text().replace("₨", "").trim(),
                serviceCharges: $("#serviceCharges").val(),
                discountAmount: $("#discountAmount").val(),
                totalAmount: $("#totalAmount").text().replace("₨", "").trim(),
                paidAmount: $("#paidAmount").val()
            }
        };
    }

    // Update the header notification table dynamically
    function updateHeldOrdersTable() {
        let html = "";
        if (heldOrders.length === 0) {
            html = `<tr><td colspan="4" class="text-center text-muted">No held orders</td></tr>`;
        } else {
            heldOrders.forEach((order) => {
                html += `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.time}</td>
                        <td>₨ ${order.totals.totalAmount}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary view-held" data-id="${order.id}">View</button>
                            <button class="btn btn-sm btn-danger delete-held" data-id="${order.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }

        $(".notification-table table tbody").remove();
        $(".notification-table table").append("<tbody>" + html + "</tbody>");
    }

    // 🟢 Hold button click
    $("#btnHold").on("click", function() {
        const orderData = getCurrentOrderData();

        if (orderData.items.length === 0) {
            alert("No items in the order!");
            return;
        }

        // Add new hold order on top
        heldOrders.unshift(orderData);

        // Update the dropdown display
        updateHeldOrdersTable();

      
    });

    // 🟡 View button click
    $(document).on("click", ".view-held", function() {
        const id = $(this).data("id");
        const order = heldOrders.find(o => o.id === id);

        if (!order) {
            alert("Order not found!");
            return;
        }

        // Restore items in POS table
        const tbody = $("#orderTable tbody");
        tbody.empty();

        order.items.forEach((item) => {
            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-end">${item.price.toFixed(2)}</td>
                    <td class="text-center"><input type="number" class="form-control form-control-sm" value="${item.qty}" min="1" style="width:60px;"></td>
                    <td class="text-center">${item.discount}</td>
                    <td class="text-end">${item.total.toFixed(2)}</td>
                    <td class="text-center"><button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></td>
                </tr>
            `;
            tbody.append(row);
        });

        // Restore totals
        $("#subTotal").text("₨ " + order.totals.subTotal);
        $("#serviceCharges").val(order.totals.serviceCharges);
        $("#discountAmount").val(order.totals.discountAmount);
        $("#totalAmount").text("₨ " + order.totals.totalAmount);
        $("#paidAmount").val(order.totals.paidAmount);
        $("#totalItems").text(order.items.length);

        $(".dropdown-menu").removeClass("show");
        
    });

    // 🔴 Delete button click
    $(document).on("click", ".delete-held", function() {
        const id = $(this).data("id");
        heldOrders = heldOrders.filter(o => o.id !== id);
        updateHeldOrdersTable();
        alert("Held order deleted successfully!");
    });

});
</script>

