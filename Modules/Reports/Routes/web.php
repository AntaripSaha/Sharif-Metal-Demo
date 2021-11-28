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

Route::prefix('reports')->group(function() {
    Route::get('/', 'ReportsController@index')->name('reports.index');
    Route::get('/sales_person', 'ReportsController@sales_person_wise')->name('reports.sales_person');
    Route::get('/sell_report_by_seller', 'ReportsController@sell_report_by_seller')->name('reports.sell_report_by_seller');

    Route::get('/customer_wise', 'ReportsController@customer_wise')->name('reports.customer_wise');
    Route::get('/customer_wise_print', 'ReportsController@customer_wise_print')->name('reports.customer_wise_print');

    Route::get('/sell_report_by_customer', 'ReportsController@sell_report_by_customer')->name('reports.sell_report_by_customer');
    Route::get('/product_wise', 'ReportsController@product_wise')->name('reports.product_wise');
    Route::get('/product/{pr_id?}/{wr_id?}', 'ReportsController@product_idby')->name('reports.product_idby');
    Route::get('/company_wise', 'ReportsController@company_wise')->name('reports.company_wise');
    Route::get('/sell_report_by_company', 'ReportsController@sell_report_by_company')->name('reports.sell_report_by_company');


    // Customer/Party Sales Report Ladger List
    Route::get('/sell_report_by_customer_details/{customer_id}/{company_id?}/{from?}/{to?}', 'ReportsController@report_customer_details')->name('reports.customer_details');
    Route::get('/sell_report_by_seller_details/{id}/{company_id?}', 'ReportsController@report_seller_details')->name('reports.seller_details');
    Route::get('/sell_report_by_seller_details_search', 'ReportsController@sell_report_by_seller_details_search')->name('reports.seller_details_by_search');
    
    //daily sales report route start
    Route::get('/daily_sales_report', 'ReportsController@daily_sales_report')->name('reports.daily_sales_report');
    Route::get('/print_daily_sales_report', 'ReportsController@print_daily_sales_report')->name('reports.print_daily_sales_report');
    
    //daily sales report route search
    Route::get('/search/daily_sales_report', 'ReportsController@daily_sales_report_search')->name('reports.daily_sales_report.search');
    
    //daily sales report route search pdf
    Route::get('/search/daily_sales_report/pdf/{date}', 'ReportsController@daily_sales_report_search_pdf')->name('reports.daily_sales_report.search.pdf');

});
