<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth','superAdmin'])->prefix('admin/companies')->group(function () {
		Route::get('','CompanyController@index')->name('admin.companies.index');
		Route::get('users','CompanyController@users')->name('admin.companies.users');
        Route::any('add','CompanyController@addCompany')->name('admin.companies.add');
        Route::any('edit/{id}','CompanyController@editCompany')->name('admin.companies.edit');
        Route::get('view/{id}','CompanyController@viewCompany')->name('admin.companies.view');
        Route::get('delete/{id}','CompanyController@deleteCompany')->name('admin.companies.delete');
        Route::post('ajax-users','CompanyController@organizationUser')->name('admin.companies.organizationUser');
});

Route::middleware(['web', 'auth'])->prefix('company')->group(function () {
    Route::get('settings', 'CompanyController@settings')->name('organization.settings');
});
