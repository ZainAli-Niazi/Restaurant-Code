
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

function readMoneyFromEl($el) {
  const dataVal = $el.data('value');
  if (typeof dataVal !== 'undefined') return safeNum(dataVal);
  return safeNum($el.text());
}

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
        <input type="number" class="form-control form-control-sm qty" value="${row.qty}" min="1" style="width:40px;display:inline-block;">
      </td>
      <td class="text-center">
        <input type="number" class="form-control form-control-sm disc" value="${row.discPct}" min="0" max="100" style="width:40px;display:inline-block;">
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

// ===== Print helpers (AJAX based) =====
function openPrintView(url) {
  $.ajax({
    url: url,
    method: 'GET',
    success: function (html) {
      // Remove old iframe if exists
      $('#printFrame').remove();

      // Create hidden iframe
      const $iframe = $('<iframe>', {
        id: 'printFrame',
        style: 'display:none;'
      }).appendTo('body');

      const iframeDoc = $iframe[0].contentWindow.document;
      iframeDoc.open();
      iframeDoc.write(html);
      iframeDoc.close();

      // Wait for content to load, then print
      $iframe[0].contentWindow.focus();
      $iframe[0].contentWindow.print();
    },
    error: function () {
      showAlert('Failed to load print view', 'danger');
    }
  });
}


// Print Invoice (requires orderId)
function printInvoice(orderId) {
  const url = `/pos/invoice/${orderId}`;
  openPrintView(url);
}

// 

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

// ===== AJAX CSRF =====
(function setupAjaxCsrf() {
  const token = $('meta[name="csrf-token"]').attr('content');
  if (token) {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });
  }
})();

// ===== Save Order (no reset on save) =====
function saveOrder(status) {
  if (order.size === 0) {
    showAlert('Please add items to the order', 'danger');
    return;
  }

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

  $.ajax({
    url: orderStoreUrl,
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(payload),
    success: function (response) {
      if (response && response.success) {
        window.lastSavedOrderId = response.order_id; // store order id globally
        showAlert('Order ' + (status === 'hold' ? 'held' : 'saved') + ' successfully!');
        // ✅ No reset here, only persist
        persistOrder();
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

// Now these buttons use new print functions (require saved orderId)
$('#btnPrint').on('click', function () {
  if (!window.lastSavedOrderId) {
    showAlert('Please save the order before printing invoice', 'danger');
    return;
  }
  printInvoice(window.lastSavedOrderId);
});

$('#btnKOT').on('click', function () {
  if (!window.lastSavedOrderId) {
    showAlert('Please save the order before printing KOT', 'danger');
    return;
  }
  printKOT(window.lastSavedOrderId);
});

$('#btnPaySave').on('click', function () { saveOrder('completed'); });
$('#btnHold').on('click', function () { saveOrder('hold'); });

// ===== Init =====
(function init() {
  setActiveCategory('all');
  restoreOrder();
  recalcTotals();
})();





// ===== Category Scroll Arrows =====

 $(function () {
  const $row = $('#categoryButtons');
  const $prevBtn = $('#catPrev');
  const $nextBtn = $('#catNext');
  const step = 200; // pixels to move per click
  
  // Function to check if arrows should be visible
  function checkArrowVisibility() {
    const categoryCount = $row.children('.category-btn').length;
    
    if (categoryCount > 5) {
      $prevBtn.addClass('show');
      $nextBtn.addClass('show');
    } else {
      $prevBtn.removeClass('show');
      $nextBtn.removeClass('show');
    }
  }
  
  // Check visibility on page load
  checkArrowVisibility();
  
  $prevBtn.on('click', function () {
    $row.animate({ scrollLeft: $row.scrollLeft() - step }, 300);
  });

  $nextBtn.on('click', function () {
    $row.animate({ scrollLeft: $row.scrollLeft() + step }, 300);
  });
});