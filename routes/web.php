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

Route::get('/', function () {
    return view('landing');
})->name('index');

Route::group(['middleware' => 'auth:sanctum', 'verified'], function(){
    Route::group(['prefix' => 'admin'], function() {
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
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
