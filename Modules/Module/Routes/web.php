<?php

Route::middleware(['web', 'auth','superAdmin'])->prefix('admin/modules')->group(function () {
    
	Route::get('','ModuleController@index')->name('admin.modules.index');
	Route::get('company/{id}','ModuleController@modulePermissions')->name('admin.modules.company_permissions');
    Route::post('tree','ModuleController@organizationTree')->name('admin.modules.treeView');
    Route::post('ajax-permissions','ModuleController@modulePermissions')->name('admin.modules.permission');
    Route::post('permission/{company_id}/save','ModuleController@saveModulePermissions')->name('modules.permission.save');

});
