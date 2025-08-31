
//----------------------------------------------------------- Sidebar  --------------------------------------------------------------

$(document).ready(function () {
  const $sidebar = $('#sidebar');
  const $toggleBtn = $('#toggleSidebar');
  const $toggleIcon = $toggleBtn.find('i');

  // Initialize Bootstrap tooltips on hover
  $('[data-bs-toggle="tooltip"]').tooltip({
    trigger: 'hover'
  });

  // Sidebar toggle button click
  $toggleBtn.on('click', function () {
    $sidebar.toggleClass('expanded');

    if ($sidebar.hasClass('expanded')) {
      $toggleIcon.removeClass('bi-layout-sidebar-inset').addClass('bi-x-lg');
      $('[data-bs-toggle="tooltip"]').tooltip('disable');
    } else {
      $toggleIcon.removeClass('bi-x-lg').addClass('bi-layout-sidebar-inset');
      $('[data-bs-toggle="tooltip"]').tooltip('enable');

      // Close all submenus when collapsed
      $('.submenu').removeClass('show');
      $('.dropdown-icon').removeClass('rotate');
    }
  });

  // Submenu toggle click
  $('.submenu-toggle').on('click', function (e) {
    if (!$sidebar.hasClass('expanded')) {
      // Show tooltip if sidebar is collapsed
      $(this).tooltip('show');
      setTimeout(() => {
        $(this).tooltip('hide');
      }, 1000);
      return;
    }

    e.preventDefault();
    const $submenu = $(this).next('.submenu');
    const $icon = $(this).find('.dropdown-icon');

    // Close all other submenus
    $('.submenu').not($submenu).removeClass('show');
    $('.dropdown-icon').not($icon).removeClass('rotate');

    // Toggle selected submenu
    $submenu.toggleClass('show');
    $icon.toggleClass('rotate');
  });

  // Close submenu when clicking outside
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.submenu-toggle').length && !$(e.target).closest('.submenu').length) {
      $('.submenu').removeClass('show');
      $('.dropdown-icon').removeClass('rotate');
    }
  });

  // Highlight active sidebar item based on current URL
  const currentUrl = window.location.href;
  $('.sidebar-item').each(function () {
    const href = $(this).attr('href');
    if (href && currentUrl.includes(href)) {
      $(this).addClass('active');
    }
  });
});


//   -----------------------------------------------------POS Screen------------------------------------------------------------------- 





/* =========================
   POS Frontend — Optimized
   ========================= */

// ===== Config =====
const CURRENCY_SYMBOL = '₨';

// ===== State =====
const order = new Map(); // key: productId, value: {id, name, price, qty, discPct}
let activeCategory = 'all'; // 'all' or category ID
const STORAGE_KEY = 'pos_order_state_v1';

// ===== Utilities =====
const currency = (n) => `${CURRENCY_SYMBOL} ${Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 2 })}`;
const safeNum = (v) => { const n = Number(String(v).toString().replace(/[^\d.-]/g, '')); return isNaN(n) ? 0 : n; };

// Read number from an element that displays money (supports data-value if present)
function readMoneyFromEl($el) {
  const dataVal = $el.data('value');
  if (typeof dataVal !== 'undefined') return safeNum(dataVal);
  return safeNum($el.text());
}

// Write number to an element and keep raw in data-value for safety
function writeMoneyToEl($el, num) {
  $el.data('value', Number(num));
  $el.text(currency(num));
}

