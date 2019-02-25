<?php

namespace App\Http\Controllers\Api\Storage;

use App\Helper\CommonHelper;
use App\Helper\ExcelHelper;
use App\Helper\ObjectHelper;
use App\Http\Controllers\Api\Controller;
use App\Models\Admin\BoxDetailModel;
use App\Models\Admin\BoxModel;
use App\Models\Admin\FashionModel;
use App\Models\Admin\StockBoxModel;

use App\Models\Admin\StockCountModel;
use App\Models\Admin\StockDetailModel;

use App\Models\Admin\StockInModel;
use App\Models\Admin\StockModel;
use App\Models\Admin\StockMoveModel;

use App\Models\Admin\StockOutModel;
use App\Models\Admin\StockScanBoxDetailModel;
use App\Models\Admin\StockScanBoxModel;
use App\Models\Admin\StockScanStockDetailModel;
use App\Models\Admin\StockScanStockModel;
use App\Service\Admin\StockService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use XiangYu2038\Wish\XY;
use XiangYu2038\Excel\Excel;



class StockController extends Controller
{

    /**
     * @api {get} /api/stockList 库位列表
     * @apiVersion 1.0.0
     * @apiName locationList
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} floor 楼层 3或者4
     * @apiParam {int} type 类型 1货架2托盘
     * @apiDescription 库位列表api
     *
     * @apiSampleRequest  /api/stockList
     *
     * @apiSuccess (返回值) {int} id 仓库id
     * @apiSuccess (返回值) {string} stock_sn 仓库编码
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":[{"stock_id":1,"stock_sn":"A01-Z01-C01"}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockList(Request $request){
        $floor = $request->get('floor');
        $type = $request->get('type');

        $stock_model = StockModel::where('floor', $floor)->where('type', $type)->get();

        $stock_list = this($stock_model,['id','stock_sn']);

        return response()->json(api_msg(0, 'ok', $stock_list));

    }

    /**
     * @api {get} /api/stockDetail 库位号里面的详情展示
     * @apiVersion 1.0.0
     * @apiName stockDetail
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int}  stock_id 库位或者托盘的id
     * @apiDescription 库位管理api
     *
     * @apiSampleRequest  /api/stockDetail
     *
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} school_name 学校名称
     * @apiSuccess (返回值) {string} box_sn 箱号编码
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":[{"fashion_name":"PE\u8fd0\u52a8\u88e4","fashion_code":"T1807040A","fashion_size":"180","fashion_num":5,"school_name":"\u60e0\u7075\u987f\u56fd\u9645\u5b66\u6821","box_sn":""},{"fashion_name":"PE\u8fd0\u52a8\u88e4","fashion_code":"T1807040A","fashion_size":"190","fashion_num":5,"school_name":"\u60e0\u7075\u987f\u56fd\u9645\u5b66\u6821","box_sn":""},{"fashion_name":"PE\u8fd0\u52a8\u88e4","fashion_code":"T1807040A","fashion_size":"170","fashion_num":5,"school_name":"\u60e0\u7075\u987f\u56fd\u9645\u5b66\u6821","box_sn":""}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */


    public function stockDetail(Request $request){
        $stock_id = $request -> get('stock_id');

        try{
            $stock_model = StockModel::where('id',$stock_id)->first();
            $stock_detail = $stock_model->stockDetail();
            $stock_detail = ObjectHelper::getInstance(FashionModel::class)->assortFashion($stock_detail);
            return response()->json(api_msg(0,'ok',$stock_detail));

        }catch (\Exception $e){
            return response()->json(api_msg(1,$e->getMessage()));
        }

    }


    /**
     * @api {get} /api/stockInRecord 产品入库流水  (字段改变 12.10)
     * @apiVersion 2.0.0
     * @apiName stockInRecord
     * @apiGroup group_so
     * @apiPermission 所有用户
     *

     * @apiDescription 产品入库流水api
     *
     * @apiSampleRequest  /api/stockInRecord
     *
     * @apiSuccess (返回值) {string} id id
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_size 产品编码
     * @apiSuccess (返回值) {string} in_num 入库数量
     * @apiSuccess (返回值) {string} stock_in.stock_in_sn 流水编号
     * @apiSuccess (返回值) {string} stock_in.operate 操作人员
     * @apiSuccess (返回值) {string} stock_in.type 类型
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/stockInRecord?page=2","total":2,"data":[{"id":1,"fashion_code":"07M10008A","fashion_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","fashion_size":"120","fashion_num":5,"stock_in":{"stock_in_sn":"123","type":"\u9000\u8d27\u5165\u5e93","operate":"\u9648\u7fd4\u5b87"}}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */

public function stockInRecord(Request $request){
   $search = $this -> searchCon($request);
   ///首先查询只入库存的产品
    $stock_scan_stock_model = StockScanStockDetailModel::where(function ($query)use($search){
         $query -> where('fashion_code','like','%'.$search['key_word'].'%');
    })->where('type','入库')->with('stock')->paginate(15);
    $stock_scan_stock_detail = this($stock_scan_stock_model,['fashion_code','fashion_name','fashion_size','fashion_num','stock'=>['stock_sn','type']]);////仅仅入库的

    ////查询箱子入库的
         ////查询入库的箱子的id
   $box_id =  StockScanBoxModel::where('stock_scan_stock_id','!=',0)->get()->pluck('box_id')->toArray();

   ////查询这个箱子的入库记录
   $box_scan_stock_model = StockScanBoxDetailModel::whereIn('box_id',$box_id)->where('type','入库')->with('box.stockBox.stock')->paginate(15);

    $box_stock_scan_stock_detail = this($box_scan_stock_model,['fashion_code','fashion_name','fashion_size','fashion_num','box'=>['stockBox'=>['stock'=>['stock_sn','type'],'chen_xiang_yu_'],'chen_xiang_yu_']]);////仅仅入库的

    $all  = array_merge($stock_scan_stock_detail,$box_stock_scan_stock_detail);


    return response()->json(api_msg(0,'ok',paginate_all($stock_scan_stock_model,$box_scan_stock_model,$all)));
}

