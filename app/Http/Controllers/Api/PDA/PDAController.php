<?php

namespace App\Http\Controllers\Api\PDA;

use App\Helper\ObjectHelper;
use App\Models\Admin\BoxModel;
use App\Models\Admin\FashionModel;
use App\Models\Admin\FashionStockWxModel;
use App\Models\Admin\StockBoxModel;
use App\Models\Admin\StockCountDetailModel;
use App\Models\Admin\StockCountModel;
use App\Models\Admin\StockInModel;
use App\Models\Admin\StockModel;
use App\Models\Admin\StockMoveModel;
use App\Models\Admin\StockOutModel;
use App\Models\Admin\StockScanBoxModel;
use App\Models\Admin\StockScanStockModel;
use App\Models\Admin\TestModel;
use App\Service\Admin\StockService;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Api\Controller;
use XiangYu2038\Excel\Excel;
use XiangYu2038\Wish\XY;

class PDAController extends Controller
{
    /**
     * @apiDefine group_pda pda模块
     */

    public function __construct(Request $request)
    {
       //调用中间件
       // $a = Cache::store('file')->get('bb');
        //$a['access_token'] = 3;
        //$request->headers->set('Authorization','Bearer '.$a['access_token']);

        //$this->middleware('api-auth:api');
    }


    /**
     * @api {get} /api/pda/stockCountList 盘点单列表
     * @apiVersion 1.0.0
     * @apiName stockCountList
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {int} page
     * @apiDescription 盘点单管理api
     *
     * @apiSampleRequest  /api/pda/stockCountList
     *
     * @apiSuccess (返回值) {string} msg 1 为失败  0为成功
     * @apiSuccess (返回值) {string} msg 说明信息
     * @apiSuccess (返回值) {string} current_page 当前页
     * @apiSuccess (返回值) {string} prev_page_url 上一页地址
     * @apiSuccess (返回值) {string} next_page_url 下一页地址
     * @apiSuccess (返回值) {string} total 总页数
     * @apiSuccess (返回值) {string} id 盘点单id
     * @apiSuccess (返回值) {string} stock_count_sn 盘点单编号
     * @apiSuccess (返回值) {string} stock_name 盘点仓库
     * @apiSuccess (返回值) {string} title 标题
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} status 状态
     * @apiSuccess (返回值) {string} created_at 盘点日期
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/pda\/choiceStockCount?page=2","total":5,"data":[{"id":1,"stock_count_sn":"123","stock_name":"1","title":"\u7b2c\u4e00\u6b21\u76d8\u70b9","operate":"\u9648\u7fd4\u5b87","status":"\u672a\u542f\u52a8","created_at":{"date":"2018-12-03 17:47:27.000000","timezone_type":3,"timezone":"PRC"}}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","api_msg":"失败","data":[]}
     */
    public function stockCountList(Request $request){
        $stock_count_model = StockCountModel::where(function (){

        })->where('status','开启')->orderBy('created_at','DESC')->paginate(15);

        $stock_count_detail = this($stock_count_model,['id','stock_count_sn','stock_name','title','operate','status','created_at']);
        $paginate = paginate($stock_count_model,$stock_count_detail);

        return response()->json(msg(0,'ok',$paginate));
    }



