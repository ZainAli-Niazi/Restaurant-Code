// Header scroll effect
window.addEventListener('scroll', function() {
  const header = document.querySelector('.main-header');
  if (window.scrollY > 50) header.classList.add('scrolled'); else header.classList.remove('scrolled');
});

// Enhanced dropdown animations
document.addEventListener('DOMContentLoaded', function() {
  const dropdowns = document.querySelectorAll('.dropdown');
  dropdowns.forEach(dropdown => {
    dropdown.addEventListener('show.bs.dropdown', function() { this.classList.add('show'); });
    dropdown.addEventListener('hide.bs.dropdown', function() { this.classList.remove('show'); });
  });

  const submenuItems = document.querySelectorAll('.dropdown-submenu');
  submenuItems.forEach(item => {
    item.addEventListener('mouseenter', function() { this.classList.add('show'); });
    item.addEventListener('mouseleave', function() { this.classList.remove('show'); });
  });
});


// -----------------------------------------------------POS Screen------------------------------------------------------------------- 

/* =========================
   POS Frontend — Optimized with Persistent Hold Orders
   ========================= */
// ===== Config =====
const CURRENCY_SYMBOL = '$';
const HOLD_ORDERS_STORAGE_KEY = 'pos_hold_orders_v1';

// ===== State =====
const order = new Map(); // key: productId, value: {id, name, price, qty, discPct}
let activeCategory = 'all'; // 'all' or category ID
const STORAGE_KEY = 'pos_order_state_v1';
let heldOrders = []; // Array to store held orders
let currentHeldOrderId = null; // track which held order is being viewed

// ===== Utilities =====
const currency = (n) => `${CURRENCY_SYMBOL} ${Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 2 })}`;
const safeNum = (v) => { const n = Number(String(v).toString().replace(/[^\d.-]/g, '')); return isNaN(n) ? 0 : n; };

function readMoneyFromEl($el) {
  const dataVal = $el.data('value');
  if (typeof dataVal !== 'undefined') return safeNum(dataVal);
  return safeNum($el.text());
}

function writeMoneyToEl($el, num) {
  $el.data('value', Number(num));
  $el.text(currency(num));
}

