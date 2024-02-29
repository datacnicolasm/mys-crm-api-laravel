<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\Ticket\TicketRefController;
use App\Http\Controllers\Ticket\TypeTicketController;
use App\Http\Controllers\User\UserController;

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
Route::resource('tickets', TicketController::class, ['except' => ['show','update','create','edit']]);
Route::post('ticketProduct', [TicketController::class, 'get_product'])->name('tickets.get_product');
Route::post('ticket', [TicketController::class, 'show_one'])->name('tickets.show_one');
Route::post('ticketUpdate', [TicketController::class, 'update_one'])->name('tickets.update_one');

//Users
Route::resource('users', UserController::class, ['except' => ['show','create','edit']]);
Route::post('user', [UserController::class, 'show_one'])->name('users.show_one');

//Types tickets
Route::resource('typetickets', TypeTicketController::class, ['except' => ['show','update','create','edit']]);

//Referencias tickets
Route::resource('ticketRef', TicketRefController::class, ['except' => ['show','create','edit']]);