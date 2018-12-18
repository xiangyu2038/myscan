<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    dd(__LINE__);
    return $request->user();
});

Route::namespace('Api')->group(function () {
    //////发货查询接口
    Route::get('/wuLiu', 'SellOrderController@wuLiu')->name('wu_liu');

});

//给一个预售新建一个批次
Route::namespace('Api\Storage')->group(function () {
     /////////////////////////////////////////////////////////////////////////
    /////箱子列表
    Route::get('/boxList', 'BoxManageController@boxList')->name('box_list');
    /////添加一个箱子
    Route::get('/addBox', 'BoxManageController@addBox')->name('add_box');
    /////删除一个箱子
    Route::get('/delBox', 'BoxManageController@delBox')->name('del_box');
    ////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////
    /////库位展示
    Route::get('/stockList', 'StockController@stockList')->name('stock_list');

    /////入库单列表
    Route::get('/stockInList', 'StockController@stockInList')->name('stock_in_list');
    /////一个入库单的详情
    Route::get('/stockInListDetail', 'StockController@stockInListDetail')->name('stock_in_list_detail');
    //////一个入库单针检的的产品列表
    Route::get('/verifyStockIn', 'StockController@verifyStockIn')->name('verify_stock_in');
    /////出库单列表
    Route::get('/stockOutList', 'StockController@stockOutList')->name('stock_out_list');

    /////出库单信息
    Route::get('/stockInListInfo', 'StockController@stockInListInfo')->name('stock_in_list_info');

    /////出库单详情
    Route::get('/stockOutListInfo', 'StockController@stockOutListInfo')->name('stock_out_list_info');

    /////一个出库单的详情
    Route::get('/stockOutListDetail', 'StockController@stockOutListDetail')->name('stock_out_list_detail');

    ///////启动一个盘点单
    Route::get('/startStockCount', 'StockController@startStockCount')->name('start_stock_count');

    ///////盘点单列表
    Route::get('/stockCountList', 'StockController@stockCountList')->name('stock_count_list');
    ///////盘点单信息
    Route::get('/stockCountInfo', 'StockController@stockCountInfo')->name('stock_count_info');
    /////盘点单详情
    Route::get('/stockCountDetail', 'StockController@stockCountDetail')->name('stock_count_detail');

    /////盘点单详情 带库位的详情
    Route::get('/stockCountDetail', 'StockController@stockCountDetail')->name('stock_count_detail');
    ///////盘点单信息
    Route::get('/stockCountListDetail', 'StockController@stockCountListDetail')->name('stock_count_list_detail');


    //////一个库位里面的详情
    Route::get('/stockDetail', 'StockController@stockDetail')->name('stock_detail');


    /////库位流水记录
    Route::get('/stockInRecord', 'StockController@stockInRecord')->name('stock_in_record');
    Route::get('/stockOutRecord', 'StockController@stockOutRecord')->name('stock_out_record');

    /////库位查询
    Route::get('/queryStock', 'StockController@queryStock')->name('query_stock');
    Route::get('/fashionList', 'StockController@fashionList')->name('fashion_list');

   /////添加一个盘点单
    Route::post('/addStockCount', 'StockController@addStockCount')->name('add_stock_count');


    ////////////////////////////////////////////////////////////////////////////////////



});
Route::namespace('Api\PDA')->prefix('pda')->group(function () {
    Route::get('/test', 'PDAController@test')->name('test');
    Route::any('/stockCountList', 'PDAController@stockCountList')->name('stock_count_list');
    Route::any('/submitScan', 'PDAController@submitScan')->name('submit_scan');

    Route::any('/addStockIn', 'PDAController@addStockIn')->name('add_stock_in');
    Route::any('/stockInList', 'PDAController@stockInList')->name('stock_in_list');
    Route::any('/setEndCheckNeedle', 'PDAController@setEndCheckNeedle')->name('set_end_check_needle');
    Route::any('/submitAudit', 'PDAController@submitAudit')->name('submit_audit');



});