function showAlert(message, type = 'success') {
  const alertId = 'alert-' + Date.now();
  const alertHtml = `
    <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
  $('#alertContainer').append(alertHtml);
  setTimeout(() => { $('#' + alertId).alert('close'); }, 3000);
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
  const $tr = $(`
    <tr data-id="${row.id}">
      <td><div class="fw-semibold text-truncate">${row.name}</div></td>
      <td class="text-end price">${row.price}</td>
      <td class="text-center">
        <input type="number" class="form-control form-control-sm qty" value="${row.qty}" min="1" style="width:80px;display:inline-block;">
      </td>
      <td class="text-center">
        <input type="number" class="form-control form-control-sm disc" value="${row.discPct}" min="0" max="100" style="width:90px;display:inline-block;">
      </td>
      <td class="text-end row-total">${row.price * row.qty * (1 - row.discPct / 100)}</td>
      <td class="text-center">
        <button class="btn btn-sm btn-outline-danger remove btn-pill"><i class="bi bi-x-lg"></i></button>
      </td>
    </tr>`);
  $('#orderTable tbody').append($tr);
}

function updateRowTotals(pid) {
  const row = order.get(pid);
  if (!row) return;
  const rowTotal = Math.max(0, row.price * row.qty * (1 - (row.discPct / 100)));
  const $tr = $(`#orderTable tbody tr[data-id="${pid}"]`);
  $tr.find('.row-total').text(rowTotal);
  $tr.find('.qty').val(row.qty);
  $tr.find('.disc').val(row.discPct);
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

  // Inputs
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

// ===== Print helpers (fix about:blank) =====
function openAndPrint(html) {
  const w = window.open('', '_blank');
  if (!w) { showAlert('Pop-up blocked. Please allow pop-ups to print.', 'danger'); return; }
  w.document.write(html);
  w.document.close();
  w.onload = function () {
    try { w.focus(); w.print(); } finally { /* optional: keep window open for reprint */ }
  };
}

// ===== Invoice & KOT =====
function generateInvoice() {
  const tableNumber = $('#tableSelect').val();
  const orderItems = Array.from(order.values());

  const subTotal = readMoneyFromEl($('#subTotal'));
  const serviceCharges = Math.max(0, safeNum($('#serviceCharges').val()));
  const discountAmount = Math.max(0, safeNum($('#discountAmount').val()));
  const totalAmount = readMoneyFromEl($('#totalAmount'));
  const paidAmount = Math.max(0, safeNum($('#paidAmount').val()));

  const invoiceContent = `
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
      * { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 0; padding: 0; }
      body { width: 58mm; margin: 0 auto; background: #fff; color: #000; }
      .text-center { text-align: center; }
      .text-right { text-align: right; }
      .text-left { text-align: left; }
      .bold { font-weight: bold; }
      .line { border-top: 1px dashed #000; margin: 5px 0; }
      .table { width: 100%; border-collapse: collapse; }
      .table th, .table td { padding: 2px 0; word-break: break-word; }
      .table td.qty, .table td.price, .table td.total { text-align: right; white-space: nowrap; }
      .summary td { padding: 2px 0; }
      .summary td:first-child { text-align: left; }
      .summary td:last-child { text-align: right; }
      .grand-total { font-size: 14px; font-weight: bold; }
      .footer { margin-top: 10px; text-align: center; }
      @media print { body { width: auto; margin: 0; } }
    </style>
  </head>
  <body onload="window.print()">

    <!-- HEADER -->
    <div class="text-center">
      <div class="bold" style="font-size:16px;">Khaana Restaurant</div>
      <div>123 Food Street, City</div>
      <div>Phone: (123) 456-7890</div>
    </div>
    <div class="line"></div>

    <!-- INVOICE INFO -->
    <div>
      <div>Table: ${tableNumber || '-'}</div>
      <div>Date: ${new Date().toLocaleString()}</div>
    </div>
    <div class="line"></div>

    <!-- ITEMS -->
    <table class="table">
      <thead>
        <tr>
          <th class="text-left">Item</th>
          <th class="qty">Qty</th>
          <th class="price">Price</th>
          
          <th class="total">Total</th>
        </tr>
      </thead>
      <tbody>
        ${orderItems.map(item => `
          <tr>
            <td>${item.name}</td>
            <td class="qty">${item.qty}</td>
            <td class="price">  ${Number(item.price).toFixed(2)}</td>
            
            <td class="total">  ${(item.price * item.qty * (1 - item.discPct / 100)).toFixed(2)}</td>
          </tr>`).join('')}
      </tbody>
    </table>
    <div class="line"></div>

    <!-- SUMMARY -->
    <table class="table summary">
      <tr><td>Sub Total</td><td>${currency(subTotal)}</td></tr>
      <tr><td>Service Charges</td><td>${currency(serviceCharges)}</td></tr>
      <tr><td>Discount</td><td>- ${currency(discountAmount)}</td></tr>
      <tr class="grand-total"><td>Grand Total</td><td>${currency(totalAmount)}</td></tr>
    </table>
    <div class="line"></div>

    <!-- FOOTER -->
    <div class="footer">
      <div>Thank you for dining with us!</div>
    </div>

  </body>
  </html>`;
  openAndPrint(invoiceContent);
}
function generateKOT() {
  const tableNumber = $('#tableSelect').val();
  const orderItems = Array.from(order.values());

  const kotContent = `
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Kitchen Order Ticket</title>
    <style>
      * { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 0; padding: 0; }
      body { width: 58mm; margin: 0 auto; background: #fff; color: #000; }
      .text-center { text-align: center; }
      .line { border-top: 1px dashed #000; margin: 5px 0; }
      .table { width: 100%; border-collapse: collapse; }
      .table th, .table td { padding: 2px 0; word-break: break-word; }
      .table th.qty, .table td.qty { text-align: right; white-space: nowrap; }
      .table th.item, .table td.item { text-align: left; }
      .footer { margin-top: 10px; text-align: center; }
      @media print { body { width: auto; margin: 0; } }
    </style>
  </head>
  <body onload="window.print()">

    <!-- HEADER -->
    <div class="text-center">
      <div class="bold" style="font-size:14px;">Kitchen Order Ticket</div>
      <div>Table: ${tableNumber || '-'}</div>
      <div>Time: ${new Date().toLocaleTimeString()}</div>
    </div>
    <div class="line"></div>

    <!-- ITEMS -->
    <table class="table">
      <thead>
        <tr>
          <th class="item">Item</th>
          <th class="qty">Qty</th>
        </tr>
      </thead>
      <tbody>
        ${orderItems.map(item => `
          <tr>
            <td class="item">${item.name}</td>
            <td class="qty">${item.qty}</td>
          </tr>`).join('')}
      </tbody>
    </table>

  </body>
  </html>`;
  openAndPrint(kotContent);
}


// ===== Save / Restore (localStorage) =====
function persistOrder() {
  try {
    const serialized = JSON.stringify(Array.from(order.values()));
    localStorage.setItem(STORAGE_KEY, serialized);
  } catch (_) { }
}

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

// ===== AJAX (Laravel-friendly) =====
(function setupAjaxCsrf() {
  const token = $('meta[name="csrf-token"]').attr('content');
  if (token) {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });
  }
})();