    /**
     * @api {get} /api/stockOutRecord 产品出库流水(字段有所更改 12.10)
     * @apiVersion 2.0.0
     * @apiName stockOutRecord
     * @apiGroup group_so
     * @apiPermission 所有用户
     *

     * @apiDescription 产品出库流水api
     *
     * @apiSampleRequest  /api/stockOutRecord
     *
     * @apiSuccess (返回值) {string} id 流水记录id
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_size 产品编码
     * @apiSuccess (返回值) {string} out_num 出库数量
     * @apiSuccess (返回值) {string} stock_out.stock_out_sn 流水编号
     * @apiSuccess (返回值) {string} stock_out.type 入库类型
     * @apiSuccess (返回值) {string} stock_out.operate 操作人员
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"current_page":1,"data":[{"id":1,"fashion_code":"07M10008A","fashion_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","fashion_size":"120","fashion_num":5,"stock_out":{"stock_out_sn":"123","type":"\u9000\u8d27\u5165\u5e93","operate":"\u9648\u7fd4\u5b87"}}],"first_page_url":"http:\/\/myscan.dev.com\/api\/stockOutRecord?page=1","from":1,"last_page":2,"last_page_url":"http:\/\/myscan.dev.com\/api\/stockOutRecord?page=2","next_page_url":"http:\/\/myscan.dev.com\/api\/stockOutRecord?page=2","path":"http:\/\/myscan.dev.com\/api\/stockOutRecord","per_page":1,"prev_page_url":null,"to":1,"total":2}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockOutRecord(Request $request){
        $search = $this ->searchCon($request);
        ///首先查询只出库存的产品
        $stock_scan_stock_model = StockScanStockDetailModel::where(function ($query)use($search){
            $query -> where('fashion_code','like','%'.$search['key_word'].'%');
        })->where('type','出库')->with('stock')->paginate(15);
        $stock_scan_stock_detail = this($stock_scan_stock_model,['fashion_code','fashion_name','fashion_size','fashion_num','stock'=>['stock_sn','type']]);////仅仅入库的


        ////查询出库的箱子的id
        $box_id =  StockScanBoxModel::where('stock_scan_stock_id','!=',0)->get()->pluck('box_id')->toArray();

        ////查询这个箱子的出库记录
        $box_scan_stock_model = StockScanBoxDetailModel::whereIn('box_id',$box_id)->where('type','出库')->with('box.stockBox.stock')->paginate(15);

        $box_stock_scan_stock_detail = this($box_scan_stock_model,['fashion_code','fashion_name','fashion_size','fashion_num','box'=>['stockBox'=>['stock'=>['stock_sn','type'],'chen_xiang_yu_'],'chen_xiang_yu_']]);////仅仅入库的

        $all  = array_merge($stock_scan_stock_detail,$box_stock_scan_stock_detail);


        return response()->json(api_msg(0,'ok',paginate_all($stock_scan_stock_model,$box_scan_stock_model,$all)));
    }


    /**
     * @api {get} /api/queryStock 产品库存详情(字段有更改)
     * @apiVersion 2.0.0
     * @apiName queryStock
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} fashion_code 产品编码
     * @apiDescription 查询一个产品的库存api
     *
     * @apiSampleRequest  /api/queryStock
     *
     * @apiSuccess (返回值) {string} fashion_stock 库存字段
     * @apiSuccess (返回值) {string} fashion_info 产品信息字段
     * @apiSuccess (返回值) {string} x_h_stock 现货库存
     * @apiSuccess (返回值) {string} z_t_stock 在途库存
     * @apiSuccess (返回值) {string} d_j_stock 冻结库存
     * @apiSuccess (返回值) {string} fashion_info.code 编码
     * @apiSuccess (返回值) {string} fashion_info.school 学校
     * @apiSuccess (返回值) {string} fashion_info.real_name 产品名称
     * @apiSuccess (返回值) {string} fashion_info.old_code 关联编码
     * @apiSuccess (返回值) {string} fashion_info.style_name 款式号
     * @apiSuccess (返回值) {string} fashion_info.color 颜色
     * @apiSuccess (返回值) {string} fashion_info.pattern_name 类别
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} box_sn 箱子编号
     * @apiSuccess (返回值) {string} stock 库位
     * @apiSuccess (返回值) {string} stock.stock_sn 库位编码
     * @apiSuccess (返回值) {string} stock.stock_name 库位名称
     * @apiSuccess (返回值) {string} stock.floor 库位楼层
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":0,"msg":"ok","data":{"fashion_stock":{"x_h_stock":[{"fashion_name":null,"fashion_code":"07M10008A","fashion_size":"180","fashion_num":3,"box_sn":"CKXH201896c728","stock":{"stock_sn":"4-SA01Z01C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4}},{"fashion_name":null,"fashion_code":"07M10008A","fashion_size":"190","fashion_num":2,"box_sn":"CKXH201896c728","stock":{"stock_sn":"4-SA01Z01C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4}},{"fashion_name":null,"fashion_code":"07M10008A","fashion_size":"180","fashion_num":6,"box_sn":null,"stock":{"stock_sn":"4-SA01Z01C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4}},{"fashion_name":null,"fashion_code":"07M10008A","fashion_size":"190","fashion_num":1,"box_sn":null,"stock":{"stock_sn":"4-SA01Z01C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4}}],"z_t_stock":[],"d_j_stock":[]},"fashion_info":{"code":"07M10008A","school":"\u4e0a\u6d77\u5e02\u5929\u5c71\u4e2d\u5b66","real_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","old_code":"","style_name":"CT09","color":"\u85cf\u9752,\u767d\u8272","pattern_name":"2011CT"}}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
public function queryStock(Request $request){
    $fashion_code = $request -> get('fashion_code');
    $fashion_model = FashionModel::where('code',$fashion_code)->first();////查询

   if(!$fashion_model){
       return response()->json(api_msg(0,'产品库没有本产品'));
   }

    $fashion_stock = $fashion_model->stock();

    $fashion_info = this($fashion_model,['code','school','real_name','old_code','style_name','color','pattern_name']);

    return response()->json(api_msg(0,'ok',compact('fashion_stock','fashion_info')));

}


    /**
     * @api {get} /api/fashionList 库存查询列表
     * @apiVersion 1.0.0
     * @apiName fashionList
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} page 页数
     * @apiDescription 产品列表api
     *
     * @apiSampleRequest  /api/fashionList
     *
     * @apiSuccess (返回值) {string} code 产品编码
     * @apiSuccess (返回值) {string} api_msg 学校
     * @apiSuccess (返回值) {string} api_msg 产品名称
     * @apiSuccess (返回值) {string} api_msg 关联编码
     * @apiSuccess (返回值) {string} api_msg 款式号
     * @apiSuccess (返回值) {string} api_msg 颜色
     * @apiSuccess (返回值) {string} api_msg 类别
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"current_page":1,"data":[{"code":"07M10008A","school":"\u4e0a\u6d77\u5e02\u5929\u5c71\u4e2d\u5b66","real_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","old_code":"","style_name":"CT09","color":"\u85cf\u9752,\u767d\u8272","pattern_name":"2011CT"}],"first_page_url":"http:\/\/myscan.dev.com\/api\/fashionList?page=1","from":1,"last_page":6176,"last_page_url":"http:\/\/myscan.dev.com\/api\/fashionList?page=6176","next_page_url":"http:\/\/myscan.dev.com\/api\/fashionList?page=2","path":"http:\/\/myscan.dev.com\/api\/fashionList","per_page":1,"prev_page_url":null,"to":1,"total":6176}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */

public function fashionList(Request $request){
$key_word = $request ->get('key_word');
$type = $request ->get('type');
    $fashion = FashionModel::where(function ($query)use($type,$key_word){
          if($type=='fashion_code'){
            $query -> where('code','like','%'.$key_word.'%');
          }elseif($type=='school'){
              $query -> where('school','like','%'.$key_word.'%');
          }
    })->select(['code','school','real_name','old_code','style_name','color','pattern_name'])->paginate(10);////查询

    return response()->json(api_msg(0,'ok',$fashion));

}


