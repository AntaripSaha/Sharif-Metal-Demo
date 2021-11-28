<?php

Route::prefix('warehouse')->group(function() {
    Route::get('/', 'WarehouseController@index')->name('warehouse.index');
    Route::get('/products/{id?}', 'WarehouseController@wareproducts')->name('warehouse.products');
    Route::get('/prod_requests', 'WarehouseController@prod_requests')->name('warehouse.prod_requests');
    
    //unapprove request route
    Route::get('/unapprove/{id}', 'WarehouseController@unapprove_request')->name('warehouse.upapprove.sell.request');
    
    Route::get('/prod_q/{prod_id}/{req_id}/{head_code}', 'WarehouseController@prod_q')->name('warehouse.prod_q');
    Route::get('/prod_q/{prod_id}/{war_id}/{req_id}/{head_code}', 'WarehouseController@prod_quantity')->name('warehouse.prod_quantity');
    
    
    Route::get('/wareproducts/{id?}', 'WarehouseController@ware_product')->name('warehouse.ware_products');
    Route::get('/stockproduct/{id?}', 'WarehouseController@stockproduct')->name('warehouse.stockproduct');
    Route::any('/add_wareproduct', 'WarehouseController@add_wareproduct')->name('warehouse.add_wareproduct');
    Route::any('/add', 'WarehouseController@add_warehouse')->name('warehouse.add_warehouse');
    Route::get('/view/{id}','WarehouseController@view_warehouse')->name('warehouse.view_warehouse');
    Route::any('/edit/{id}','WarehouseController@edit_warehouse')->name('warehouse.edit_warehouse');
    Route::get('/delete/{id}','WarehouseController@delete_warehouse')->name('warehouse.delete_warehouse');
    Route::any('/sell_req_details/{id}', 'WarehouseController@sell_req_details')->name('warehouse.sell_req_details');
    Route::any('/deliver', 'WarehouseController@deliver')->name('warehouse.deliver');

    Route::any('/stock/{ware_id?}', 'WarehouseController@stock')->name('warehouse.stock');
    Route::any('/stock_details', 'WarehouseController@stock_details')->name('warehouse.stock_details');
    Route::get('/stock_details/print', 'WarehouseController@stockDetailsPrint')->name('warehouse.stock_details_print');
    Route::get('/stock_movement_report', 'WarehouseController@stockMovementReport')->name('warehouse.stock_movement');
    Route::post('/stock_movement_report/print', 'WarehouseController@stockMovementReportPrint')->name('warehouse.stock_movement_print');
    Route::get('/search/stock_movement_report', 'WarehouseController@stockMovementReportSearch')->name('warehouse.search_stock_report_search');

    Route::get('/search/stock_report_details', 'WarehouseController@searchStockReportDetails')->name('warehouse.search_stock_report_details');
    Route::any('/return', 'WarehouseController@deliver')->name('warehouse.return');
    Route::any('/stock_return', 'WarehouseController@deliver')->name('warehouse.stock_return');
    Route::any('/supplier_return', 'WarehouseController@deliver')->name('warehouse.supplier_return');
    Route::any('/wastage_return', 'WarehouseController@deliver')->name('warehouse.wastage_return');
    Route::get('/combo_prod/{id}/{ware_id}', 'WarehouseController@combo_prod')->name('warehouse.combo_prod');
    Route::get('/print_chalan/{id}', 'WarehouseController@print_chalan')->name('warehouse.print_chalan');
    
    
    //edit stock
    Route::get("/edit/stock/{id}","WarehouseController@edit_stock_modal")->name("edit.stock.modal");
    Route::post("/edit/stock/{id}","WarehouseController@edit_stock")->name("edit.stock");
    
});