    /**
     * @api {post} /api/pda/submitScan 盘点数据提交
     * @apiVersion 2.0.0
     * @apiName submitScan
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {string} type 类型 1代表盘点 2代表出库 3代表检针 4代表装箱
     * @apiParam {string} stock_count_id 若为类型1  必须提供盘点单的id  若类型为3和4  必须提供入库单id  stock_in_id
     * @apiParam {string} container 编码  编码有两种
     * @apiParam {string} element  扫描的编码
     * @apiDescription 提交扫描数据api
     *
     * @apiSampleRequest  /api/pda/submitScan
     *
     * @apiSuccess (返回值) {string} container 库位或者箱子编码
     * @apiSuccess (返回值) {string} element  扫描的编码
     * @apiSuccess (返回值) {string} element.fashion_code  扫描的编码
     * @apiSuccess (返回值) {string} element.fashion_size  扫描的尺码
     * @apiSuccess (返回值) {string} element.fashion_num  扫描的数量
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":[{"container":"daddafffdsfsd","element":[{"fashion_code":"07M10008A","fashion_size":"180","fashion_num":2}]},{"container":"daddafffdsfsd","element":[{"fashion_code":"07M10008A","fashion_size":"180","fashion_num":1}]}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function submitScan(Request $request){

        /////////////////////////////////
         $type =  $request -> post('type');
         $data =  $request -> post('data');
         $data = $this -> heihei($data);

        //////////////////////////////////

//     $data = [
//            [
//                'container'=>'4_S_A01Z01C01',
//                'element'=>['07M10008A180','07M10008A180','07M10008A190','07M10008A190']
//            ],
//            [
//                'container'=>'CKXH1812130001',
//                'element'=>['07M10008A180','07M10008A180','07M10008A180']
//            ]
//        ];

//        $data = [
//            [
//                'container'=>'CKXH201896c728',
//                'element'=>['M1710009A','M1710009A']
//            ]
//        ];

        $stock_service = ObjectHelper::getInstance(StockService::class);
        if($type == 1){
            /////这是盘点动作
            $stock_count_id = $request -> post('id');
          //  $stock_count_id =1;
            $stock_count_model = StockCountModel::find($stock_count_id);

            return response()->json($stock_service -> stockCount($stock_count_model,$data));
         }elseif($type == 2){
            /////这是出库动作  首先记录出库记录
            return response()->json($stock_service -> stockOuts($data));

        }elseif($type==3){
             ////这是检针动作
            $stock_in_id = $request -> post('id');///入库单的id

          //  $stock_in_id = 4;
            $stock_in_model = StockInModel::find($stock_in_id);
            //$stock_service =  ObjectHelper::getInstance(StockService::class);
            return response()->json( $stock_service -> checkNeedle($stock_in_model,$data));

        }elseif($type == 4){
            ////这是入库动作
             $stock_in_id = $request -> post('id');///入库单的id
            //$stock_in_id =1;
            $stock_in_model = StockInModel::find($stock_in_id);

            return response()->json($stock_service -> stockIns($stock_in_model,$data));

        }
    }


    public function heihei($data){
        $data = json_decode($data,true);
        foreach ($data as &$v){
            $v['element'] = json_decode($v['element'],true);
        }
        unset($v);
        return $data;
    }


    /**
     * @api {post} /api/pda/addStockIn 新增一个入库单
     * @apiVersion 1.0.0
     * @apiName addStockIn
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {string} name 入库名称
     * @apiParam {string} j_h_time 交货时间
     * @apiParam {string} type 入库类型
     * @apiParam {string} operate 操作者
     * @apiDescription 新增一个入库单api
     *
     * @apiSampleRequest  /api/pda/addStockIn
     *
     * @apiSuccess (返回值) {int} id 入库单id
     * @apiSuccess (返回值) {string} name 入库名称
     * @apiSuccess (返回值) {string} stock_in_sn 入库单编码
     * @apiSuccess (返回值) {string} j_h_time 交货时间
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} type 入库类型
     * @apiSuccess (返回值) {string} created_at 日期
     * @apiSuccess (返回值) {string} created_at.date 提示信息创建日期
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":1,"msg":"所选地址不存在","data":[]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function addStockIn(){
        ////新增一个入库单
       $name = $_POST['name'];
        $operate = $_POST['operate'];
        $j_h_time = $_POST['j_h_time'];
        $type = $_POST['type'];

//        $name = '测试名称';
//        $operate = '陈翔宇';
//        $j_h_time = current_time();
//        $type = '退货入库';

        $stock_in_res = ObjectHelper::getInstance(StockInModel::class)->add($name,$operate,$j_h_time,$type);
        if($stock_in_res){
            return response()->json(msg(0,'ok',this($stock_in_res,['id','name','stock_in_sn','j_h_time','operate','type','created_at'])));
        }
        return response()->json(msg(1,'新增入库单失败'));


    }

    /**
     * @api {get} /api/pda/stockInList 入库单列表
     * @apiVersion 2.0.0
     * @apiName stockInList
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {int} page 页数
     * @apiParam {int} type 入库类型
     * @apiDescription 入库单列表api
     *
     * @apiSampleRequest  /api/pda/stockInList
     *
     * @apiSuccess (返回值) {int} id 入库单id
     * @apiSuccess (返回值) {string} stock_in_sn 入库单编码
     * @apiSuccess (返回值) {string} name 入库单名称
     * @apiSuccess (返回值) {string} type 入库单类型
     * @apiSuccess (返回值) {string} operate 操作者
     * @apiSuccess (返回值) {string} j_h_time 交货时间
     * @apiSuccess (返回值) {string} z_j_is_end 检针是否结束
     * @apiSuccess (返回值) {string} b_x_is_end 搬箱是否结束
     * @apiSuccess (返回值) {string} j_z_box_num 检针箱数
     * @apiSuccess (返回值) {string} r_k_box_num 入库箱数
     * @apiSuccess (返回值) {string} wait_ban_box_num 待搬箱箱数
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/pda\/stockInList?page=2","total":11,"data":[{"id":1,"stock_in_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","type":"\u9000\u8d27\u5165\u5e93","operate":"\u9648\u7fd4\u5b87","j_h_time":null,"z_j_is_end":"\u5b8c\u6210","b_x_is_end":"\u5b8c\u6210","j_z_box_num":8,"r_k_box_num":0,"wait_ban_box_num":8}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public  function stockInList(Request $request){
         /////入库单列表

        $type = $request -> get('type');
        $heihei_type = $request -> get('heihei_type');
////////
//        $type = '大货入库';
//        $heihei_type = '检针';
////////

        //TestModel::create(['json'=>json_encode($_GET)]);
        $stock_in_model = StockInModel::with('stockScanBox','stockScanBoxDetail')->where(function($query)use($type,$heihei_type){
            if($type){
                if($heihei_type=='检针'){
                    /////检针需要未完成列表
                    $query->where('type',trim(urldecode($type)))->where('z_j_is_end','未完成');
                }elseif($heihei_type=='搬箱'){
                    $query->where('type',trim(urldecode($type)))->where('b_x_is_end','未完成');
                }else{
                    $query->where('type',trim(urldecode($type)));
                }

            }

        })->orderBy('created_at','DESC')->paginate(15);
        $stock_in_list = this($stock_in_model,['id','stock_in_sn','name','type','operate','j_h_time','z_j_is_end','b_x_is_end','j_z_box_num','r_k_box_num','wait_ban_box_num']);


        return response()->json(msg(0,'ok',paginate($stock_in_model,$stock_in_list)));
    }

    /**
     * @api {post} /api/pda/addStockOut 新增一个出库单
     * @apiVersion 2.0.0
     * @apiName addStockOut
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {string} name 出库名称
     * @apiParam {string} type 出库类型
     * @apiParam {string} operate 操作者
     * @apiDescription 新增一个出库单api
     *
     * @apiSampleRequest  /api/pda/addStockOut
     *
     * @apiSuccess (返回值) {int} id 出库单id
     * @apiSuccess (返回值) {string} name 出库名称
     * @apiSuccess (返回值) {string} stock_out_sn 入库单编码
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} type 出库类型
     * @apiSuccess (返回值) {string} created_at 日期
     * @apiSuccess (返回值) {string} created_at.date 提示信息创建日期
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"stock_out_sn":"CKOUT_901034773","name":"123","type":"789","operate":"456","created_at":"2019-01-03 14:09:46","id":5}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function addStockOut(Request $request){
        ////新增一个入库单

        $name = $request -> post('name');
        $operate = $request -> post('operate');
        $type = $request -> post('type');

//        $name = '123';
//       $operate = '456';
//
//        $type = '789';

        $stock_in_res = ObjectHelper::getInstance(StockOutModel::class)->add($name,$operate,$type);
        $stock_in_res =  XY::with($stock_in_res)->except('updated_at')->get();

        if($stock_in_res){
            return response()->json(msg(0,'ok',$stock_in_res));
        }
        return response()->json(msg(1,'新增入库单失败'));


    }

    /**
     * @api {get} /api/pda/stockOutList 出库单列表
     * @apiVersion 2.0.0
     * @apiName stockOutList
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {int} page 页数
     * @apiParam {int} type 出库类型
     * @apiDescription 出库单列表api
     *
     * @apiSampleRequest  /api/pda/stockOutList
     *
     * @apiSuccess (返回值) {int} id 出库单id
     * @apiSuccess (返回值) {string} name 出库名称
     * @apiSuccess (返回值) {string} stock_out_sn 入库单编码
     * @apiSuccess (返回值) {string} operate 操作人
     * @apiSuccess (返回值) {string} type 出库类型
     * @apiSuccess (返回值) {string} created_at 日期
     * @apiSuccess (返回值) {string} created_at.date 提示信息创建日期
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":"http:\/\/myscan.dev.com\/api\/pda\/stockInList?page=2","total":11,"data":[{"id":1,"stock_in_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","type":"\u9000\u8d27\u5165\u5e93","operate":"\u9648\u7fd4\u5b87","j_h_time":null,"z_j_is_end":"\u5b8c\u6210","b_x_is_end":"\u5b8c\u6210","j_z_box_num":8,"r_k_box_num":0,"wait_ban_box_num":8}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public  function stockOutList(Request $request){
        /////出库单列表

        $type = $request -> get('type');
        $stock_out_model =  StockOutModel::where(function (){

        })->paginate(15);

        $stock_out_res = XY::with($stock_out_model)->except('updated_at')->get();

        return response()->json(msg(0,'ok',$stock_out_res));
    }

    /**
     * @api {get} /api/pda/setEndCheckNeedle 设置针检完成
     * @apiVersion 1.0.0
     * @apiName setEndCheckNeedle
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_in_id 入库单id
     * @apiDescription 设置针检完成api
     *
     * @apiSampleRequest  /api/pda/setEndCheckNeedle
     *
     * @apiSuccess (返回值) {int} id 入库单id
     * @apiSuccess (返回值) {string} stock_in_sn 编号
     * @apiSuccess (返回值) {string} name 入库名称
     * @apiSuccess (返回值) {string} box_num 箱子数量
     * @apiSuccess (返回值) {string} fashion_num 商品数量
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"id":1,"stock_in_sn":"123","name":"\u7b2c\u4e00\u6b21\u5165\u5e93","box_num":2,"fashion_num":4}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function setEndCheckNeedle(Request $request){
          $stock_in_id = $request -> get('stock_in_id');

          $stock_in_model = StockInModel::where('id',$stock_in_id)->with('stockScanBox','stockScanBoxDetail')->first();
          $stock_in_num = $stock_in_model ->needleInfo();
           $stock_in_info = this($stock_in_model,['id','stock_in_sn','name']);
            $stock_in_info['box_num'] =  $stock_in_num['box_num'];
            $stock_in_info['fashion_num'] =  $stock_in_num['fashion_num'];

          $stock_in_res = StockInModel::find($stock_in_id)->update(['z_j_is_end'=>'完成']);
          if($stock_in_res){
              return response()->json(msg(0,'ok',$stock_in_info));
          }
        return response()->json(msg(1,'false'));
    }


    /**
     * @api {post} /api/pda/submitAudit 提交审核
     * @apiVersion 1.0.0
     * @apiName submitAudit
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {int} stock_in_id 入库单id
     * @apiDescription 提交审核api
     *
     * @apiSampleRequest  /api/pda/submitAudit
     *
     * @apiSuccess (返回值) {int} id 状态码
     * @apiSuccess (返回值) {string} stock_in_sn 入库编码
     * @apiSuccess (返回值) {string} stock_sn_s 入库箱子
     * @apiSuccess (返回值) {string} has_box_num 入库箱子数量
     * @apiSuccess (返回值) {string} detail 详情
     * @apiSuccess (返回值) {string} detail.stock_sn 库位编码
     * @apiSuccess (返回值) {string} detail.stock_box 箱子编码
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"id":1,"stock_in_sn":"in_812157766","stock_sn_s":["4_S_A01Z01C01","4_S_A01Z01C01","4_S_A01Z01C01","4_S_A01Z01C01","4_S_A01Z01C01"],"has_box_num":9,"detail":[{"stock_sn":"4_S_A01Z01C01","box_sn":["CKXH1802251111","CKXH1802251111"]},{"stock_sn":"4_S_A01Z01C01","box_sn":["CKXH1802251111","CKXH1802251111"]},{"stock_sn":"4_S_A01Z01C01","box_sn":["CKXH1802251111","CKXH1802251111"]},{"stock_sn":"4_S_A01Z01C01","box_sn":["CKXH1802251111","CKXH1802251111"]},{"stock_sn":"4_S_A01Z01C01","box_sn":["CKXH1802251111","CKXH1802251111"]}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function submitAudit(Request $request){
        ///提交审核
        ///
        $stock_in_id = $request -> post('stock_in_id');
        $stock_in_model = StockInModel::where('id',$stock_in_id)->with('stockScanStock.stock.stockBox','stockScanBox')->first();
        $stock_in_detail = this($stock_in_model,['id','stock_in_sn','stock_sn_s','has_box_num','detail']);

        $temp = [];
        $a = [];
        foreach ($stock_in_detail['detail'] as $v){
             foreach ($v['box_sn'] as $vv){
                 $temp['stock_sn'] = $v['stock_sn'];
                 $temp['box_sn'] = $vv;
                 $temp['num'] = 1;
                 $a[] = $temp;
             }
         }
        $stock_in_detail['detail'] = $a;
        return response()->json(msg(0,'ok',$stock_in_detail));
    }

    /**
     * @api {get} /api/pda/listNotBindingBox 列出入库单所有已检针未绑定的箱子号
     * @apiVersion 1.0.0
     * @apiName listNotBindingBox
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {int} stock_in_sn 入库单id
     * @apiDescription 列出入库单所有已检针未绑定的箱子号api
     *
     * @apiSampleRequest  /api/pda/listNotBindingBox
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"\u6210\u529f","data":{"1":"CKXH1812130002"}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */

