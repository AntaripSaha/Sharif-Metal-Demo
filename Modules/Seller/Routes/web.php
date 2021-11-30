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

Route::prefix('seller')->group(function () {
    Route::get('/index', 'SellerController@index')->name('seller.index');
    Route::any('/sell_req', 'SellerController@add_sell')->name('seller.add_sell');
    Route::any('/add_collection', 'SellerController@add_collection')->name('seller.add_collection');
    Route::any('/sell_req_details/{id}', 'SellerController@sell_req_details')->name('seller.sell_req_details');
    Route::any('/sold_details/{id}', 'SellerController@sold_details')->name('seller.sold_details');


    Route::any('/bill_edit/{id}', 'SellerController@bill_edit')->name('bill.edit');
    Route::any('/bill_update/{id}', 'SellerController@bill_update')->name('bill.update');

    //Edit Requisition Details
    Route::any('/edit_sell_req_details/{id}', 'SellerController@edit_requisition_details')->name('edit_requisition_details');

    Route::get('/request_approve/{id}', 'SellerController@request_approve')->name('seller.sell_req_details');
    Route::get('/request_reject/{id}', 'SellerController@request_reject')->name('seller.sell_req_reject');
    Route::get('/cancel_request_reject/{id}', 'SellerController@cancel_request_reject')->name('seller.cancel_sell_req_reject');

    Route::get('/my_sales/', 'SellerController@my_sales')->name('seller.my_sales');
    Route::get('/manage_sales/', 'SellerController@manage_sales')->name('seller.manage_sales');
    Route::get('/sales-discount-edit/{id}', 'SellerController@sales_discount_edit')->name('sales.discount.edit');
    Route::post('/sales-discount-edit/{id}', 'SellerController@sales_discount_update')->name('sales.discount.update');
    Route::get('/manage_sales/rejected/{id}', 'SellerController@rejected_status')->name('rejected.sales');
    Route::get('/manage_chalan/', 'SellerController@manage_chalan')->name('seller.manage_chalan');
    Route::get('/print_invoice/{id}', 'SellerController@print_invoice')->name('seller.print_invoice');

    //print challan
    Route::get('/print_bill/{id}/{group}', 'SellerController@print_bill')->name('seller.print_bill');


    Route::get('/undelivered_sales', 'SellerController@undelivered_sales')->name('seller.undelivered_sales');
    // All all_undelivered_products Route
    Route::get('/all_undelivered_products_report', 'SellerController@all_undelivered_products')->name('all_undelivered_products');
    Route::get('/undelivered_product_search', 'SellerController@undelivered_product_search')->name('undelivered_product_search');
    Route::get('/undelivered_product_print', 'SellerController@undelivered_product_print')->name('undelivered_product_print');

    Route::get('/undelivered_details/{id}', 'SellerController@undelivered_details')->name('seller.undelivered_details');
    Route::get('/undelivered_details/approve/{id}', 'SellerController@undelivered_details_approve')->name('seller.undelivered_details.approve');

    Route::get('/re_order/{id}', 'SellerController@re_order')->name('seller.re_order');
    Route::any('/direct_sale', 'SellerController@direct_sale')->name('seller.direct_sale');
    Route::any('/sales_bydate', 'SellerController@sales_bydate')->name('seller.sales_bydate');

    //rejected sales details
    Route::get('/rejected_sales', 'SellerController@rejected_sales')->name('seller.rejected.sales');

    // Print Sale Requisition before Approve
    Route::get('/sale_request_print/{id}', 'SellerController@SaleRequestPrint')->name('seller.sale_request_print');
    // Update Sale Discount Value
    Route::get('/update_sale_discount', 'SellerController@updateSaleDiscount')->name('updateSaleDiscount');

    //Update Due Amount
    Route::get('/update_due_amount', 'SellerController@updateDueAmount')->name('updateDueAmount');

    //Export Excel
    Route::get('/export-excel/{id}', 'SellerController@sale_request_details_export_excel')->name('sale_req_details_export_excel');

    // Child Users Sell Requisitions
    Route::get('/child_users_requisition', 'SellerController@child_users_requisition')->name('child_users_requisition');

    //Change Password Route Start
    Route::get("/changepassword/{id}", "SellerController@change_password_page")->name("seller.change.password.page");
    Route::post("/changepassword/{id}", "SellerController@change_password")->name("seller.change.password");
});
