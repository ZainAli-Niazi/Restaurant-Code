<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\{
    CategoryController,
    ExpenseController,
    HallController,
    OrderController,
    POSController,
    ProductController,
    ReportController,
    SettingController,
    SettingsController,
    ShiftController,
    TableController
};

// 🔐 Guest Routes - Login & Register (with /admin prefix)
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('admin.login');
    Route::get('/register', [RegisterController::class, 'register'])->name('admin.register');
    Route::post('/process-register', [RegisterController::class, 'store'])->name('admin.store');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('admin.authenticate');
});

// 🧑 Admin Authenticated Routes (with /admin prefix)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/live-data', [DashboardController::class, 'getLiveData'])->name('admin.dashboard.live');
});


// 🛠 Admin CRUD Routes (no /admin in URL, but still admin protected)
Route::middleware(['auth', 'admin'])->group(function () {

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::match(['put', 'patch'], '/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/{category}/products', [CategoryController::class, 'products'])->name('categories.products');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::match(['put', 'patch'], '/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');


    // Halls
    Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
    Route::get('/halls/create', [HallController::class, 'create'])->name('halls.create');
    Route::post('/halls', [HallController::class, 'store'])->name('halls.store');
    Route::get('/halls/{hall}', [HallController::class, 'show'])->name('halls.show');
    Route::get('/halls/{hall}/edit', [HallController::class, 'edit'])->name('halls.edit');
    Route::match(['put', 'patch'], '/halls/{hall}', [HallController::class, 'update'])->name('halls.update');
    Route::delete('/halls/{hall}', [HallController::class, 'destroy'])->name('halls.destroy');
    Route::get('/halls/{hall}/tables', [HallController::class, 'tables'])->name('halls.tables');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::match(['put', 'patch'], '/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    /*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
*/
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/{order}/edit', [POSController::class, 'edit'])->name('pos.edit');
    Route::post('/pos/order/store', [POSController::class, 'storeOrder'])->name('pos.order.store');
    Route::put('/pos/order/{order}/update', [POSController::class, 'updateOrder'])->name('pos.order.update');
    Route::get('/pos/held-orders', [POSController::class, 'getHeldOrders'])->name('pos.held-orders');

    // Fixed: use {id} not {$id}
    Route::get('/pos/invoice/{id}', [POSController::class, 'invoice'])->name('pos.invoice');
    Route::get('/pos/kot/{id}', [POSController::class, 'kot'])->name('pos.kot');



    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::match(['put', 'patch'], '/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/products', [ReportController::class, 'productReport'])->name('reports.products');
        Route::get('/expenses', [ReportController::class, 'expenseReport'])->name('reports.expenses');
        Route::get('/profit-loss', [ReportController::class, 'profitLossReport'])->name('reports.profit-loss');
    });

    // Shifts
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('/shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('/shifts/{shift}', [ShiftController::class, 'show'])->name('shifts.show');
    Route::post('/shifts/{shift}/end', [ShiftController::class, 'endShift'])->name('shifts.end');

    // Tables
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::get('/tables/{table}', [TableController::class, 'show'])->name('tables.show');
    Route::get('/tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
    Route::match(['put', 'patch'], '/tables/{table}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');



  // Settings Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/restaurant', [App\Http\Controllers\SettingController::class, 'updateRestaurant'])->name('settings.restaurant.update');
        Route::post('/tax', [App\Http\Controllers\SettingController::class, 'updateTax'])->name('settings.tax.update');
        Route::get('/{key}', [App\Http\Controllers\SettingController::class, 'getSetting'])->name('settings.get');
    });
});

// 🔄 Redirect root to admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});