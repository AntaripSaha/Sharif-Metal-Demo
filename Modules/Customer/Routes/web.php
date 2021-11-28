<?php

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

Route::prefix('customer')->group(function() {
    Route::get('/index', 'CustomerController@index')->name('customer.index');
    Route::any('/add', 'CustomerController@addCustomer')->name('customer.addCustomer');
    Route::any('/view_customer/{id}', 'CustomerController@viewCustomer')->name('customer.viewCustomer');
    Route::any('/update_customer/{id}', 'CustomerController@updateCustomer')->name('customer.updateCustomer');
    Route::get('/credit_customer', 'CustomerController@credit_customer')->name('customer.credit_customer');
    Route::get('/paid_customer', 'CustomerController@paid_customer')->name('customer.paid_customer');
    Route::get('/customer_ledger', 'CustomerController@customer_ledger')->name('customer.customer_ledger');
    Route::any('/customer_advance', 'CustomerController@customer_advance')->name('customer.customer_advance');
    Route::any('/view_ledgers', 'CustomerController@view_ledgers')->name('customer.view_ledgers');
    Route::any('/delete/{id}', 'CustomerController@deleteCustomer')->name('customer.deleteCustomer');
    Route::any('/import_file', 'CustomerController@import_file')->name('customer.import_file');
});