    public function listNotBindingBox(Request $request){
        //////列出本次没有绑定的箱子
        $stock_in_sn = $request -> get('stock_in_sn');

        $stock_scan_box_model = StockScanBoxModel::where('op_sn',$stock_in_sn)->withxy(['box'=>['id','box_sn']])->get(['op_sn','box_id','stock_scan_stock_id']);
        $temp_j_z = [];//检针的模型
        $temp_b_d = [];///搬箱的模型
        foreach ($stock_scan_box_model as $v){
            if($v->stock_scan_stock_id == 0){
                $temp_j_z[] = $v;
            }else{
                $temp_b_d[] = $v;
            }
        }
        $stock_in_model = ObjectHelper::getInstance(StockInModel::class);

        $w_z = $stock_in_model -> wZSn($temp_j_z,$temp_b_d)->toArray();
        $w_z = array_values($w_z);

        return response()->json(msg(0,'成功',$w_z));


    }


    /**
     * @api {post} /api/pda/addMoveList 新增一个移位单
     * @apiVersion 1.0.0
     * @apiName addMoveList
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {string} name 移库单名称
     * @apiParam {string} operate  操作者
     * @apiDescription 新增一个移位单api
     *
     * @apiSampleRequest  /api/pda/addMoveList
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     * @apiSuccess (返回值) {string} stock_move_sn 移库单编码
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"stock_move_sn":"CKXH_812289859"}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function addMoveList(Request $request){
        ///新增一个移位单
        $name = $request -> post('name');
        $operate = $request -> post('operate');

        ///
//        $name = '第'.rand(0,10000).'次移位';
//        $operate = '陈翔宇'.rand(0,10000).'号';
        ////
        $stock_move = ObjectHelper::getInstance(StockMoveModel::class);
        $stock_move_build = $stock_move->build($name,$operate);
        $res = $stock_move -> create($stock_move_build);
        if(!$res){
            return response()->json(msg(1,'保存移库单失败'));
        }

        $res =  XY::with($res)->only('stock_move_sn')->get();
        return response()->json(msg(0,'ok',$res));

    }

    /**
     * @api {get} /api/pda/queryStockHas 库位中所有箱子和产品
     * @apiVersion 2.0.0
     * @apiName queryStockHas
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {int} stock_sn  库位编码
     * @apiDescription 库位中所有箱子和产品api
     *
     * @apiSampleRequest  /api/pda/queryStockHas
     *
     * @apiSuccess (返回值) {int}  box_sn 箱子编码 或者产品编码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":[{"box_sn":"CKXH1812130001","fashion_num":6},{"fashion_num":5,"box_sn":"07M10008A180"},{"fashion_num":5,"box_sn":"07M10008A180"}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function queryStockHas(Request $request){
        ////查询一个库位里面有啥 有箱子 有产品
        $stock_sn = $request -> get('stock_sn');
        /////
        //$stock_sn = '4_S_A01Z01C01';
        ///////

        $stock_model = StockModel::where('stock_sn',$stock_sn)->withxy(['stockDetails'=>['stock_id','fashion_code','fashion_num','fashion_size'],'stockBox'=>['stock_id','box_sn','box'=>['id','box_sn','boxDetail'=>['box_id','fashion_code','fashion_size','fashion_num']]]])->first(['id','stock_sn']);


        $has_box =  $stock_model -> hasBoxWithFashionNum()->toArray();///拥有的箱子

        $has_fashion = $stock_model -> hasFashion()->toArray();//拥有的产品

        $all =  array_merge($has_box,$has_fashion);

        return response()->json(msg(0,'ok',$all));
    }


    /**
     * @api {get} /api/pda/applyMoveStock 移位操作
     * @apiVersion 2.0.0
     * @apiName applyMoveStock
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {int} or_stock_sn 源库位编码
     * @apiParam {int} ta_stock_sn 目标库位编码
     * @apiParam {int} data [{"sn":"CKXH201896c728","num":1},{"sn":"T1805136A130","num":1}]
     * @apiParam {string} stock_move_sn 移位单号
     * @apiDescription 移位操作api
     *
     * @apiSampleRequest  /api/pda/applyMoveStock
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":[]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function applyMoveStock(Request $request){
        ////进行移位操作
        $or_stock_sn = $request -> post('or_stock_sn');
        $ta_stock_sn = $request -> post('ta_stock_sn');
        $stock_move_sn = $request -> post('stock_move_sn');
        $data = $request -> post('data');
        $data =json_decode($data,true);
         //$data = $this -> heihei($data);

//        $or_stock_sn = '4_S_A01Z01C01';///源库位编码
//        $ta_stock_sn = '4_S_A01Z01C01';///目标库位编码
//        $stock_move_sn = 'CKXH_901047670';///移位单
//        $data =  [
//             ['sn'=>'T1806090A120','num'=>1],
//        ];

        $stock_service = ObjectHelper::getInstance(StockService::class);
        $res =  $stock_service -> applyMoveStock($or_stock_sn,$ta_stock_sn,$data,$stock_move_sn);

        return response()->json($res);

    }


    /**
     * @api {post} /api/pda/stockOut 出库动作
     * @apiVersion 2.0.0
     * @apiName stockOut
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {int} stock_out_id 出库单id
     * @apiParam {int} data 出库单数据
     * @apiDescription 出库动作api
     *
     * @apiSampleRequest  /api/pda/stockOut
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":[{"container":"4_S_A01Z01C01","element":{"fashion_info":[{"fashion_code":"T1806090A","fashion_size":"120","fashion_num":1}],"box_info":[]}}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function stockOut(Request $request){
        ////出库动作
        /////这是出库动作  首先记录出库记录

        $stock_out_id = $request -> post('stock_out_id');
        $data =  $request->post('data');
        $data = $this -> heihei($data);


//        $data = [
//            [
//                'container'=>'4_S_A01Z01C01',
//                'element'=>['T1806090A120']
//            ]
//        ];
//        $stock_out_id = 6;
        $stock_out_model = StockOutModel::find($stock_out_id);
         if(!$stock_out_model){
             return response()->json(msg(1,'不存在的出库单'));
         }

        $stock_service  = ObjectHelper::getInstance(StockService::class);

        return response()->json($stock_service -> stockOuts($data,$stock_out_model));
    }


    /**
     * @api {get} /api/pda/queryFashion 查询一个产品的库位库存信息
     * @apiVersion 2.0.0
     * @apiName queryFashion
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {int} fashion_code 产品编码
     * @apiDescription 查询一个产品的库位库存信息api
     *
     * @apiSampleRequest  /api/pda/queryFashion
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} stock_sn 库存信息
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} school 学校
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":[{"fashion_code":"W1702042A","fashion_size":"130","fashion_num":20,"stock_sn":"4_S_A01Z02C01","fashion_name":"\u82f1\u4f26\u5b66\u9662\u98ce\u77ed\u8896\u886c\u886b","school":"\u4e0a\u6d77\u5e02\u6c11\u529e\u4e2d\u82af\u5b66\u6821"}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function queryFashion(Request $request){

         $box_id = ObjectHelper::getInstance(BoxModel::class)->belongToStock();
         $fashion_code = $request -> get('fashion_code');
         $fashion_code = ObjectHelper::getInstance(FashionModel::class)->fashionCode($fashion_code);

        $need = ['boxDetail'=>[function($query)use($box_id){
            $query -> whereIn('box_id',$box_id)->select('box_id','fashion_code','fashion_size','fashion_num');
        },'box'=>['id','box_sn','stockBox'=>['stock_id','box_sn','stock'=>['stock_sn','id']]]],'stockDetail'=>['stock_id','fashion_code','fashion_size','fashion_num','stock'=>['stock_sn','id']]];


        $fashion_model = FashionModel::where(function ($query)use($fashion_code){
            $query -> where('code',$fashion_code['fashion_code']);
        })->withxy($need)->get(['code','real_name','school']);

        $temp = [];

        foreach ($fashion_model as $v){
            $temp[] =  $v -> hasXHStockHasBox();
        }

        $new_temp = [];
        foreach ($temp as $v){
            foreach ($v as $vv){
                if($fashion_code['fashion_size']){
                    if($vv['fashion_size'] == $fashion_code['fashion_size']){
                      $new_temp[] = $vv;
                    }
                    continue;
                }
                $new_temp[] = $vv;
            }
        }

        return response()->json(msg(0,'ok',$new_temp));
}


    /**
     * @api {get} /api/pda/queryStockFashionList 查询一个库位中的产品列表(包括箱子)
     * @apiVersion 1.0.0
     * @apiName queryStockFashionList
     * @apiGroup group_pda
     * @apiPermission 登录用户
     *
     * @apiParam {int} stock_sn 库位编码
     * @apiDescription 查询一个库位中的产品列表api
     *
     * @apiSampleRequest  /api/pda/queryStockFashionList
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     * @apiSuccess (返回值) {string} fashion_name 产品名称
     * @apiSuccess (返回值) {string} fashion_code 产品编码
     * @apiSuccess (返回值) {string} fashion_size 产品尺码
     * @apiSuccess (返回值) {string} fashion_num 产品数量
     * @apiSuccess (返回值) {string} school 学校
     * @apiSuccess (返回值) {string} box_sn 箱子编码
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":[{"fashion_name":"\u8212\u9002\u9633\u5149\u897f\u88e4","fashion_code":"M1807148A","fashion_size":"120","fashion_num":22,"school":"","box_sn":"CKXH1901107376"},{"fashion_name":"\u82f1\u4f26\u5b66\u9662\u98ce\u77ed\u8896\u886c\u886b","fashion_code":"W1702042A","fashion_size":"140","fashion_num":20,"school":"\u4e0a\u6d77\u5e02\u6c11\u529e\u4e2d\u82af\u5b66\u6821","box_sn":"CKXH1901107376"},{"fashion_name":"\u8212\u9002\u9633\u5149\u897f\u88e4","fashion_code":"M1807148A","fashion_size":"120","fashion_num":5,"school":"","box_sn":"CKXH1901105081"},{"fashion_name":"\u8131\u5378\u5f0f\u4e09\u5408\u68c9\u98ce\u8863","fashion_code":"T1806117A","fashion_size":"130","fashion_num":15,"school":"\u676d\u5dde\u91c7\u5b9e\u6559\u80b2\u96c6\u56e2","box_sn":"CKXH1901105081"},{"fashion_name":"\u6e05\u723d\u8212\u9002\u77ed\u8896T\u6064","fashion_code":"T1801234A","fashion_size":"140","fashion_num":20,"school":"\u4e0a\u6d77\u5fb7\u82f1\u4e50\u5b66\u9662","box_sn":"CKXH1901102480"},{"fashion_name":"\u82f1\u4f26\u5b66\u9662\u98ce\u77ed\u8896\u886c\u886b","fashion_code":"W1702042A","fashion_size":"130","fashion_num":9,"school_name":"\u4e0a\u6d77\u5e02\u6c11\u529e\u4e2d\u82af\u5b66\u6821","box_sn":""}]}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
public function queryStockFashionList(Request $request){
      $stock_sn = $request -> get('stock_sn');

      $stock_model = StockModel::where('stock_sn',$stock_sn)->with('stockBox')->with('stockDetails')->first();
      if(!$stock_model){
          return  response()->json(msg(1,'未查询到信息'));
      }
    $stock_detail = $stock_model -> stockDetail();

      $fashion_model = ObjectHelper::getInstance(FashionModel::class)->addFashionName($stock_detail);

     return  response()->json(msg(0,'ok',$fashion_model));

}

public function version(){
    return  response()->json(msg(0,'ok',['version'=>'5','url'=>'https://fir.im/7rmp']));
}

public function stockOutDetail(Request $request){
    $stock_out_sn =  $request -> get('stock_out_sn');
   // $stock_out_sn = 'CKOUT_901247965';

    $stock_scan_stock_model = StockScanStockModel::where('op_sn',$stock_out_sn)->with('stockScanStockDetail.fashion')->with('stockScanBox.box.boxDetail.fashion')->with('stock')->get();

    ///仅仅库存的产品
    $one = this($stock_scan_stock_model,['stock_scan_stock_detail'=>['fashion_code','fashion_name','fashion_size','fashion_num','stock'=>['stock_sn','chen_xiang_yu_'],'fashion'=>['school','chen_xiang_yu_'],'chen_xiang_yu_']]);
    $temp_one = [];
    foreach ($one as $v){
        foreach ($v as $vv){
            $vv['box_sn'] = '';
            $temp_one[] = $vv;


        }
    }


////仅仅箱子里的产品
    $two = this($stock_scan_stock_model,['stock'=>['stock_sn','stock_name','floor'],'stock_scan_box'=>['box'=>['box_sn','box_detail'=>['fashion_code','fashion_name','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_'],'chen_xiang_yu_'],'chen_xiang_yu_']]]);

    $temp_two = [];
    foreach ($two as $a){
        foreach ($a['stock_scan_box'] as $key=> $v){

            foreach ($v as $vv){
                 if(is_string($vv)){
                     $box_sn = $vv;
                     continue;
                 }


                     $vv['stock_sn'] = $a['stock']['stock_sn'];
                     $vv['box_sn'] = $box_sn;
                     $temp_two[] = $vv;


            }
        }
    }

    $all = array_merge($temp_one,$temp_two);

    $all = ObjectHelper::getInstance(FashionModel::class)->  assortFashionWithStockss($all);

     $all = array_values($all);
    $all = ObjectHelper::getInstance(FashionModel::class) -> addFashionName($all);

    return response()->json(msg(0,'ok',$all));

}


public function  aa($data){
   $num = 0;
    foreach ($data as $v){
        $num = $num + $v['fashion_num'];
    }
    return $num;
}

public function editStockNum(Request $request){
    ///修改库存数量
     $stock_sn = $request -> post('stock_sn');
     $data = $request -> post('data');
    // Cache::store('file')->put('dd', $_POST, 1000);
    //    $data = Cache::store('file')->get('dd');
    //    $data = $data['data'];

    $data = json_decode($data,true);
//     $data = [
//       [
//           'fashion_code'=>'W1702042A',
//           'fashion_size'=>'130',
//           'fashion_num'=>'11',
//           ],
//       [
//       'fashion_code'=>'T1805136A',
//         'fashion_size'=>'120',
//         'fashion_num'=>'11',
//     ]
//     ];

    //////
    $deal_function = function ($data){
        $temp = [];
        foreach ($data as $v){

              for($i=0;$i<$v['fashion_num'];$i++){
                  $temp[] = $v['fashion_code'].$v['fashion_size'];
              }

          }
          return $temp;
    };

    $data = [
            [
                'container' => $stock_sn,
                'element'=> $deal_function($data)
            ]
        ];


    $stock_service = ObjectHelper::getInstance(StockService::class);
    $stock_count_model = new StockCountModel();
    $count_info = ['title'=>'修改库存'.current_time(),'operate'=>'修改者','note'=>'修改库存','stock_name'=>$stock_sn];
    $stock_count_model = $stock_count_model -> add($count_info);


    return response()->json($stock_service -> stockCount($stock_count_model,$data));

}

}


