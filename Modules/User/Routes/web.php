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

Route::prefix('users')->group(function() {
    Route::get('/dashboard', 'UserController@index')->name('users.index');
//    Route::get('/dashboard', function(){
//        return view('errors.under_construction');
//    });
    Route::get('/user_list', 'UserController@user_list')->name('users.user_list');
    Route::any('/add_user', 'UserController@add_user')->name('users.add');
    Route::any('/import_sellers', 'UserController@importSellers')->name('users.importSellers');
    Route::get('/view/{id}','UserController@view_user')->name('users.view_customer');
    
    Route::any('/edit/{id}','UserController@edit_user')->name('users.edit_user');
    
    //change password
    
    Route::get('/delete/{id}','UserController@deleteUser')->name('users.deleteUser');

    // AJAX Call for Product Request Chart Value
    Route::get('/product_request_chart','UserController@getProductRequestChart')->name('product_request_chart');
    Route::get('/change_user_password/{id}', 'UserController@changeUserPasswordPage')->name('change_user_password');
    Route::post('/change_user_password/{id}', 'UserController@changePasswordPost')->name('user.changePasswordPost');
});