function showAlert(message, type = 'success', ttl = 1500) {
  // Single alert creation to avoid duplicates
  const alertId = 'alert-' + Date.now();
  const alertHtml = `
    <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert" style="z-index:1050;">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
  $('#alertContainer').append(alertHtml);
  setTimeout(() => { $('#' + alertId).alert('close'); }, ttl);
}

// ===== Hold Orders Management =====
function loadHeldOrders() {
  try {
    const stored = localStorage.getItem(HOLD_ORDERS_STORAGE_KEY);
    heldOrders = stored ? JSON.parse(stored) : [];
  } catch (e) {
    console.error('Error loading held orders:', e);
    heldOrders = [];
  }
  updateHeldOrdersTable();
}

function saveHeldOrders() {
  try {
    localStorage.setItem(HOLD_ORDERS_STORAGE_KEY, JSON.stringify(heldOrders));
  } catch (e) {
    console.error('Error saving held orders:', e);
  }
}

function getCurrentOrderData() {
  const items = Array.from(order.values()).map(row => ({
    product_id: row.id,
    name: row.name,
    price: row.price,
    quantity: row.qty,
    discount_percentage: row.discPct,
    total: row.price * row.qty * (1 - row.discPct / 100)
  }));

  return {
    id: currentHeldOrderId || 'H' + Date.now(), // Unique ID for the hold order
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    table_number: $('#tableSelect').val(),
    items: items,
    totals: {
      sub_total: safeNum($('#subTotal').data('value') || 0),
      service_charges: safeNum($('#serviceCharges').val()),
      discount_amount: safeNum($('#discountAmount').val()),
      total_amount: safeNum($('#totalAmount').data('value') || 0),
      paid_amount: safeNum($('#paidAmount').val())
    },
    timestamp: Date.now() // For sorting
  };
}

function updateHeldOrdersTable() {
  const $tbody = $('.notification-table table tbody');

  if (!Array.isArray(heldOrders) || heldOrders.length === 0) {
    $tbody.html('<tr><td colspan="4" class="text-center text-muted py-3">No held orders</td></tr>');
    $('#heldOrdersBadge').hide();
    return;
  }

  heldOrders.sort((a, b) => b.timestamp - a.timestamp); // newest first

  let html = '';
  heldOrders.forEach((o) => {
    html += `
      <tr data-order-id="${o.id}">
        <td class="small">${o.id}</td>
        <td class="small">${o.time}</td>
        <td class="small">${currency(o.totals.total_amount)}</td>
        <td class="text-center">
          <button class="btn btn-sm btn-primary view-held me-1" data-id="${o.id}" title="View Order"><i class="bi bi-eye"></i></button>
          <button class="btn btn-sm btn-danger delete-held" data-id="${o.id}" title="Delete Order"><i class="bi bi-trash"></i></button>
        </td>
      </tr>
    `;
  });

  $tbody.html(html);
  $('#heldOrdersBadge').show().text(heldOrders.length);
}

function loadHeldOrderIntoPOS(orderData) {
  if (!orderData) return;

  currentHeldOrderId = orderData.id; // mark as currently loaded held order

  // Clear current order
  order.clear();
  $('#orderTable tbody').empty();

  // Set table number
  if (orderData.table_number) {
    $('#tableSelect').val(orderData.table_number);
  }

  // Set financial values
  $('#serviceCharges').val(orderData.totals.service_charges || 0);
  $('#discountAmount').val(orderData.totals.discount_amount || 0);
  $('#paidAmount').val(orderData.totals.paid_amount || 0);

  // Load order items
  if (orderData.items && Array.isArray(orderData.items)) {
    orderData.items.forEach(item => {
      const row = {
        id: Number(item.product_id),
        name: item.name,
        price: safeNum(item.price),
        qty: Math.max(1, safeNum(item.quantity)),
        discPct: Math.max(0, Math.min(100, safeNum(item.discount_percentage)))
      };
      order.set(row.id, row);
      appendOrderRow(row);
    });
  }

  recalcTotals();
  showAlert('Held order loaded successfully!', 'success');
}

function deleteHeldOrder(orderId) {
  heldOrders = heldOrders.filter(h => h.id !== orderId);
  saveHeldOrders();
  updateHeldOrdersTable();
  showAlert('Held order deleted successfully!', 'warning');
}

// ===== Category + Filtering =====
function setActiveCategory(id) {
  activeCategory = id;
  $('#categoryButtons .category-btn').removeClass('active');
  $(`#categoryButtons .category-btn[data-id="${id}"]`).addClass('active');
  filterProducts();
}

function filterProducts() {
  const q = ($('#searchInput').val() || '').toLowerCase();
  $('.product-item').each(function () {
    const productName = String($(this).find('.product-card').data('name') || '').toLowerCase();
    const categoryId = $(this).data('category-id');
    const nameMatch = !q || productName.includes(q);
    const categoryMatch = activeCategory === 'all' || String(categoryId) === String(activeCategory);
    $(this).toggle(nameMatch && categoryMatch);
  });
}

// ===== Cart ops =====
function addOrIncProduct(pid) {
  const $product = $(`.product-card[data-id="${pid}"]`);
  if (!$product.length) return;

  const product = {
    id: Number(pid),
    name: $product.data('name'),
    price: safeNum($product.data('price')),
    qty: 1,
    discPct: 0
  };

  if (!order.has(pid)) {
    order.set(pid, product);
    appendOrderRow(order.get(pid));
  } else {
    const row = order.get(pid);
    row.qty += 1;
    order.set(pid, row);
    updateRowTotals(pid);
  }
  recalcTotals();
  persistOrder();
}

