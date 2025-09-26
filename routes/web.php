<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Middleware\IsAdmin;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


Route::middleware(['auth', IsAdmin::class])->group(function () {

    // Users

    Route::get('users', [UsersController::class, 'index'])
        ->name('users');

    Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');

    Route::get('users/{user}/edit', [UsersController::class, 'edit'])
        ->name('users.edit');

    Route::put('users/{user}', [UsersController::class, 'update'])
        ->name('users.update');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy');

    Route::put('users/{user}/restore', [UsersController::class, 'restore'])
        ->name('users.restore');

});


Route::middleware(['auth', 'can:orders'])->group(function () {

    // Products
    Route::get('products', [ProductsController::class, 'index'])
        ->name('products');

    Route::get('products/create', [ProductsController::class, 'create'])
        ->name('products.create');

    Route::post('products', [ProductsController::class, 'store'])
        ->name('products.store');

    Route::get('products/{product}/edit', [ProductsController::class, 'edit'])
        ->name('products.edit');

    Route::put('products/{product}', [ProductsController::class, 'update'])
        ->name('products.update');

    Route::delete('products/{product}', [ProductsController::class, 'destroy'])
        ->name('products.destroy');

    Route::put('products/{product}/restore', [ProductsController::class, 'restore'])
        ->name('products.restore');

});





// orders


Route::get('orders', [OrdersController::class, 'index'])
->name('orders')
->middleware('auth');

Route::middleware(['auth', 'can:create_orders'])->group(function () {
    Route::get('orders/create', [OrdersController::class, 'create'])
        ->name('orders.create');

    Route::post('orders', [OrdersController::class, 'store'])
        ->name('orders.store');
});

Route::get('orders/{order}/edit', [OrdersController::class, 'edit'])
->name('orders.edit')
->middleware('auth');

Route::put('orders/{order}', [OrdersController::class, 'update'])
->name('orders.update')
->middleware('auth');

Route::delete('orders/{order}', [OrdersController::class, 'destroy'])
->name('orders.destroy')
->middleware('auth');

Route::put('orders/{order}/restore', [OrdersController::class, 'restore'])
->name('orders.restore')
->middleware('auth');



Route::post('/orders/{order}/approve', [OrdersController::class, 'approve'])
        ->name('orders.approve')
        ->middleware('auth');

Route::post('/orders/{order}/ship', [OrdersController::class, 'ship'])
        ->name('orders.ship')
        ->middleware('auth');


// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