    /**
     * @api {get} /api/stockInList 入库单列表
     * @apiVersion 1.0.0
     * @apiName stockInList
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiDescription 入库单列表api
     *
     * @apiSampleRequest  /api/stockInList
     *
     * @apiSuccess (返回值) {string} stock_in_sn 入库编码
     * @apiSuccess (返回值) {string} name 入库名称
     * @apiSuccess (返回值) {string} type 入库类型
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} created_at 操作日期
     * @apiSuccess (返回值) {string} status 审核状态
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/stockInList?page=2","total":2,"data":[{"stock_in_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","type":"\u9000\u8d27\u5165\u5e93","num":"50","operate":"\u9648\u7fd4\u5b87","created_at":null,"status":"\u5f85\u5ba1\u6838"}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
public function stockInList(Request $request){
    $stock_model_s = StockInModel::where(function (){
             //////预留搜索接口

    })->orderBy('created_at','DESC')->paginate(15);

    $stock_list = this($stock_model_s,['id','stock_in_sn','name','type','num','operate','created_at','status']);

    return response()->json(api_msg(0,'ok',paginate($stock_model_s,$stock_list)));

}


    /**
     * @api {get} /api/stockInListDetail 入库单详情(字段有所改变)
     * @apiVersion 1.0.0
     * @apiName stockInListDetail
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_in_id 入库单id
     * @apiDescription 入库单详情api
     *
     * @apiSampleRequest   /api/stockInListDetail
     *
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} school_name 学校名称
     * @apiSuccess (返回值) {string} stock.stock_sn 库位编码
     * @apiSuccess (返回值) {string} stock.stock_name 库位名称
     * @apiSuccess (返回值) {string} stock.floor 库位所在楼层
     * @apiSuccess (返回值) {string} stock.type 库位类型
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"ok","data":[{"fashion_code":"07M10008A","fashion_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","fashion_size":"120","fashion_num":5,"school_name":null,"stock":{"stock_sn":"A01-Z01-C01","stock_name":"A\u533a01","floor":4,"type":"\u8d27\u67b6"}},{"fashion_code":"07M10008A","fashion_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","fashion_size":"150","fashion_num":10,"school_name":null,"stock":{"stock_sn":"A01-Z01-C01","stock_name":"A\u533a01","floor":4,"type":"\u8d27\u67b6"}}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
public function stockInListDetail(Request $request){
     $stock_in_id =  $request -> get('stock_in_id');
     $op_sn = StockInModel::find($stock_in_id)->sn;
    $stock_scan_stock_model = StockScanStockModel::where('op_sn',$op_sn)->with('stockScanStockDetail.fashion')->with('stockScanBox.box.boxDetail.fashion')->with('stock')->get();

    ///仅仅库存的产品
    $one = this($stock_scan_stock_model,['stock_scan_stock_detail'=>['fashion_code','fashion_name','fashion_size','fashion_num','stock'=>['stock_sn','stock_name','floor'],'fashion'=>['school','chen_xiang_yu_'],'chen_xiang_yu_']]);

////仅仅箱子里的产品
$temp_one = [];
foreach ($one as $v){
    foreach ($v as $vv){

            $temp_one[] = $vv;


    }
}


    $two = this($stock_scan_stock_model,['stock'=>['stock_sn','stock_name','floor'],'stock_scan_box'=>['box'=>['box_detail'=>['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'chen_xiang_yu_'],'chen_xiang_yu_']]]);

    $temp_two = [];
    foreach ($two as $a){

        foreach ($a['stock_scan_box'] as $v){
            foreach ($v as $vv){
                $vv['stock'] = $a['stock'];
                $temp_two[] = $vv;
            }
        }
    }




    $all = array_merge($temp_one,$temp_two);

    $all = ObjectHelper::getInstance(FashionModel::class)->  assortFashionWithStock($all);




    ////对 two 进行格式化


    return response()->json(api_msg(0,'ok',$all));


}

    /**
     * @api {get} /api/stockInListInfo 入库单信息 (去除num字段)
     * @apiVersion 2.0.0
     * @apiName stockInListInfo
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_in_id 入库单id
     * @apiDescription 入库单详情api
     *
     * @apiSampleRequest  /api/stockInListInfo
     *
     * @apiSuccess (返回值) {string} stock_in_sn 入库单编码
     * @apiSuccess (返回值) {string} name 入库单名称
     * @apiSuccess (返回值) {string} type 入库类型
     * @apiSuccess (返回值) {string} num 入库数量
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} created_at 操作时间
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"stock_in_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","type":"\u9000\u8d27\u5165\u5e93","num":"50","operate":"\u9648\u7fd4\u5b87","created_at":null}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */

public function stockInListInfo(Request $request){
    $stock_in_id =  $request -> get('stock_in_id');
    $stock_in_model = StockInModel::find($stock_in_id);
    $stock_in_info = this($stock_in_model,['stock_in_sn','name','type','num','operate','created_at']);
    return response()->json(api_msg(0,'ok',$stock_in_info));
}
    /**
     * @api {get} /api/stockOutList 出库单列表
     * @apiVersion 1.0.0
     * @apiName stockOutList
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiDescription 出库单列表api
     *
     * @apiSampleRequest  /api/stockOutList
     *
     * @apiSuccess (返回值) {string} stock_out_sn 入库编码
     * @apiSuccess (返回值) {string} name 入库名称
     * @apiSuccess (返回值) {string} type 入库类型
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} created_at 操作日期
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"current_page":1,"data":[{"stock_out_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","type":"\u9000\u8d27\u5165\u5e93","num":"50","operate":"\u9648\u7fd4\u5b87","created_at":null}],"first_page_url":"http:\/\/myscan.dev.com\/api\/stockInList?page=1","from":1,"last_page":2,"last_page_url":"http:\/\/myscan.dev.com\/api\/stockInList?page=2","next_page_url":"http:\/\/myscan.dev.com\/api\/stockInList?page=2","path":"http:\/\/myscan.dev.com\/api\/stockInList","per_page":1,"prev_page_url":null,"to":1,"total":2}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockOutList(Request $request){
        $stock_model_s = StockOutModel::where(function (){
            //////预留搜索接口

        })->orderBy('created_at','DESC')->paginate(15);

        $stock_list = this($stock_model_s,['id','stock_out_sn','name','type','num','operate','created_at']);


        return response()->json(api_msg(0,'ok', paginate($stock_model_s,$stock_list)));

    }
    /**
     * @api {get} /api/stockOutListDetail 出库单详情(字段有所改变 12.10)
     * @apiVersion 2.0.0
     * @apiName stockOutListDetail
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_out_id 出库单id
     * @apiDescription 出库单详情api
     *
     * @apiSampleRequest   /api/stockOutListDetail
     *
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} school_name 学校名称
     * @apiSuccess (返回值) {string} stock 库位信息
     * @apiSuccess (返回值) {string} stock.stock_sn 库位编码
     * @apiSuccess (返回值) {string} stock.stock_name 库位名称
     * @apiSuccess (返回值) {string} stock.stock_floor 库位楼层
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"ok","data":[{"fashion_code":"07M10008A","fashion_name":null,"fashion_size":"180","fashion_num":7,"stock":{"stock_sn":"4H-SH-A01-Z01-C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4},"school":"\u4e0a\u6d77\u5e02\u5929\u5c71\u4e2d\u5b66"},{"fashion_code":"07M10008A","fashion_name":null,"fashion_size":"190","fashion_num":1,"stock":{"stock_sn":"4H-SH-A01-Z01-C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4},"school":"\u4e0a\u6d77\u5e02\u5929\u5c71\u4e2d\u5b66"},{"fashion_code":"T1807040A","fashion_name":null,"fashion_size":"180","fashion_num":5,"school":"\u60e0\u7075\u987f\u56fd\u9645\u5b66\u6821","stock":{"stock_sn":"4H-SH-A01-Z01-C01","stock_name":"4\u697c\u4e0a\u6d77\u4ed3","floor":4}}]}
     *
     * @apiErrorExample (json) 错误示例:
     * {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockOutListDetail(Request $request){
        $stock_out_id =  $request -> get('stock_out_id');

        $op_sn = StockOutModel::find($stock_out_id)->sn;
         $stock_scan_stock_model = StockScanStockModel::where('op_sn',$op_sn)->with('stockScanStockDetail.fashion')->with('stockScanBox.box.boxDetail.fashion')->with('stock')->get();

           ///仅仅库存的产品
        $one = this($stock_scan_stock_model,['stock_scan_stock_detail'=>['fashion_code','fashion_name','fashion_size','fashion_num','stock'=>['stock_sn','stock_name','floor'],'fashion'=>['school','chen_xiang_yu_'],'chen_xiang_yu_']]);
        $temp_one = [];
        foreach ($one as $v){
            foreach ($v as $vv){

                $temp_one[] = $vv;


            }
        }


////仅仅箱子里的产品
        $two = this($stock_scan_stock_model,['stock'=>['stock_sn','stock_name','floor'],'stock_scan_box'=>['box'=>['box_detail'=>['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'chen_xiang_yu_'],'chen_xiang_yu_']]]);
        $temp_two = [];
        foreach ($two as $a){

            foreach ($a['stock_scan_box'] as $v){
                foreach ($v as $vv){
                    $vv['stock'] = $a['stock'];
                    $temp_two[] = $vv;
                }
            }
        }
        $all = array_merge($temp_one,$temp_two);

      $all = ObjectHelper::getInstance(FashionModel::class)->  assortFashionWithStock($all);

        return response()->json(api_msg(0,'ok',$all));
    }

    /**
     * @api {get} /api/stockOutListInfo 出库单信息
     * @apiVersion 1.0.0
     * @apiName stockOutListInfo
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_out_id 收货地址信息
     * @apiDescription 出库单信息api
     *
     * @apiSampleRequest  /api/stockOutListInfo
     *
     * @apiSuccess (返回值) {string} stock_out_sn 出库单编码
     * @apiSuccess (返回值) {string} name 出库单名称
     * @apiSuccess (返回值) {string} type 出库单类型
     * @apiSuccess (返回值) {string} num 出库数量
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} created_at 操作时间
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"stock_out_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","type":"\u9000\u8d27\u5165\u5e93","num":"50","operate":"\u9648\u7fd4\u5b87","created_at":null}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockOutListInfo(Request $request){
        $stock_out_id =  $request -> get('stock_out_id');

        $stock_out_model = StockOutModel::find($stock_out_id);

        $stock_out_info = this($stock_out_model,['stock_out_sn','name','type','num','operate','created_at']);
        return response()->json(api_msg(0,'ok',$stock_out_info));
    }


    /**
     * @api {get} /api/stockCountList 盘点单管理
     * @apiVersion 1.0.0
     * @apiName stockCountList
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiDescription 盘点单管理api
     *
     * @apiSampleRequest  /api/stockCountList
     *
     * @apiSuccess (返回值) {string} id 盘点单id
     * @apiSuccess (返回值) {string} stock_count_sn 盘点单编号
     * @apiSuccess (返回值) {string} stock_name 盘点仓库
     * @apiSuccess (返回值) {string} title 标题
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} status 状态
     * @apiSuccess (返回值) {string} created_at 盘点日期
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/stockCountList?page=2","total":2,"data":[{"stock_count_sn":"123","stock_name":1,"title":"\u7b2c\u4e00\u6b21\u76d8\u70b9","operate":"\u9648\u7fd4\u5b87","status":"\u672a\u542f\u52a8","created_at":{"date":"2018-12-03 17:47:27.000000","timezone_type":3,"timezone":"PRC"}}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockCountList(Request $request){
        $stock_count_model = StockCountModel::where(function (){

        })->orderBy('created_at','DESC')->paginate(15);

        $stock_count_detail = this($stock_count_model,['id','stock_count_sn','stock_name','title','operate','status','created_at']);

        return response()->json(api_msg(0,'ok',paginate($stock_count_model,$stock_count_detail)));

    }

    /**
     * @api {get} /api/stockCountDetail 盘点的详情 (字段有所改变 12.10)
     * @apiVersion 2.0.0
     * @apiName stockCountDetail
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_count_id 盘点单的id
     * @apiDescription  盘点单列表点击进去查看盘点单的详情产品信息api
     *
     * @apiSampleRequest  /api/stockCountDetail
     *
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} school 提学校名称
     * @apiSuccess (返回值) {string} before_fashion_num 账面数量
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/stockCountDetail?page=2","total":2,"data":[{"fashion_code":"07M10008A","fashion_name":null,"fashion_size":"180","fashion_num":2,"before_fashion_num":0,"school":"\u4e0a\u6d77\u5e02\u5929\u5c71\u4e2d\u5b66"},{"fashion_code":"07M10008A","fashion_name":null,"fashion_size":"180","fashion_num":3,"before_fashion_num":5,"school":"\u4e0a\u6d77\u5e02\u5929\u5c71\u4e2d\u5b66"}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockCountDetail(Request $request){
        $stock_count_id = $request -> get('stock_count_id');
        $op_sn = StockCountModel::find($stock_count_id)->sn;

        /////查询盘点的只在库位里的产品
        $stock_scan_stock_model = StockScanStockDetailModel::where('op_sn',$op_sn)->orderBy('created_at','DESC')->paginate(15);

        ////直接装库位的盘点详情
        $stock_scan_stock_detail = this($stock_scan_stock_model,['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'before_fashion_num']);
        //////装在箱子的
         //  $stock_id =  array_unique(pick_up(this($stock_scan_stock_model,['stock_id']),'stock_id'));///本次盘点的库位id
        $box_id = StockScanBoxModel::where('op_sn',$op_sn)->get()->pluck('box_id')->toArray();

          //$stock_box_model =   StockBoxModel::whereIn('stock_id',$stock_id)->with('box')->get();
           //$box_id = array_unique(pick_up(this($stock_box_model,['box'=>['id','chen_xiang_yu_']]),'id'));
          $box_detail_model = BoxDetailModel::whereIn('box_id',$box_id)->orderBy('created_at','DESC')->paginate(15);
         $stock_scan_box_detail = this($box_detail_model,['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'before_fashion_num']);


        $stock_scan_stock_detail =  ObjectHelper::getInstance(FashionModel::class)->assortFashion($stock_scan_stock_detail);
        $stock_scan_box_detail = ObjectHelper::getInstance(FashionModel::class)->assortFashion($stock_scan_box_detail);
        $all = array_merge($stock_scan_stock_detail,$stock_scan_box_detail);

         $all = ObjectHelper::getInstance(FashionModel::class)->assortFashion($all);

        return response()->json(api_msg(0,'ok',paginate_all($stock_scan_stock_model,$box_detail_model,$all)));


    }

    /**
     * @api {get} /api/stockCountInfo 盘点单信息
     * @apiVersion 1.0.0
     * @apiName stockCountInfo
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_count_id 盘点单的id
     * @apiDescription 一个盘点单的信息api
     *
     * @apiSampleRequest  /api/stockCountInfo
     *
     * @apiSuccess (返回值) {string} stock_count_sn 盘点编码
     * @apiSuccess (返回值) {string} stock_name 盘点名称
     * @apiSuccess (返回值) {string} title 盘点标题
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} status 状态
     * @apiSuccess (返回值) {string} created_at 创建时间
     * @apiSuccess (返回值) {string} note 备注
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"stock_count_sn":"pd_812055670","stock_name":"\u4e0a\u6d77\u4ed3","title":"2018\u5e744\u697c\u4e0a\u6d77\u4ed3\u5e74\u7ec8\u76d8\u70b9","operate":"\u9648\u7fd4\u5b87","status":null,"created_at":{"date":"2018-11-20 00:00:00.000000","timezone_type":3,"timezone":"PRC"},"note":"\u7b2c\u4e00\u6b21\u6d4b\u8bd5\u5907\u6ce8"}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockCountInfo(Request $request){
        $stock_count_id = $request -> get('stock_count_id');
        $stock_count_model = StockCountModel::find($stock_count_id);
        $stock_count_info = this($stock_count_model,['stock_count_sn','stock_name','title','operate','status','created_at','note']);
        return response()->json(api_msg(0,'ok',$stock_count_info));
    }

    /**
     * @api {get} /api/stockCountListDetail 盘点清单(字段有所改变 12.10)
     * @apiVersion 1.0.0
     * @apiName stockCountListDetail
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_count_id 盘点的id
     * @apiDescription 盘点清单api
     *
     * @apiSampleRequest  /api/stockCountListDetail
     *
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} school 学校名称
     * @apiSuccess (返回值) {string} stock.stock_sn 库位编码
     * @apiSuccess (返回值) {string} stock.stock_name 库位名称
     * @apiSuccess (返回值) {string} stock.floor 库位所在楼层
     * @apiSuccess (返回值) {string} stock.type 库位类型 托盘或者货架
     * @apiSuccess (返回值) {string} stock_box.box_sn 所属箱子的编号
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"ok","data":{"fashion_code":"07M10008A","fashion_name":"\u7eaf\u8272\u57fa\u672c\u6b3e\u5f00\u895f\u957fT","fashion_size":"180","fashion_num":25,"school_name":null,"stock":{"stock_sn":"A01-Z01-C01","stock_name":"A\u533a01","floor":4,"type":"\u8d27\u67b6"},"stock_box":{"box_sn":"B_c07b"}}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */

    public function stockCountListDetail(Request $request){
        $stock_count_id = $request -> get('stock_count_id');
        /////查询盘点的只在库位里的产品
        $op_sn = StockCountModel::find($stock_count_id)->sn;

        $stock_scan_stock_model = StockScanStockDetailModel::where('op_sn',$op_sn)->get();
        ////直接装库位的盘点详情
        $stock_scan_stock_detail = this($stock_scan_stock_model,['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'stock'=>['stock_sn','stock_name','floor']]);

        //////装在箱子的
       // $stock_id =  array_unique(pick_up(this($stock_scan_stock_model,['stock_id']),'stock_id'));///本次盘点的库位id
      //  $stock_box_model =   StockBoxModel::whereIn('stock_id',$stock_id)->with('box')->get();
        //$box_id = array_unique(pick_up(this($stock_box_model,['box'=>['id','chen_xiang_yu_']]),'id'));
        $box_id = StockScanBoxModel::where('op_sn',$op_sn)->get()->pluck('box_id')->toArray();
        $box_detail_model = BoxDetailModel::whereIn('box_id',$box_id)->orderBy('created_at','DESC')->paginate(15);
        $stock_scan_box_detail = this($box_detail_model,['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'before_fashion_num']);

        $stock_scan_stock_detail =  ObjectHelper::getInstance(FashionModel::class)->assortFashionWithStock($stock_scan_stock_detail);
        $stock_scan_box_detail = ObjectHelper::getInstance(FashionModel::class)->assortFashion($stock_scan_box_detail);
        $all = array_merge($stock_scan_stock_detail,$stock_scan_box_detail);



        return response()->json(api_msg(0,'ok',$all));

    }

    /**
     * @api {post} /api/addStockCount 新增一个盘点单
     * @apiVersion 1.0.0
     * @apiName addStockCount
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} title 收货地址信息
     * @apiParam {int} operate 收货地址信息
     * @apiParam {int} time 收货地址信息
     * @apiParam {int} note 收货地址信息
     * @apiParam {int} stock_name 收货地址信息
     * @apiDescription 新增一个盘点单api
     *
     * @apiSampleRequest  /api/addStockCount
     *
     * @apiSuccess (返回值) {int} id 盘点单的id
     * @apiSuccess (返回值) {int} stock_count_sn 盘点单的编号
     * @apiSuccess (返回值) {int} stock_name 名称
     * @apiSuccess (返回值) {int} title 标题
     * @apiSuccess (返回值) {int} operate 操作人
     * @apiSuccess (返回值) {int} status 状态
     * @apiSuccess (返回值) {int} note 备注
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":{"id":5}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function addStockCount(Request $request){
        $title = $request -> post('title');
        $operate = $request -> post('operate');
        $created_at = $request -> post('time');
        $note = $request -> post('note');
        $stock_name = $request -> post('stock_name');
//        $title = '2018年4楼上海仓年终盘点';
//        $operate = '陈翔宇';
//        $created_at = '2018-11-20';
//        $note = '第一次测试备注';
//        $stock_name = '上海仓';

        $stock_count_model = new StockCountModel();
        $res =  $stock_count_model->add(compact('title','operate','created_at','note','stock_name'));
        if($res){
            return response()->json(api_msg(0,'ok',for_this($res,['id','stock_count_sn','title','operate','status','note'])));
        }
        return response()->json(api_msg(1,'false'));
    }


    /**
     * @api {get} /api/startStockCount 启动一个盘点单
     * @apiVersion 1.0.0
     * @apiName startStockCount
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_count_id 盘带单id
     * @apiDescription 开启一个盘点单api
     *
     * @apiSampleRequest  /api/startStockCount
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} api_msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"api_msg":"ok","data":null}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function startStockCount(Request $request){
        $stock_count_id = $request -> get('stock_count_id');
        $stock_count_model =  StockCountModel::find($stock_count_id);
        $res = $stock_count_model -> start($stock_count_model);
        if($res){
            return response()->json(api_msg(0,'ok'));
        }
        return response()->json(api_msg(1,'false'));
    }

    /**
     * @api {get} /api/verifyStockIn 入库审核详情页面
     * @apiVersion 1.0.0
     * @apiName verifyStockIn
     * @apiGroup group_so
     * @apiPermission 登录用户
     *
     * @apiParam {int} stock_in_id 入库单id
     * @apiDescription 入库审核页面api
     *
     * @apiSampleRequest  /api/verifyStockIn
     *
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} o_num 工厂交货数量
     * @apiSuccess (返回值) {string} fashion_info.school 学校
     * @apiSuccess (返回值) {string} fashion_info.fileurl 图片
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"ok","data":[{"fashion_name":"PE\u8fd0\u52a8\u88e4","fashion_code":"T1807040A","fashion_size":"180","fashion_num":5,"fashion_info":{"school":"\u60e0\u7075\u987f\u56fd\u9645\u5b66\u6821","fileurl":null},"o_num":55}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */

    public function verifyStockIn(Request $request){
        ////针检数据详情
         $stock_in_id =  $request -> get('stock_in_id');
         $op_sn = StockInModel::find($stock_in_id)->sn;

         $stock_box_model = StockScanBoxModel::where('op_sn',$op_sn)->get();

         $stock_box_info  = this($stock_box_model,['id','stockScanBoxDetail'=>['fashion_name','fashion_code','fashion_size','fashion_num','fashion_info'=>['school','fileurl']]]);

         ///整理下 产品
        $temp = [];
       foreach ($stock_box_info as $v){
           foreach ($v['stockScanBoxDetail'] as $vv){
               $vv['o_num'] = 0;
               $temp[] = $vv;
           }
       }

        return response()->json(api_msg(0,'ok', asssort($temp)));
    }

public function searchCon($request){
    $key_word = $request ->get('key_word');
    $type = $request ->get('type');
    return compact('key_word','type');
}

    /**
     * @api {get} /api/alertStockCountFashionNum 修改盘点单产品数量
     * @apiVersion 2.0.0
     * @apiName alertStockCountFashionNum
     * @apiGroup group_so
     * @apiPermission 登录用户
     *
     * @apiParam {int} stock_count_sn 盘点编码
     * @apiParam {int} stock_id 库位id
     * @apiParam {int} fashion_code 产品编码
     * @apiParam {int} fashion_size 产品尺码
     * @apiParam {int} fashion_num 产品数量
     * @apiDescription 修改盘点单产品数量api
     *
     * @apiSampleRequest  /api/alertStockCountFashionNum
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":1,"msg":"本库位编码未盘点本库位","data":[]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"state":"1","msg":"失败","data":[]}
     */

public function alertStockCountFashionNum(Request $request){
    /////修改盘点单里某个库位的产品数量
    $stock_count_sn = $request -> get('stock_count_sn');
    $stock_sn = $request ->get('stock_sn');
    $fashion_code = $request ->get('fashion_code');
    $fashion_size = $request ->get('fashion_size');
    $fashion_num = $request ->get('fashion_num');



    /////////////////
//    $stock_count_sn = 'pd_812151376';
//
//    $stock_sn = '4_S_A01Z01C01';
//    $fashion_code = '07M10008A';
//    $fashion_size = 180;
//    $fashion_num = 5;
    /// //////////////
    $stock_model = StockModel::where('stock_sn',$stock_sn)->first();

    if(!$stock_model){
        return response()->json(api_msg(1,'不存在本库位'));
    }
    //////首先修改一下盘点记录
        $stock_scan_stock_model = StockScanStockModel::where('op_sn',$stock_count_sn)->first();
        if(!$stock_scan_stock_model){
            /////本库位不存在盘点 不允许修改
            return response()->json(api_msg(1,'本库位编码未盘点本库位'));
        }
       $stock_scan_stock_detail_model =  StockScanStockDetailModel::where('op_sn',$stock_count_sn)->where('stock_id',$stock_model->id)->where('fashion_code',$fashion_code)->where('fashion_size',$fashion_size)->first();


    if(!$stock_scan_stock_detail_model){
        /////本库位不存在盘点 不允许修改
        return response()->json(api_msg(1,'本库位没有盘点记录 无法修改'));
    }
    $stock_scan_stock_detail_model = $stock_scan_stock_detail_model -> update(['fashion_num'=>$fashion_num]);
    if(!$stock_scan_stock_detail_model){
        return response()->json(api_msg(1,'更新失败'));
    }

    ////////进行同步库位更改
    $stock_detail =  StockDetailModel::where('stock_id',$stock_model->id)->where('fashion_code',$fashion_code)->where('fashion_size',$fashion_size)->first();
    if(!$stock_detail){
        return response()->json(api_msg(1,'库位不存在本产品'));
    }
    $res = $stock_detail -> update(['fashion_num'=>$fashion_num]);

    if(!$res){
        return response()->json(api_msg(1,'更新产品库存失败'));
    }

    return response()->json(api_msg(0,'成功'));

}

public function moveStockRecord(Request $request){
      ////移位记录
   $stock_move =  StockMoveModel::where(function ($query){

    })->withxy(['stockMoveDetail'=>['id','stock_move_sn','or_stock_sn','ta_stock_sn','data']])->get();

   $stock_move = XY::with($stock_move)->wish('stockMoveDetail')->add('value')->except('data')->get();

    return response()->json(api_msg(0,'成功',$stock_move));


}

    /**
     * @api {get} /api/export 按照学校或者产品名称导出产品库位库存信息
     * @apiVersion 2.0.0
     * @apiName export
     * @apiGroup group_so
     * @apiPermission 登录用户
     *
     * @apiParam {int} fashion_code 产品编码
     * @apiParam {int}  school_name 学校名称
     * @apiDescription 按照学校或者产品名称导出产品库位库存信息api
     *
     * @apiSampleRequest  /api/export
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":1,"msg":"所选地址不存在","data":[]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */

public function export(){
////导出产品信息以及器所在的库位等信息

    $box_id = ObjectHelper::getInstance(BoxModel::class)->belongToStock();

    $need = ['boxDetail'=>[function($query)use($box_id){
        $query -> whereIn('box_id',$box_id)->select('box_id','fashion_code','fashion_size','fashion_num');
    },'box'=>['id','box_sn','stockBox'=>['stock_id','box_sn','stock'=>['stock_sn','id']]]],'stockDetail'=>['stock_id','fashion_code','fashion_size','fashion_num','stock'=>['stock_sn','id']]];


    $fashion_model = FashionModel::where(function ($query){
  $query -> where('code','07M10008A');
 })->withxy($need)->get(['code','real_name']);

$temp = [];

 foreach ($fashion_model as $v){
     $temp[] =  $v -> hasXHStockNew();
 }

 $new_temp = [];
 foreach ($temp as $v){
     $num = 0;
     foreach ($v as $vv){
         $new_temp[] = $this -> sortForExport($vv);
         $num = $num + $vv['fashion_num'];
     }
      $a = [];
      $a['fashion_code'] =  '合计';
      $a['fashion_name'] = $num;
      $a['fashion_size'] = '';
      $a['fashion_num'] = '';
      $a['stock_sn'] = '';
      $a['total_num'] = '';

      array_push($new_temp,$a);

 }


   $export = [];
    $export[] =$new_temp;

    $sheet_title = ['导出数据'];
    $head_arr=['产品编码','产品名称','尺码','数量','库位'];

   Excel::export($export,$head_arr,$title='导出表格',$sheet_title);
    //////////////执行导出
   // ExcelHelper::exports($export,$head_arr,$title='导出表格',$sheet_title);

}

/**
 * 格式化位导出的格式
 * @param
 * @return mixed
 */
  public function sortForExport($data){
     $temp = [];
     $temp['fashion_code'] = $data['fashion_code'];
     $temp['fashion_name'] = $data['fashion_name'];
     $temp['fashion_size'] = $data['fashion_size'];
     $temp['fashion_num'] = $data['fashion_num'];
     $temp['stock_sn'] = $data['stock_sn'];
     return $temp;
  }




public function stockImport(Request $request){

      if($request->isMethod('post')){
       $res = CommonHelper::upload($request,'file');//上传文件  获取上传地址
       if($res['code']==0){
           $file_path = $res['data']['url'];
           $ext = $res['data']['ext'];
           $data =  ExcelHelper::import($file_path,$ext);///导入的表格数据
           foreach ($data as $key => $v){
               $this -> oneSheet($v,$key);
           }
           return response()->json(['state'=>0,'msg'=>'ok']);

       }else{
           dd($res['msg']);
       }
   }

    return view('admin.tool.import_stock');
}

public function oneSheet($data,$title){
    $stock_service = ObjectHelper::getInstance(StockService::class);
    $deal_data = $stock_service -> dealStockImport($data);
    ////开始生成一个盘点单
    $stock_in =new StockInModel();
     parse_str(urldecode($_POST['datas']),$post);

    $title = $title.current_time().'导入入库数据';
    $operate = $post['operator'];
    $created_at = current_time();
    $note = $post['note'];
    $stock_name = $post['type'];

    $stock_in_model = $stock_in -> add($title,$operate,$created_at,$stock_name);

   $return =  $stock_service -> stockIns($stock_in_model,$deal_data);
  if($return['code'] == 1){
      dd($return['msg']);
  }
return;
}
public function oneSheetOut($data,$title){
    $stock_service = ObjectHelper::getInstance(StockService::class);
    $deal_data = $stock_service -> dealStockImport($data);
    ////开始生成一个盘点单
    $stock_out =new StockOutModel();
     parse_str(urldecode($_POST['datas']),$post);

    $title = $title.current_time().'导入数据';
    $operate = $post['operator'];
    $created_at = current_time();
    $note = $post['note'];
    $stock_name = $post['type'];

    $stock_out_model = $stock_out -> add($title,$operate,$stock_name);

   $return =  $stock_service -> stockOuts($deal_data,$stock_out_model );
  if($return['code'] == 1){
      dd($return['msg']);
  }
return;
}


    public function stockImportOut(Request $request){

        if($request->isMethod('post')){
            $res = CommonHelper::upload($request,'file');//上传文件  获取上传地址
            if($res['code']==0){
                $file_path = $res['data']['url'];
                $ext = $res['data']['ext'];
                $data =  ExcelHelper::import($file_path,$ext);///导入的表格数据
                foreach ($data as $key => $v){
                    $this -> oneSheetOut($v,$key);
                }
                return response()->json(['state'=>0,'msg'=>'ok']);

            }else{
                dd($res['msg']);
            }
        }

        return view('admin.tool.import_stock');
    }

}