function appendOrderRow(row) {
  const rowTotal = row.price * row.qty * (1 - row.discPct / 100);
  const $tr = $(`
    <tr data-id="${row.id}">
      <td style="width: 24%;"><div class="fw-semibold text-truncate" style="font-size: 14px;">${row.name}</div></td>
      <td class="text-end price" style="width: 14%; font-size: 14px;">${currency(row.price)}</td>
      <td class="text-center" style="width: 8%;">
        <input type="number" class="form-control form-control-sm qty" value="${row.qty}" min="1" style="width:48px;display:inline-block;font-size:13px;padding:2px 6px;">
      </td>
      <td class="text-center" style="width: 8%;">
        <input type="number" class="form-control form-control-sm disc" value="${row.discPct}" min="0" max="100" style="width:48px;display:inline-block;font-size:13px;padding:2px 6px;">
      </td>
      <td class="text-center row-total" style="width: 14%; font-size: 14px;">${currency(rowTotal)}</td>
      <td class="text-center" style="width: 13%;">
        <button class="btn btn-sm btn-outline-danger remove btn-pill"><i class="bi bi-x-lg"></i></button>
      </td>
    </tr>`);
  $('#orderTable tbody').append($tr);
}

function updateRowTotals(pid) {
  const row = order.get(pid);
  if (!row) return;
  const rowTotal = row.price * row.qty * (1 - row.discPct / 100);
  const $tr = $(`#orderTable tbody tr[data-id="${pid}"]`);
  $tr.find('.row-total').text(currency(rowTotal));
  $tr.find('.qty').val(row.qty);
  $tr.find('.disc').val(row.discPct);
}

// ===== Load Existing Order =====
function loadExistingOrder(orderData) {
  if (!orderData) return;
  order.clear();
  $('#orderTable tbody').empty();

  if (orderData.table_number) $('#tableSelect').val(orderData.table_number);
  $('#serviceCharges').val(orderData.service_charges || 0);
  $('#discountAmount').val(orderData.discount_amount || 0);
  $('#paidAmount').val(orderData.paid_amount || 0);

  if (orderData.items && Array.isArray(orderData.items)) {
    orderData.items.forEach(item => {
      const row = {
        id: Number(item.product_id),
        name: item.name,
        price: safeNum(item.price),
        qty: Math.max(1, safeNum(item.quantity)),
        discPct: Math.max(0, Math.min(100, safeNum(item.discount_percentage)))
      };
      order.set(row.id, row);
      appendOrderRow(row);
    });
  }
  recalcTotals();
}

// ===== Totals =====
function recalcTotals() {
  let items = 0;
  let subTotal = 0;

  order.forEach(r => {
    items += r.qty;
    const gross = r.price * r.qty;
    const disc = gross * (r.discPct / 100);
    subTotal += (gross - disc);
  });

  const service = Math.max(0, safeNum($('#serviceCharges').val()));
  const extraDisc = Math.max(0, safeNum($('#discountAmount').val()));
  const paid = Math.max(0, safeNum($('#paidAmount').val()));

  const subBeforeExtra = subTotal;
  const total = Math.max(0, subTotal - extraDisc + service);
  const balance = Math.max(0, total - paid);
  const ret = Math.max(0, paid - total);

  $('#totalItems').text(items);
  writeMoneyToEl($('#subTotal'), subBeforeExtra);
  const $auto = $('#discountPercentAuto');
  if ($auto.length) {
    $auto.text(subBeforeExtra > 0 && extraDisc > 0 ? ((extraDisc / subBeforeExtra) * 100).toFixed(1) + '%' : '0%');
  }
  writeMoneyToEl($('#totalAmount'), total);
  writeMoneyToEl($('#balanceAmount'), balance);
  writeMoneyToEl($('#returnAmount'), ret);
}

// ===== Persistence =====
function persistOrder() {
  try {
    const serialized = JSON.stringify(Array.from(order.values()));
    localStorage.setItem(STORAGE_KEY, serialized);
  } catch (_) { }
}

