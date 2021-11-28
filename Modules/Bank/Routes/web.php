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

Route::prefix('bank')->group(function() {
    Route::get('/index', 'BankController@index')->name('bank.index');
    Route::any('add', 'BankController@addBank')->name('bank.addBank');
    Route::get('view/{id}','BankController@viewBank')->name('bank.viewBank');
    Route::any('edit/{id}','BankController@editBank')->name('bank.editBank');
    Route::get('delete/{id}','BankController@deleteBankAccount')->name('bank.deleteBankAccount');
    Route::any('/transactions', 'BankController@create_transaction')->name('bank.transactions');
    Route::any('/ledgers', 'BankController@ledgers')->name('bank.ledgers');
    Route::any('/view_ledgers', 'BankController@view_ledgers')->name('bank.view_ledgers');
});
