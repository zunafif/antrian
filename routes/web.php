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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/logout','Auth\LoginController@logout');
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('landing');
})->name('index');

// Route::group(['middleware' => 'auth:sanctum', 'verified'], function(){
Route::group(['middleware' => 'web'], function(){
    // Route::group(['middleware' => 'permission:access administrative'], function(){
    Route::group(['middleware' => ['role:admin']], function (){
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::group(['prefix' => 'manajemen_antrian'], function () {
            Route::get('{filter?}', 'QueuefoController@index')->name('queuefo.read');
            Route::post('checkData', 'QueuefoController@checkData')->name('queuefo.check');
            Route::post('next','QueuefoController@next')->name('queuefo.next');
            Route::post('skip','QueuefoController@skip')->name('queuefo.skip');
            Route::post('ready','QueuefoController@ready')->name('queuefo.ready');
            Route::post('set_fo','QueuefoController@setFoQueue')->name('queuefo.set_fo');
        });
    });
});

Route::group(['prefix' => 'antrian'], function () {
    Route::get('{filter?}', 'QueueinfoController@index')->name('queueinfo.read');
    Route::post('checkData', 'QueueinfoController@checkData')->name('queueinfo.check');
});

Route::group(['prefix' => 'master_loket'], function () {
    Route::get('', 'CounterController@index')->name('counter_master.index');
    Route::get('create', 'CounterController@create')->name('counter_master.create');
    Route::get('edit/{id}', 'CounterController@edit')->name('counter_master.edit');
    Route::post('store', 'CounterController@store')->name('counter_master.store');
    Route::post('delete', 'CounterController@delete')->name('counter_master.delete');
});