function loadOrderFromStorage() {
  const data = localStorage.getItem(STORAGE_KEY);
  if (!data) return;
  const items = JSON.parse(data);
  items.forEach(item => {
    order.set(item.id, item);
    appendOrderRow(item);
  });
  recalcTotals();
}

// ===== Event Handlers =====
$(document).ready(function() {
  loadHeldOrders();
  loadOrderFromStorage();
  setActiveCategory('all');

  // NOTE: removed duplicate handlers for #btnHold and #btnPaySave here to avoid double actions/alerts.
  // The final single bindings are at the bottom (they call saveOrder).
  
  // View held order
  $(document).on('click', '.view-held', function() {
    const orderId = $(this).data('id');
    const heldOrder = heldOrders.find(o => o.id === orderId);
    if (heldOrder) {
      loadHeldOrderIntoPOS(heldOrder);
      $('.dropdown-menu').removeClass('show'); // close dropdown
    } else {
      showAlert('Held order not found!', 'danger');
    }
  });

  // Delete held order
  $(document).on('click', '.delete-held', function() {
    const orderId = $(this).data('id');
    deleteHeldOrder(orderId);
  });

  // Reset
  $('#btnReset').on('click', function() {
    order.clear();
    $('#orderTable tbody').empty();
    $('#serviceCharges').val(0);
    $('#discountAmount').val(0);
    $('#paidAmount').val(0);
    recalcTotals();
    persistOrder();
    currentHeldOrderId = null;
    showAlert('Order reset successfully!', 'info');
  });

  // Remove row / qty / disc handlers
  $(document).on('click', '.remove', function() {
    const pid = Number($(this).closest('tr').data('id'));
    order.delete(pid);
    $(this).closest('tr').remove();
    recalcTotals();
    persistOrder();
  });

  $(document).on('input', '.qty', function() {
    const pid = Number($(this).closest('tr').data('id'));
    const row = order.get(pid);
    if (row) {
      row.qty = Math.max(1, safeNum($(this).val()));
      order.set(pid, row);
      updateRowTotals(pid);
      recalcTotals();
      persistOrder();
    }
  });

  $(document).on('input', '.disc', function() {
    const pid = Number($(this).closest('tr').data('id'));
    const row = order.get(pid);
    if (row) {
      row.discPct = Math.max(0, Math.min(100, safeNum($(this).val())));
      order.set(pid, row);
      updateRowTotals(pid);
      recalcTotals();
      persistOrder();
    }
  });

  // Financial inputs
  $('#serviceCharges, #discountAmount, #paidAmount').on('input', function() {
    const v = Math.max(0, safeNum($(this).val()));
    $(this).val(v);
    recalcTotals();
    persistOrder();
  });

  // Category buttons
  $('#categoryButtons').on('click', '.category-btn', function() {
    setActiveCategory($(this).data('id'));
  });

  // Search
  $('#searchInput').on('input', filterProducts);
});

// ===== Product Click Handler (Single Handler) =====
$(document).on('click', '.product-card', function() {
  const pid = Number($(this).data('id'));
  addOrIncProduct(pid);
});

// ===== Print helpers (AJAX based) =====
function openPrintView(url) {
  $.ajax({
    url: url,
    method: 'GET',
    success: function (html) {
      $('#printFrame').remove();
      const $iframe = $('<iframe>', { id: 'printFrame', style: 'display:none;' }).appendTo('body');
      const iframeDoc = $iframe[0].contentWindow.document;
      iframeDoc.open();
      iframeDoc.write(html);
      iframeDoc.close();
      $iframe[0].contentWindow.focus();
      $iframe[0].contentWindow.print();
    },
    error: function () {
      showAlert('Failed to load print view', 'danger');
    }
  });
}

function printInvoice(orderId) { openPrintView(`/pos/invoice/${orderId}`); }
function printKOT(orderId) { openPrintView(`/pos/kot/${orderId}`); }

