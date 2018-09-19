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

Route::get('/', function () {
    return view('welcome');
});
Route::get('test', function () {
   dd(__LINE__);
});

Route::namespace('Myscan')->prefix('myscan')->group(function () {
    Route::get('/{user}', 'indexController@index')->name('mysan.index.index');
    Route::get('api/{user}', function (App\User $user) {
        dd(__LINE__);
    });
});

;