function saveOrder(status) {
  if (order.size === 0) {
    showAlert('Please add items to the order', 'danger');
    return;
  }

  // Get the route URL from the hidden input
  const orderStoreUrl = $('#orderStoreRoute').val();

  // Prepare payload
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

  $.ajax({
    url: orderStoreUrl,
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(payload),
    success: function (response) {
      if (response && response.success) {
        showAlert('Order ' + (status === 'hold' ? 'held' : 'saved') + ' successfully!');
        if (status === 'completed') {
          resetOrderUI();
        } else {
          persistOrder(); // keep for held orders
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
        showAlert(errorMessage, 'danger');
      } else {
        const msg = xhr.responseJSON?.message || (xhr.status + ' ' + xhr.statusText) || 'Unknown error';
        showAlert('Error saving order: ' + msg, 'danger');
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
  $('#tableSelect').val('');
  $('#searchInput').val('');
  recalcTotals();
  localStorage.removeItem(STORAGE_KEY);
}

// ===== Event bindings =====
$(document)
  .on('click', '#categoryButtons .category-btn', function () {
    const id = $(this).data('id');
    setActiveCategory(id);
  })
  .on('click', '.product-card', function () {
    const pid = Number($(this).data('id'));
    addOrIncProduct(pid);
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
$('#btnPrint').on('click', generateInvoice);
$('#btnKOT').on('click', generateKOT);
$('#btnPaySave').on('click', function () { saveOrder('completed'); });
$('#btnHold').on('click', function () { saveOrder('hold'); });

// ===== Init =====
(function init() {
  setActiveCategory('all');
  restoreOrder(); // load any persisted cart
  recalcTotals();
})();