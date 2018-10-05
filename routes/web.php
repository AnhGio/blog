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

Route::get('/login', "CalotteryController@showLogin")->name("login.show");
Route::post('/login', "CalotteryController@login")->name("login");
Route::middleware(['account'])->group(function () {
	Route::get('/', "CalotteryController@index")->name("csv.index");
	Route::post('/csv', "CalotteryController@getCsv")->name("csv.get");	
});
Route::get('/change-password', "CalotteryController@showChangePassword")->name("show.password");
Route::post('/change-password', "CalotteryController@changePassword")->name("change.password");