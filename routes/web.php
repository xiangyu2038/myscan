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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('tests', function () {
   dd(__LINE__);
});

////测试
Route::get('test', function (){
    return view('admin.test');
})->name('admin.test');

////对桌面新开窗口
///
Route::get('/', function (){
    return view('admin.boot');
})->name('admin.boot');



/////////web  desktop  桌面
Route::get('webdesktop', 'Admin\IndexController@index');



Route::namespace('Admin\MyScan')->prefix('myscan')->group(function () {
    Route::get('/', 'IndexController@index')->name('admin.myscan.index.index');
    Route::get('api/{user}', function (App\User $user) {
        dd(__LINE__);
    });
});

Route::namespace('Admin\Tool')->prefix('tool')->group(function () {
    Route::any('/', 'IndexController@index')->name('admin.tool.index.index');
    Route::any('/convertData', 'IndexController@convertData')->name('admin.tool.index.convert_data');
});







