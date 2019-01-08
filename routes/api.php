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
Route::namespace('Api\Storage')->group(/**
 *
 */
    function () {
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


    /////修改盘点单里某个库位的产品数量
        Route::any('/alertStockCountFashionNum', 'StockController@alertStockCountFashionNum')->name('alert_stockCount_fashion_num');

        ////查询移位单记录
        Route::any('/moveStockRecord', 'StockController@moveStockRecord')->name('move_stock_record');


        ////导出信息
        Route::any('/export', 'StockController@export')->name('export');
    ////////////////////////////////////////////////////////////////////////////////////



});
Route::namespace('Api\PDA')->prefix('pda')->group(function () {
    ///登陆注册
    Route::any('/login', 'LoginController@login')->name('login');
    Route::any('/logout', 'LoginController@logout')->name('logout');
    Route::any('/refresh', 'LoginController@refresh')->name('refresh');

    Route::get('/test', 'PDAController@test')->name('test');
    Route::any('/stockCountList', 'PDAController@stockCountList')->name('stock_count_list');
    Route::any('/submitScan', 'PDAController@submitScan')->name('submit_scan');

    Route::any('/addStockIn', 'PDAController@addStockIn')->name('add_stock_in');
    Route::any('/stockInList', 'PDAController@stockInList')->name('stock_in_list');

    Route::any('/addStockOut', 'PDAController@addStockOut')->name('add_stock_out');
    Route::any('/stockOutList', 'PDAController@stockOutList')->name('stock_out_list');

    Route::any('/setEndCheckNeedle', 'PDAController@setEndCheckNeedle')->name('set_end_check_needle');
    Route::any('/submitAudit', 'PDAController@submitAudit')->name('submit_audit');

    //////列出本次没有绑定的箱子
    Route::any('/listNotBindingBox', 'PDAController@listNotBindingBox')->name('list_not_binding_box');

    ////新增一个移位单
    Route::any('/addMoveList', 'PDAController@addMoveList')->name('add_move_list');

    /////////查询一个库位里面有啥 有箱子 有产品
    Route::any('/queryStockHas', 'PDAController@queryStockHas')->name('query_stock_has');
    //////进行移位操作
    Route::any('/applyMoveStock', 'PDAController@applyMoveStock')->name('apply_move_stock');

    ////出库动作
    Route::any('/stockOut', 'PDAController@stockOut')->name('stock_out');
});
