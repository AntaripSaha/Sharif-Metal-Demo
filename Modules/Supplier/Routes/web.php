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



Route::prefix('supplier')->group(function() {
    Route::get('/index', 'SupplierController@index')->name('supplier.index');
    Route::get('/supplier_ledger', 'SupplierController@supplier_ledger')->name('supplier.ledgers');
    Route::any('/view_ledgers', 'SupplierController@view_ledgers')->name('supplier.view_ledgers');
    Route::get('/advance', 'SupplierController@index')->name('supplier.advance');
    Route::any('/add_supplier', 'SupplierController@add_supplier')->name('supplier.add_supplier');
    Route::get('/view_supplier/{id}', 'SupplierController@view_supplier')->name('supplier.view_supplier');
    Route::get('delete/{id}', 'SupplierController@delete')->name('supplier.delete');

    // Supplier Products Reports
    Route::get('/products', 'SupplierController@supplierProducts')->name('supplier.products');
    Route::get('/supplier_product_view', 'SupplierController@supplier_product_view')->name('supplier.supplier_product_view');
});
