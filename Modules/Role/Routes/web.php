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

Route::prefix('roles')->group(function() {
    Route::get('/', 'RoleController@index')->name('roles.index');
    Route::any('add_role','RoleController@addrole')->name('roles.add');
    Route::any('edit/{id}','RoleController@editrole')->name('roles.edit');
    Route::any('view/{id}','RoleController@rolePermissions')->name('roles.view');
    Route::get('delete/{id}','RoleController@deleterole')->name('roles.delete');
    Route::post('permission/{role_id}/save','RoleController@saveRolePermissions')->name('roles.permission.save');
});
