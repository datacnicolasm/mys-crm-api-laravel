<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Ticket\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Cuestomers
Route::resource('customers', CustomerController::class, ['except' => ['show','create','edit']]);
Route::post('customer', [CustomerController::class, 'show_one'])->name('customers.show_one');

//Products
Route::resource('products', ProductController::class, ['except' => ['show','create','edit']]);
Route::post('product', [ProductController::class, 'show_one'])->name('products.show_one');

//Tickets
Route::resource('tickets', TicketController::class, ['except' => ['create','edit']]);