// ===== Save / Restore (localStorage) =====
function restoreOrder() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return;
    const rows = JSON.parse(raw);
    if (!Array.isArray(rows)) return;
    order.clear();
    $('#orderTable tbody').empty();
    rows.forEach(r => {
      const row = {
        id: Number(r.id),
        name: r.name,
        price: safeNum(r.price),
        qty: Math.max(1, safeNum(r.qty)),
        discPct: Math.max(0, Math.min(100, safeNum(r.discPct)))
      };
      order.set(row.id, row);
      appendOrderRow(row);
    });
    recalcTotals();
  } catch (_) { }
}

// ===== AJAX CSRF =====
(function setupAjaxCsrf() {
  const token = $('meta[name="csrf-token"]').attr('content');
  if (token) $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });
})();

// ===== Save/Update Order =====
function saveOrder(status) {
  if (order.size === 0) {
    showAlert('Please add items to the order', 'danger');
    return;
  }

  const isEditMode = $('#isEditMode').val() === 'true';
  const orderId = $('#currentOrderId').val();
  const orderStoreUrl = $('#orderStoreRoute').val();

  const items = Array.from(order.values()).map(item => ({
    product_id: item.id,
    quantity: item.qty,
    price: item.price,
    discount_percentage: item.discPct
  }));

  const payload = {
    table_number: $('#tableSelect').val(),
    items: items,
    sub_total: readMoneyFromEl($('#subTotal')),
    service_charges: Math.max(0, safeNum($('#serviceCharges').val())),
    discount_amount: Math.max(0, safeNum($('#discountAmount').val())),
    total_amount: readMoneyFromEl($('#totalAmount')),
    paid_amount: Math.max(0, safeNum($('#paidAmount').val())),
    status: status
  };

  const method = isEditMode ? 'PUT' : 'POST';

  $.ajax({
    url: orderStoreUrl,
    method: method,
    contentType: 'application/json',
    data: JSON.stringify(payload),
    success: function (response) {
      if (response && response.success) {
        window.lastSavedOrderId = response.order_id; // store order id globally

        // Handle hold vs completed locally to avoid double alerts
        if (status === 'hold') {
          // Save hold locally (persist on client only)
          const hold = getCurrentOrderData();
          // ensure newest-first and avoid exact duplicate id collisions
          heldOrders = heldOrders || [];
          heldOrders.unshift(hold);
          saveHeldOrders();
          updateHeldOrdersTable();

          // Clear current order UI (since it's held)
          order.clear();
          $('#orderTable tbody').empty();
          $('#serviceCharges').val(0);
          $('#discountAmount').val(0);
          $('#paidAmount').val(0);
          recalcTotals();
          currentHeldOrderId = null;

          showAlert('Order held successfully!', 'success');
        } else if (status === 'completed') {
          // If we loaded this order from holds, remove it
          if (currentHeldOrderId) {
            deleteHeldOrder(currentHeldOrderId);
            currentHeldOrderId = null;
          }
          // Clear current order UI after completion
          order.clear();
          $('#orderTable tbody').empty();
          $('#serviceCharges').val(0);
          $('#discountAmount').val(0);
          $('#paidAmount').val(0);
          recalcTotals();
          persistOrder();

          showAlert('Order completed successfully!', 'success');
        } else {
          // Generic save/update feedback
          showAlert('Order saved successfully!', 'success');
        }
      } else {
        showAlert('Unexpected response from server.', 'danger');
      }
    },
    error: function (xhr) {
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        let errorMessage = '<b>Please fix the following errors:</b><br>';
        $.each(xhr.responseJSON.errors, function (field, messages) {
          errorMessage += `- ${messages[0]}<br>`;
        });
        showAlert(errorMessage, 'danger', 3000);
      } else {
        const msg = xhr.responseJSON?.message || (xhr.status + ' ' + xhr.statusText) || 'Unknown error';
        showAlert('Error ' + (isEditMode ? 'updating' : 'saving') + ' order: ' + msg, 'danger', 3000);
      }
    }
  });
}

