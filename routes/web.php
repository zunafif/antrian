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

Route::get('/logout','Auth\LoginController@logout')->name('logout');
Auth::routes();
Route::post('/login','Auth\LoginController@login')->name('login');

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('landing');
})->name('index');

Route::group(['middleware' => 'web'], function(){
    Route::group(['middleware' => ['role:admin']], function (){
        Route::group(['prefix' => 'master_loket'], function () {
            Route::get('', 'CounterController@index')->name('counter_master.index');
            Route::get('create', 'CounterController@create')->name('counter_master.create');
            Route::get('edit/{id}', 'CounterController@edit')->name('counter_master.edit');
            Route::post('store', 'CounterController@store')->name('counter_master.store');
            Route::post('delete', 'CounterController@delete')->name('counter_master.delete');
        });
        
        Route::group(['prefix' => 'master_pengguna'], function() {
            Route::get('', 'UserController@index')->name('user_master.index');
            Route::get('create', 'UserController@create')->name('user_master.create');
            Route::get('edit/{id}', 'UserController@edit')->name('user_master.edit');
            Route::post('store', 'UserController@store')->name('user_master.store');
            Route::post('delete', 'UserController@delete')->name('user_master.delete');
        });
        // Route::group(['prefix' => 'master_suara'], function() {
        //     Route::get('', 'SoundController@index')->name('sound_master.index');
        //     Route::get('create', 'SoundController@create')->name('sound_master.create');
        //     Route::get('edit/{id}', 'SoundController@edit')->name('sound_master.edit');
        //     Route::post('store', 'UserController@store')->name('sound_master.store');
        //     Route::post('delete', 'UserController@delete')->name('sound_master.delete');
        // });
    });
    Route::group(['middleware' => ['role:admin|counter']], function (){
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