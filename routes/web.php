<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductAttributeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Route Otentikasi/Auth ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route Registrasi
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


// --- Route Dashboard (SETELAH LOGIN) ---



Route::get('/dashboard', function () {
    // Redirect user to their role-specific dashboard
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'manager':
            return redirect()->route('manager.dashboard');
        case 'staff':
            return redirect()->route('staff.dashboard');
        default:
            return view('dashboard');
    }

})->middleware(['auth'])->name('dashboard');


// 2. Route Khusus ADMIN (Redirect di LoginController mengarah ke '/admin/dashboard')
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Product Attributes
    Route::get('/categories/{categoryId}/attributes/json', [ProductAttributeController::class, 'json'])->name('admin.product-attributes.json');
    Route::get('/categories/{categoryId}/attributes/{attributeId}/json', [ProductAttributeController::class, 'show'])->name('admin.product-attributes.show.json');
    Route::get('/categories/{categoryId}/attributes', [ProductAttributeController::class, 'index'])->name('admin.product-attributes.index');
    Route::post('/categories/{categoryId}/attributes', [ProductAttributeController::class, 'store'])->name('admin.product-attributes.store');
    Route::put('/categories/{categoryId}/attributes/{attributeId}', [ProductAttributeController::class, 'update'])->name('admin.product-attributes.update');
    Route::delete('/categories/{categoryId}/attributes/{attributeId}', [ProductAttributeController::class, 'destroy'])->name('admin.product-attributes.destroy');
    Route::post('/categories/{categoryId}/attributes/reorder', [ProductAttributeController::class, 'reorder'])->name('admin.product-attributes.reorder');

    // Products (routes tanpa parameter harus didahulukan)
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // Suppliers
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('admin.suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('admin.suppliers.store');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('admin.suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('admin.suppliers.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Reports
    Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('admin.reports.stock');
    Route::get('/reports/movement', [ReportController::class, 'movementReport'])->name('admin.reports.movement');

    // Global Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
});

// 3. Route Khusus MANAGER (Redirect di LoginController mengarah ke '/manager/dashboard')
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Manager\DashboardController@index')->name('manager.dashboard');

    // Stock Routes
    Route::get('/stock/monitoring', 'App\Http\Controllers\Manager\StockController@monitoring')->name('manager.stock.monitoring');
    Route::get('/stock/in', 'App\Http\Controllers\Manager\StockController@indexIn')->name('manager.stock.in');
    Route::post('/stock/in', 'App\Http\Controllers\Manager\StockController@storeIn')->name('manager.stock.in.store');
    Route::get('/stock/in/{id}/edit', 'App\Http\Controllers\Manager\StockController@editIn')->name('manager.stock.in.edit');
    Route::put('/stock/in/{id}', 'App\Http\Controllers\Manager\StockController@updateIn')->name('manager.stock.in.update');
    Route::delete('/stock/in/{id}', 'App\Http\Controllers\Manager\StockController@destroyIn')->name('manager.stock.in.destroy');

    Route::get('/stock/out', 'App\Http\Controllers\Manager\StockController@indexOut')->name('manager.stock.out');
    Route::post('/stock/out', 'App\Http\Controllers\Manager\StockController@storeOut')->name('manager.stock.out.store');
    Route::get('/stock/out/{id}/edit', 'App\Http\Controllers\Manager\StockController@editOut')->name('manager.stock.out.edit');
    Route::put('/stock/out/{id}', 'App\Http\Controllers\Manager\StockController@updateOut')->name('manager.stock.out.update');
    Route::delete('/stock/out/{id}', 'App\Http\Controllers\Manager\StockController@destroyOut')->name('manager.stock.out.destroy');

    // Product Routes
    Route::get('/products', 'App\Http\Controllers\Manager\ProductController@index')->name('manager.products');
    Route::get('/products/{id}', 'App\Http\Controllers\Manager\ProductController@show')->name('manager.products.show');
    Route::get('/products/{id}/edit', 'App\Http\Controllers\Manager\ProductController@edit')->name('manager.products.edit');
    Route::put('/products/{id}', 'App\Http\Controllers\Manager\ProductController@update')->name('manager.products.update');
    Route::get('/low-stock', 'App\Http\Controllers\Manager\ProductController@lowStock')->name('manager.low_stock');
    Route::post('/reorder', 'App\Http\Controllers\Manager\ProductController@storeReorder')->name('manager.reorder.store');

    // Supplier Routes
    Route::get('/suppliers', 'App\Http\Controllers\Manager\SupplierController@index')->name('manager.suppliers.index');

    // Manager Reports

    // Manager Reports
    Route::get('/reports/stock', [\App\Http\Controllers\Manager\ReportController::class, 'stock'])->name('manager.reports.stock');
    Route::get('/reports/movement', [\App\Http\Controllers\Manager\ReportController::class, 'movement'])->name('manager.reports.movement');
    // Manager stock opname
    Route::get('/stock/opname', 'App\Http\Controllers\Manager\StockController@opnameIndex')->name('stock.opname.index');
});

// 4. Route Khusus STAFF (Redirect di LoginController mengarah ke '/staff/dashboard')
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Staff\DashboardController@index')->name('staff.dashboard');
    // Staff tasks
    Route::get('/tasks', 'App\Http\Controllers\Staff\TaskController@index')->name('staff.tasks');
    // Staff history (confirmed items)
    Route::get('/history', 'App\Http\Controllers\Staff\HistoryController@index')->name('staff.history');
    // Staff stock routes (used by sidebar links)
    Route::get('/stock/in', 'App\Http\Controllers\Staff\StockController@indexIn')->name('staff.stock.in');
    Route::post('/stock/in', 'App\Http\Controllers\Staff\StockController@storeIn')->name('staff.stock.in.store');
    Route::get('/stock/in/{id}/confirm', 'App\Http\Controllers\Staff\StockController@confirmIn')->name('staff.stock.in.confirm');
    Route::post('/stock/in/{id}/confirm', 'App\Http\Controllers\Staff\StockController@doConfirmIn')->name('staff.stock.in.do_confirm');
    Route::post('/stock/in/{id}/reject', 'App\Http\Controllers\Staff\StockController@rejectIn')->name('staff.stock.in.reject');
    Route::get('/stock/out', 'App\Http\Controllers\Staff\StockController@indexOut')->name('staff.stock.out');
    Route::post('/stock/out', 'App\Http\Controllers\Staff\StockController@storeOut')->name('staff.stock.out.store');
    Route::get('/stock/out/{id}/confirm', 'App\Http\Controllers\Staff\StockController@confirmOut')->name('staff.stock.out.confirm');
    Route::post('/stock/out/{id}/confirm', 'App\Http\Controllers\Staff\StockController@doConfirmOut')->name('staff.stock.out.do_confirm');
    Route::post('/stock/out/{id}/reject', 'App\Http\Controllers\Staff\StockController@rejectOut')->name('staff.stock.out.reject');
});

// Route Home Page (Jika ada)
Route::get('/', function () {
    return redirect()->route('login');
});