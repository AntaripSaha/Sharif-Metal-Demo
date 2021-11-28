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

Route::prefix('product')->group(function() {
    Route::get('/index', 'ProductController@index')->name('product.index');
    Route::any('/add', 'ProductController@add_product')->name('product.add_product');
    Route::get('/view/{id}','ProductController@view_product')->name('product.view_product');
    Route::any('/edit/{id}','ProductController@edit_product')->name('product.edit_product');    
    Route::get('/unit', 'UnitController@index')->name('product.unit_index');
    Route::any('/unit/add', 'UnitController@add_unit')->name('product.add_unit');
    Route::get('/unit/view/{id}','UnitController@viewUnit')->name('product.view_Unit');
    Route::any('/unit/edit/{id}','UnitController@editUnit')->name('product.edit_unit');
    Route::get('/unit/delete/{id}','UnitController@deleteUnit')->name('product.delete_Unit');
    Route::get('/category', 'CategoryController@index')->name('product.category_index');
    Route::any('/category/add', 'CategoryController@add_category')->name('product.add_category');
    Route::get('/category/view/{id}','CategoryController@view_category')->name('product.view_category');
    Route::any('/category/edit/{id}','CategoryController@edit_category')->name('product.edit_category');
    Route::get('/category/delete/{id}','CategoryController@delete_category')->name('product.delete_category');
    Route::get('/get_price/{id}','ProductController@get_price')->name('product.get_price');
    
    //Product Reports
    Route::get('/reports', 'ProductController@productReports')->name('product.reports'); 
    Route::get('/print_product_report', 'ProductController@printProductReport')->name('product.print_product');
    Route::get('/product_searching', 'ProductController@productSearching')->name('product.product_searching');
});
