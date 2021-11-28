<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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

// Cache Clear Route
Route::get('/cache', function (){
   Artisan::call('vendor:publish --tag=laravel-errors');
//   Artisan::call('migrate');
   return "Success";
});


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('country-code/{country_code}','BaseController@ajaxGetCountryCode')->name('ajaxGetCountryCode');