// ===== Reset UI =====
function resetOrderUI() {
  order.clear();
  $('#orderTable tbody').empty();
  $('#serviceCharges').val(0);
  $('#discountAmount').val(0);
  $('#paidAmount').val(0);
  $('#tableSelect').val('T1');
  $('#searchInput').val('');
  recalcTotals();
  localStorage.removeItem(STORAGE_KEY);
}

// ===== Consolidated Event Bindings =====
$(document)
  .on('click', '#categoryButtons .category-btn', function () {
    setActiveCategory($(this).data('id'));
  })
  .on('input', '#searchInput', function () { filterProducts(); });

$('#orderTable tbody')
  .on('input', 'tr .qty', function () {
    const $tr = $(this).closest('tr');
    const pid = Number($tr.data('id'));
    let v = Math.max(1, safeNum($(this).val()));
    $(this).val(v);
    const row = order.get(pid);
    if (!row) return;
    row.qty = v;
    order.set(pid, row);
    updateRowTotals(pid);
    recalcTotals();
    persistOrder();
  })
  .on('input', 'tr .disc', function () {
    const $tr = $(this).closest('tr');
    const pid = Number($tr.data('id'));
    let v = Math.max(0, Math.min(100, safeNum($(this).val())));
    $(this).val(v);
    const row = order.get(pid);
    if (!row) return;
    row.discPct = v;
    order.set(pid, row);
    updateRowTotals(pid);
    recalcTotals();
    persistOrder();
  })
  .on('click', 'tr .remove', function () {
    const $tr = $(this).closest('tr');
    const pid = Number($tr.data('id'));
    order.delete(pid);
    $tr.remove();
    recalcTotals();
    persistOrder();
  });

$('#serviceCharges, #discountAmount, #paidAmount').on('input', function () {
  const v = Math.max(0, safeNum($(this).val()));
  $(this).val(v);
  recalcTotals();
  persistOrder();
});

$('#btnReset').on('click', resetOrderUI);

$('#btnPrint').on('click', function () {
  if (!window.lastSavedOrderId) { showAlert('Please save the order before printing invoice', 'danger'); return; }
  printInvoice(window.lastSavedOrderId);
});

$('#btnKOT').on('click', function () {
  if (!window.lastSavedOrderId) { showAlert('Please save the order before printing KOT', 'danger'); return; }
  printKOT(window.lastSavedOrderId);
});

// Single bindings for save/hold to avoid duplicates
$('#btnPaySave').off('click').on('click', function() { saveOrder('completed'); });
$('#btnHold').off('click').on('click', function() { saveOrder('hold'); });

// ===== Init =====
$(document).ready(function() {
  setActiveCategory('all');

  // Load existing order if in edit mode
  const orderDataElement = document.getElementById('orderData');
  if (orderDataElement && orderDataElement.value) {
    try {
      const orderData = JSON.parse(orderDataElement.value);
      console.log('Parsed order data:', orderData); // Debug log
      loadExistingOrder(orderData);
    } catch (e) {
      console.error('Error parsing order data:', e);
      restoreOrder();
    }
  } else {
    restoreOrder();
  }

  // Ensure held orders UI is up-to-date
  loadHeldOrders();
  recalcTotals();
});

// ===== Category Scroll Arrows =====
$(function () {
  const $row = $('#categoryButtons');
  const $prevBtn = $('#catPrev');
  const $nextBtn = $('#catNext');
  const step = 200; // pixels to move per click

  function checkArrowVisibility() {
    const categoryCount = $row.children('.category-btn').length;
    if (categoryCount > 9) { $prevBtn.addClass('show'); $nextBtn.addClass('show'); } else { $prevBtn.removeClass('show'); $nextBtn.removeClass('show'); }
  }

  checkArrowVisibility();
  $prevBtn.on('click', function () { $row.animate({ scrollLeft: $row.scrollLeft() - step }, 300); });
  $nextBtn.on('click', function () { $row.animate({ scrollLeft: $row.scrollLeft() + step }, 300); });
});
