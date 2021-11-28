<?php

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

Route::prefix('accounts')->group(function() {
    Route::get('/', 'AccountsController@index')->name('accounts.index');
    Route::any('/view_code/{id}', 'AccountsController@get_code')->name('accounts.view_code');
    Route::any('/new_code/{id}', 'AccountsController@new_code')->name('accounts.new_code');
    Route::any('/customer_receive', 'AccountsController@customer_receive')->name('accounts.customer_receive');
    Route::get('/get_bank', 'AccountsController@get_bank')->name('accounts.get_bank');
    Route::get('/get_accode/{id}', 'AccountsController@get_accode')->name('accounts.get_accode');
    Route::get('/cash_adjusment', 'AccountsController@index')->name('accounts.cash_adjusment');
    Route::any('/debit_voucher', 'AccountsController@debit_voucher')->name('accounts.debit_voucher');
    Route::any('/credit_voucher', 'AccountsController@credit_voucher')->name('accounts.credit_voucher');
    Route::any('/contra_voucher', 'AccountsController@contra_voucher')->name('accounts.contra_voucher');
    Route::any('/cr_check/{check_id?}', 'AccountsController@cr_check')->name('accounts.cr_check');
    Route::any('/journal_voucher', 'AccountsController@journal_voucher')->name('accounts.journal_voucher');
    Route::get('/voucher_approve', 'AccountsController@voucher_approve')->name('accounts.voucher_approve');
    Route::get('/approve_voucher/{v_id}', 'AccountsController@approve_voucher')->name('accounts.approve_voucher');
    // Cashbook
    Route::get('/cash_book', 'AccountsController@cash_book')->name('accounts.cash_book');
    Route::get('/cash_book_filter', 'AccountsController@cash_book_filter')->name('account.CashBookFilter');

    Route::get('/inventory_ledger', 'AccountsController@inventory_ledger')->name('accounts.inventory_ledger');
    Route::get('/inventory_ledger_by_date', 'AccountsController@inventory_ledger_by_date')->name('accounts.inventory_ledger_by_date');
    Route::get('/bank_book', 'AccountsController@bank_book')->name('accounts.bank_book');
    Route::get('/bank_book_filter', 'AccountsController@bank_book_filter')->name('account.BankBookFilter');

    Route::get('/general_ledger', 'AccountsController@general_ledger')->name('accounts.general_ledger');
    Route::get('/is_gl_search', 'AccountsController@is_gl_search')->name('accounts.is_gl_search');

    Route::get('/trial_balance', 'AccountsController@trial_balance')->name('accounts.trial_balance');
    Route::get('/trail_balance_by_date', 'AccountsController@trial_balance_by_date')->name('accounts.trail_balance_by_date');
    Route::get('/cash_flow', 'AccountsController@index')->name('accounts.cash_flow');
    Route::get('/coa_print', 'AccountsController@index')->name('accounts.coa_print');
    Route::get('/voucher_list', 'AccountsController@voucher_list')->name('accounts.voucher_list');
    Route::get('/balance_sheet', 'AccountsController@balance_sheet')->name('accounts.balance_sheet');
    Route::get('/balance_sheet_by_date', 'AccountsController@balance_sheet_by_date')->name('accounts.balance_sheet_by_date');
    Route::get('/print_voucher/{v_id}', 'AccountsController@print_voucher')->name('accounts.print_voucher');
    Route::get('/print_cashbook/{v_id}', 'AccountsController@print_cashbook')->name('accounts.print_cashbook');
    Route::get('/checks_in_hand', 'AccountsController@checks_ih')->name('accounts.checks_ih');
    Route::get('/check_update/{id}/{status}', 'AccountsController@check_update')->name('accounts.check_update');

    //balance search route
    Route::get('/balance','AccountsController@balance_search')->name('search.balance');


    // Profit and Loss Routes
    Route::get('/profit_loss', 'ProfitAndLossController@index')->name('accounts.profit_loss');
    Route::get('/profit_loss_by_date', 'ProfitAndLossController@search_by_date')->name('accounts.profit_loss_by_date');
});
