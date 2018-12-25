<?php

namespace App\Http\Controllers\Api\PDA;

use App\Helper\ObjectHelper;
use App\Helper\XY;
use App\Models\Admin\StockBoxModel;
use App\Models\Admin\StockCountDetailModel;
use App\Models\Admin\StockCountModel;
use App\Models\Admin\StockInModel;
use App\Models\Admin\StockModel;
use App\Models\Admin\StockOutModel;
use App\Models\Admin\TestModel;
use App\Service\Admin\StockService;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Api\Controller;
class PDAController extends Controller
{
    /**
     * @apiDefine group_pda pda模块
     */

    public function test(){
       $a = ['stockDetails'=>['id','stock_id','fashion_code','fashion'=>[function($query){
           $query ->select('id','code');
       }]],'stockBox'=>['id','stock_id','box_sn']];

        $a = StockModel::withxy(['stockDetails'=>['id','stock_id','fashion_code']])->get(['id','stock_sn']);

        dd(__LINE__);
        $a = XY::with($a)->wish('stock_details')->add('fashion_code')->get();


        //XY::with($a)->wish('stockDetails')->add('heihei')->get();
dd($a->toArray());

      dd(__LINE__);
        dd($a->toArray());
       dd(__LINE__);


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
//         $type =  $_POST['type'];
//         $data =  $_POST['data'];
//         $data = $this -> heihei($data);
        //$type = 3;
        //////////////////////////////////
        $type = 1;
//     $data = [
//            [
//                'container'=>'4_S_A01Z01C01',
//                'element'=>['07M10008A180','07M10008A180','07M10008A190','CKXH201896c728']
//            ],
//            [
//                'container'=>'CKXH1812130001',
//                'element'=>['07M10008A180','07M10008A180','07M10008A180']
//            ]
//        ];
//
        $data = [
            [
                'container'=>'4_S_A01Z01C01',
                'element'=>['07M10008A180']
            ]
        ];
        $stock_service = ObjectHelper::getInstance(StockService::class);
        if($type == 1){
            /////这是盘点动作
            //$stock_count_id = $_POST['id'];
            $stock_count_id =1;
            $stock_count_model = StockCountModel::find($stock_count_id);

            return response()->json($stock_service -> stockCount($stock_count_model,$data));
         }elseif($type == 2){
            /////这是出库动作  首先记录出库记录
            return response()->json($stock_service -> stockOuts($data));

        }elseif($type==3){
             ////这是检针动作
            //$stock_in_id = $_POST['id'];///入库单的id
            $stock_in_id = 2;
            $stock_in_model = StockInModel::find($stock_in_id);
            $stock_service =  ObjectHelper::getInstance(StockService::class);
            return response()->json( $stock_service -> checkNeedle($stock_in_model,$data));

        }elseif($type == 4){
            ////这是入库动作
             //$stock_in_id = $_POST['id'];///入库单的id
            $stock_in_id =1;
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



}


