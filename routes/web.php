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
    Route::get('/dealFaHuo/{batch_id}', 'IndexController@dealFaHuo')->name('admin.myscan.index.deal_fa_huo');
    Route::any('/addFaHuo', 'IndexController@addFaHuo')->name('admin.myscan.index.add_fa_huo');
    Route::get('/editAliasCode', 'IndexController@editAliasCode')->name('admin.myscan.index.edit_alias_code');
    Route::get('/setKDCompany', 'IndexController@setKDCompany')->name('admin.myscan.index.set_kd_company');
    Route::get('/printVip', 'IndexController@printVip')->name('admin.myscan.index.print_vip');

    ///打印全部学生条码
    Route::get('/printAllList', 'IndexController@printAllList')->name('admin.myscan.index.print_all_list');

    ///部分打印条码
    Route::any('/printList', 'IndexController@printList')->name('admin.myscan.index.print_list');

    ////扫描页面
    Route::any('/scaning', 'IndexController@scaning')->name('admin.myscan.index.scaning');

    ////扫描列表路由 包含 已扫描 未扫描 未全部扫描等
    Route::any('/scanList', 'IndexController@scanList')->name('admin.myscan.index.scan_list');

    /////一个包裹的详情 页
    Route::get('/packageDetail', 'IndexController@packageDetail')->name('admin.myscan.index.package_detail');

    /////预先取快递
    Route::get('/prevKD', 'IndexController@prevKD')->name('admin.myscan.index.prev_k_d');

    ////清空一个包裹
    Route::get('/clearPackage', 'IndexController@clearPackage')->name('admin.myscan.index.clear_package');

    ////获取包裹的实时扫描数据
    Route::get('/printListScan', 'IndexController@printListScan')->name('admin.myscan.index.print_list_scan');


    ///扫描结束 配送出库页面
    Route::get('/endScan', 'IndexController@endScan')->name('admin.myscan.index.end_scan');

    ///确认出库 确认出库页面
    Route::get('/goOut', 'IndexController@goOut')->name('admin.myscan.index.go_out');

    ///导出理货单
    Route::get('/exportLiHuo', 'IndexController@exportLiHuo')->name('admin.myscan.index.export_li_huo');

    ///导出快递
    Route::get('/exportKuaiDi', 'IndexController@exportKuaiDi')->name('admin.myscan.index.export_kuai_di');

    ///导出缺货信息
    Route::get('/exportProInfo', 'IndexController@exportProInfo')->name('admin.myscan.index.export_pro_info');

    ///导出出库产品清单
    Route::get('/exportOutList', 'IndexController@exportOutList')->name('admin.myscan.index.export_out_list');

    ///编辑一个要发货的学生的地址信息
    Route::any('/editAddress', 'IndexController@editAddress')->name('admin.myscan.index.edit_address');


    ///编辑一类产品的产品编码 针对领带领结等产品
    Route::any('/editFashionSize', 'IndexController@editFashionSize')->name('admin.myscan.index.edit_fashion_size');

    Route::get('api/{user}', function (App\User $user) {
        dd(__LINE__);
    });
});

Route::namespace('Admin\Tool')->prefix('tool')->group(function () {
    Route::any('/', 'IndexController@index')->name('admin.tool.index.index');
    Route::any('/convertData', 'IndexController@convertData')->name('admin.tool.index.convert_data');
});


