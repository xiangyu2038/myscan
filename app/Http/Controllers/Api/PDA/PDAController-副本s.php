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

    public function testPanDian(Request $request){
        //  dd( app('events'));
       set_time_limit(0);
        $data = [
            [
                'container'=>'4H-SH-A01-Z01-C01',
                'element'=>['07M10008A180']
            ]
        ];


//
//        $a = StockScanStockModel::where('op_sn','CKPD_901105477')->with('stock')->with('stockScanStockDetail')->get();
//
//        $element = function ($data){
//
//
//            $temp = [];
//            foreach ($data as $v){
//                for ($i=0;$i<$v['fashion_num'];$i++){
//                    $temp[]= $v['fashion_code'].$v['fashion_size'];
//                }
//            }
//            return $temp;
//        };
//        $temp = [];
//
//        foreach ($a as $v){
//            $temp[$v['id']]['container'] = $v['stock']['stock_sn'];
//            $temp[$v['id']]['element'] = $element($v['stockScanStockDetail']);
//
//        }
//
//
//        $stock_scan_stock =  Cache::store('file')->put('bb', $temp, 1000);
        $stock_scan_stock =  Cache::store('file')->get('bb');


        $i=0;
        foreach ($stock_scan_stock as $v){
            $h = [];
            $h[] = $v;;
            $this -> haha($h);
            echo $v['container'].'</Br>';
            echo $i++;
        }

dd('结束');

    }
    public function test(Request $request){
        //  dd( app('events'));
       set_time_limit(0);
        $data = [
            [
                'container'=>'4H-SH-A01-Z01-C01',
                'element'=>['07M10008A180']
            ]
        ];

//                $a = StockScanStockModel::whereIn('op_sn',['CKOUT_901244374','CKOUT_901248370','CKOUT_901242178','CKOUT_901244473','CKOUT_901248865','CKOUT_901243375','CKOUT_901243771','CKOUT_901244770','CKOUT_901245769','CKOUT_901243474','CKOUT_901248766','CKOUT_901247965','CKOUT_901248766','CKOUT_901245076','CKOUT_902181967','CKOUT_902186665','CKOUT_902189167','CKOUT_902189860','CKOUT_902184370','CKOUT_902181274','CKOUT_902182768','CKOUT_902183470','CKOUT_902186170'])->with('stock')->with('stockScanStockDetail')->get();
//
//
//
//           $temp = $this -> getTemp($a);
////
////
//        $stock_scan_stock =  Cache::store('file')->put('fefew', $temp, 1000);
        $stock_scan_stock =  Cache::store('file')->get('fefew');


        $i=0;
        foreach ($stock_scan_stock as $v){
            $h = [];
            $h[] = $v;
            $this -> Out($h);
            echo $v['container'].'</Br>';
            echo $i++;
        }

dd('结束');

    }

    public function test2(){



//        $data = TestModel::all()->pluck('fashion_code')->toArray();
//        foreach ($data as &$v){
//            $v=trim($v);
//        }
//        unset($v);
//
//
//$data = array_unique($data,SORT_LOCALE_STRING );

$gong = $this -> song();


$shang = $this -> shang();
$shang = array_merge($gong,$shang);

$wu = $this -> wu();



//$b = FashionStockWxModel::all()->pluck('fashion_code')->toArray();
//$b = array_unique($b);
//$data = array_diff($shang,$b);


$a= FashionStockWxModel::withxy(['fashion'=>['school','code','sex','type_name','real_name','style_name','material_make','color']])->whereIn('fashion_code',$wu)->get(['fashion_code','fashion_name','fashion_size','fashion_num'])->toArray();


        $fashion_model = ObjectHelper::getInstance(FashionModel::class);
       //$c =  $fashion_model -> assortFashion($b);

       $function = function($data){
           $temp = [];
           $temp['school_name'] = $data['fashion']['school'];
           $temp['real_name'] = $data['fashion']['real_name'];
           $temp['fashion_code'] = $data['fashion']['code'];
           $temp['style_name'] = $data['fashion']['style_name'];
           $temp['material_make'] = $data['fashion']['material_make'];
           $temp['color'] = $data['fashion']['color'];
           $temp['fashion_size'] = $data['fashion_size'];
           $temp['fashion_num'] = $data['fashion_num'];
           return $temp;
       };


foreach ($a as &$v){
     $v = $function($v);
}
unset($v);



        $c =  $fashion_model -> assortFashion($a);

        $yy = [];
        foreach ($c as $v){
            $yy[$v['school_name']][] = $v;
        }
          krsort($yy);


        $sort_by_fashion_size = function ($data){
            $yy = [];
            foreach ($data as $v){
                $yy[$v['fashion_size']][] = $v;
            }
            ksort($yy);
            return $yy;
        };

        $sort_by_fashion_name =function($data)use($sort_by_fashion_size){
            $yy = [];
            foreach ($data as $v){
                $yy[$v['real_name']][] = $v;
            }
            krsort($yy);

            foreach ($yy as &$v){
                $v =  $sort_by_fashion_size($v);
            }
            unset($v);
            return $yy;
        };
        ////对产品名称进行排序
foreach ($yy as &$v){
             $v =  $sort_by_fashion_name($v);
}
unset($v);




        $uu = [];
        foreach ($yy as $v){
            foreach ($v as $vv){
              foreach ($vv as $vvv){
                  foreach ($vvv as $vvvv){
                      $uu [] = $vvvv;
                  }
              }
            }
        }
        $c = $uu;

        $temp = [];
        foreach ($c as $v){
            $temp[$v['fashion_code']][] = $v;
        }




        $aa = function($data){
            $temp = [];
            $temp['school_name'] = '总计';
            $temp['fashion_code'] = $data;
            $temp['sex'] = '';
            $temp['type_name'] = '';
            $temp['real_name'] = '';
            $temp['fashion_size'] = '';
            $temp['fashion_num'] = '';
            return $temp;
        };



        $new_temp = [];
        foreach ($temp as $v){

            $num = $this ->aa($v);

            foreach ($v as $vv){
                $new_temp[] = $vv;
            }

            $new_temp[] = $aa($num);
        }


$aaa[] = $new_temp;

        $sheet_title = ['导出数据'];
        $head_arr=['学校名称','产品名称','产品编码','款式号','面料名称','颜色','尺码','库存数量'];

        Excel::export($aaa,$head_arr,$title='导出表格',$sheet_title);

dd(__LINE__);
      $a = ['stockDetails'=>['id','stock_id','fashion_code','fashion'=>[function($query){
           $query ->select('id','code');
       }]],'stockBox'=>['id','stock_id','box_sn']];

        $a = StockModel::withxy(['stockDetails'=>['id','stock_id','fashion_code']])->get(['id','stock_sn']);


        $a = XY::with($a)->wish('stock_details')->add('fashion_code')->get();





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
    return  response()->json(msg(0,'ok',['version'=>'3','url'=>'https://fir.im/vu8t']));
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
    public function haha($data){
        try{
            $stock_count_id = 4;
            $stock_count_model = StockCountModel::find($stock_count_id);
            $stock_service = ObjectHelper::getInstance(StockService::class);
            $stock_service->addRecord($stock_count_model,$data,'盘点');

            \DB::commit();

        }catch (\Exception $e){

            \DB::rollBack();dd($e->getMessage());
            //return response()->json(msg(1,$e->getMessage()));
        }
    }

    public function song(){
        $fashion_code = 'T1607017A
T1601059A
T1601058A
T1501023A
T1601057A
T1607038A
T1507010A
T1501020A
T1601056A
T1601020A
W1508006A
W1608034A
W1608073A
W1608048A
11M60014A 
T1607029A 
T1607015A 
T1607030A
11M60020A
T1607029A 
T1601050A
T1607033A
T1601014A
T1601026A
T1601012A
T1601028A
T1507004A
T1607032A

T1607063A
T1607080A
T1607016A
T1507006A
T1607034A
T1507020A
T1607040A 
T1507012A
T1607039A
T1607057A 
W1508005A
W1608033A
T1601054A
T1601061A
T1501026A
T1601130A
T1601129A
T1601060A
T1501025A
T1501055A
T1701022A
W1608061A
T1701025A
T1701017A
T1607029A 
T1607017A 
T1605019A 
T1507009A
T1607037A
T1505012A
T1605040A
T1605006A
T1607036A
T1605023A

T1605041A
T1605026A
T1607013A
T1505006A
T1605035A
T1505010A
T1605038A
T1607014A 
T1605039A
T1505004A
T1607005A
T1605027A
T1607014A 
T1605032A
T1505003A
T1605007A
M1607010A 
M1607009A 
T1607040A 
T1607041A
T1607036A 
T1607036A
07M60020C
T1607043A
T1607042A
T1607042A
M1602049A
W1508009A
W1608037A
W1608029A
W1508007A
W1608035A
W1308001A
11W20069A
M1302009A
M1602061A
W1502009A
W1602051A
W1608022A
W1608024A
12M10141A
M1602063A
M1602061A
M1502006A
M1602055A

M1602063A
M1502008A 
M1602057A 
W1602058A
12W10143A 
W1602064A 
W1502013A
W1602060A
T1603002A
W1302001A
W1602062A
M1607010A 
W1502005A
W1602054A

M1607009A 
T1607013A
T1603002A
11M70006A
T1603111A
T1603049A
T1603032A
M1502014A
T1503004A
T1603031A
T1603008A
T1603045A

T1503023A
T1603050A
T1603032A
T1503007A
T1603034A
M1603113A
T1603048A
T1503016A
T1603043A

T1607034A
M1604003A 
T1603009A 
T1603023A
M1604019A
W1603041A 
W1503014A 
T1603046A 
T1503019A
M1604018A
W1604020A
T1603049A
T1503022A 
T1503020A
W1603110A
M1603109A
T1503021A
T1703040A
12M70036A
W1503001A
T1603014A
T1603022A

T1603002A
W1604010A 

W1604021A
T1603009A 
T1603032A
T1607017A
W1608035A
T1603031A
T1603020A
W1603038A 

M1602055A
T1601057A
T1601020A
T1701022A

T1607073A
T1607074A

T1606006A

T1506002A
T1606005A 
T1706005A 
T1606007A
T1506019A 
T1606010A
T1706021A

T1612016A

T1506005A 
T1606008A 

T1606011A
';
        $gong = explode(PHP_EOL,$fashion_code);
        $gong_s = [];
        foreach ($gong as &$v){

            if(strlen($v)>2){
                $gong_s[] = trim($v);
            }
        }
        return $gong_s;
    }

    public function shang(){
        $shang = '
W1604021A
M1802092A
W1802113A
M1804067A
W1804068A
W1808013A
W1808015A
T1805072A
M1807012A
T1801179A 
T1807019A 
M1807017A 
T1806050A 
M1807085A 

T1606108A
T1601162A
W1702056A
T1601163A
M1702055A
M1707057A
T1803001A
T1702071A
T1706084A
W1612030A
T1612029A
T1603131A 
W1708012A 
T1705020A
T1605235A
T1705074A
T1605183A
T1707075A

T1801096A
T1801095A
M1804099A
W1804100A
T1607012A
W1808054A
T1805090A
T1812009A
T1801187A
T1801188A
T1806062A
T1803061A
T1805083A
T1806061A
T1801010A 
T1801008A 
T1801006A 
T1803003A 
T1801007A 
T1801009A 
10M30076A
10M80006A
10M30074A
10M20055A
10M30075A
10M80007A
T1607198A
10M10139A
10W10135A

W1802006A
M1802005A
M1807097A
W1808011A
M1804075A
M1804077A
W1804078A
W1804076A
T1803057A
T1807137A
T1805078A 
T1805079A 
T1801149A
T1801150A
T1801205A
T1803066A
T1801206A
T1806067A
T1806068A
T1810106A

M1601177A
T1603136A
T1605190A
W1401020A 
12W10092A
M1401021A
T1503058A
12M30057A 
T1607172A
T1606154A
11M60069A
T1806078A 
W1708017A
T1701161A
W1707091A
T1705070A 
T1705154A 
M1804105A 
W1804106A
W1704073A 
M1704072A
M1707093A
T1803056A
W1708020A 
T1710040A 
T1810110A 
M1704046A
W1704047A
T1710028A
T1710027A
T1710033A
T1710034A

T1701028A
T1701029A
T1605093A
T1703007A
T1607130A

T1601208A
W1608076A
W1408004A
T1601209A
T1605204A
T1407040A 
T1607117A 
T1605081A 
T1405031A 
11M60099A 
T1607159A
T1603108A

T1705106A 
T1605120A 
13M30004A 
T1405011A
T1801065A
T1801066A
T1701189A
T1701190A
T1805046A 
T1505039A 
T1406025A 
13M40023A 
T1706040A 
T1506082A 
T1806035A 

10M30076A 
T1701161A
W1708017A

W1606073A
M1606072A
M1607184A
W1406044A
W1407060A
W1607185A

M1607002A
W1608001A
T1705142A
T1607013A 
T1505043A 
T1707097A 
T1505046A 
T1707098A 
T1705114A 
T1505045A 
T1507039A
T1507040A
W1508026A
T1705143A
T1805126A
M1605132A
W1605133A 
W1504018A 
T1503034A 
M1507041A 
T1706060A 
W1504025A
T1503036A 
M1507042A 
M1504023A 
W1504017A 
W1508025A 
W1504022A 
W1608032A 
T1807001A 
M1504016A 
M1502033A 
M1504015A 
W1602002A 
M1504024A 
W1504026A 
M1602001A 
T1801001A 
M1504020A 
W1504021A 
T1503033A
M1704054A
W1704055A
T1503035A
T1501126A
T1701171A
T1505044A
W1508027A
T1705068A
T1601005A
T1601006A
T1512005A
W1502032A
T1503032A
T1601283A
T1512004A
T1607143A
T1506022A
T1506023A
M1607028A 
W1608089A 
T1706060A 

W1804055A
M1604003A
T1706084A
T1702071A

T1707081A 
T1701133A 
11W50047A
11W10004A
T1607139A
11M30001A
M1707053A
T1607113A
T1607114A
M1607112A
T1705091A
T1705098A
W1604062A
09M70006H
T1605108A
T1707054A
W1704039A
T1405007A
T1607126A
T1407027A
11M30001A
T1412001A
T1407011A
11M60033A 
T1407026A 
11M10252A 
T1701132A 
T1607147A 
M1702057A 
W1608073A 
13M50015A 
T1707085A 
W1702058A 
11W10253A 
11W50047A
11M50048A
W1602125A
T1412001A
T1405001A
T1405006A
13M50015A 
M1704038A
11M60001A
T1605092A
T1407013A
13W50016A
T1403008A
M1604061A
T1605107A
11M60133A 
11M60071A
T1607127A
T1605127A

T1705098A
T1705091A
T1705092A 
T1707081A 
T1607139A 
T1605108A 
T1707085A 
T1707084A 
M1604003A 
W1608073A 
M1602055A 
W1602058A 
11W20069A 
W1508007A 
T1601197A 
T1701132A 
T1607137A 
T1405001A 
T1401031A 
11M60001A 
11M10014A 
M1607160A 
T1405006A 
T1407027A 
11W10253A 
T1407011A 
11M40023A 
T1612020A 
T1412005A 
T1612007A 

W1706038A
M1706037A

T1601140A
T1601139A
T1606101A
T1605140A

T1801218A
T1701176A
T1501182A
T1703051A
T1603174A
T1601314A
T1601323A
T1803104A
T1605215A
T1805124A
T1705145A
T1605251A

W1608077A
W1601213A
11W10130A
M1601212A
W1605192A
M1607119A 
M1605191A 
11M60099A 
T1607173A 
M1606098A 
W1606099A

T1505032A
T1706032A
T1507035A
T1501104A 
T1706031A 
T1606143A
T1607129A
T1806097A 
T1606028A 
T1606029A
T1606030A
T1705075A
T1805112A
T1607125A 
T1605089A 
T1807136A 
T1707076A 
T1707078A 
T1807147A
M1807125A 
W1808068A 
T1601220A 
W1803068A 
T1701154A 
T1608088A 
T1607012A
W1808022A
T1707067A
T1707066A
T1807124A
T1801208A
W1608078A
W1708014A
T1507047A
T1607121A

M1705059A
W1705060A
T1607146A
W1607175A
W1701158A
W1701159A
M1801058A
M1801059A
M1701157A
W1801063A
W1801061A

T1606026A
T1601217A
T1601219A
T1605084A
T1605083A 
T1601218A 
T1601216A 

T1705041A 
T1705042A 
T1701123A 
T1701111A 
T1806098A 
T1806099A 
T1706074A
T1706075A

T1601194A
T1601195A
T1603097A
T1604040A

M1607099A
T1607098A
T1407030A
T1607061A
M1404005A
W1604039A
M1604038A
W1608070A
T1701048A
W1602103A
M1602102A
W1602105A
M1602104A
M1601142A
W1605069A
M1605068A
T1607097A
T1605067A
T1607096A
T1703026A
W1704025A
M1704024A
T1712001A

T1705126A
T1607194A
T1707096A
T1701130A
T1706087A 

09M40004F
11M30090A
T1607150A 
11M60112A 
T1501056A
T1501057A
12W10126A
11M60070A
12M10125A

T1802135A
M1802151A
W1802143A
M1802142A 
W1802115A 
M1802114A 
W1804143A 
M1804142A
T1806111A
W1806109A 
M1806108A 
T1801184A 
T1803109A 
T1803091A 
W1804115A
M1804114A
M1807091A
M1805123A
M1807148A
W1808051A
T1807092A
W1805122A
T1805119A
W1808075A 
W1808053A 
M1810114A 
W1810112A 

T1607193A
T1706041A
T1705088A
T1605119A
T1805099A
W1702077A
13M40027A
W1504033A
W1604048A
14W50004A
M1604047A
14M50003A 
W1504034A 
M1702076A 
T1606118A 
T1506009A 
13M10008A 
T1605239A 
M1601110A 
W1601111A 
T1603164A 
W1608084A
11W20001A 
W1608058A 
13W20001A 
T1607192A 
11M60118A 

T1705152A 
T1807002A 
T1806112A 
T181005A 
T1705151A 
T1801219A 
T1805132A 
T1807154A 
T1701195A 
W1808001A 

T1706053A
T1701192A
T1705115A
W1704045A
M1704044A

T1801183A
W1802119A
M1802120A
T1805093A
T1805094A
W1804102A
M1804101A
T1803085A 
T1806084A 
M1802133A 
W1802134A 

T1701049A
T1705065A
M1704020A
W1704021A
T1812008A
T1712004A

T1706061A
T1607171A 
13M60011A 
T1706062A 
T1706061A 
T1606046A 
13M40031A 
T1606047A 
13M40032A 
T1506008A
T1406003A

T1707051A
W1601244A 
M1801002A
T1607132A
T1607132A
T1605095A
T1807120A 
M1601241A
W1608038A
T1607131A
W1801003A
T1503055A
T1603149A
T1606069A

T1805102A
T1806051A
W1806052A
T1705087A
T1707080A
13M70017A
12W10134A
M1606048A
13M40006A
12M10133A
W1606049A
M1701053A
T1507037A
T1803055A
T1505042A 
M1801100A 
W1801101A 
M1603113A 
14W10034A
14M10033A

W1601155A
M1601152A
M1601153A
W1601154A
11M60125A
11M30031A
11M30030A 

T1805082A
T1801053A 
M1804082A 
W1607006A 
T1803060A 
W1804081A 
T1806059A 

T1701170A
T1705123A
T1707092A
M1702069A
M1702049A
T1701127A
M1702067A
W1702050A
W1702068A
T1701129A
T1701128A
M1707011A
W1708003A
W1702070A
M1702047A
W1702048A
M1707011A
W1708019A
T1701125A
W1702066A
M1702065A
T1712008A
T1706081A
T1806071A
T1806072A 
T1807099A 
T1707099A 
T1807132A 
W1604077A 
M1709004A 
W1810013A 
T1703048A 
W1704059A 
M1704058A 
W1710019A 
M1710017A 
M1710018A 
T1810001A 
T1810126A 
W1710025A 
M1710024A 
M1710022A 

T1606101A
T1605140A
T1601139A
T1601140A 

T1701124A
T1607060A
T1601073A
T1501039A
T1501014A
T1606034A
T1406002A
W1401054A

M1606139A
W1606140A
W1606138A
M1606137A
M1506067A
T1605088A
T1605035A
T1505050A
T1605087A
T1601233A
T1601232A
T1505070A
T1501154A 

T1603114A
T1605117A
T1601230A
T1606144A

T1706088A
T1505004A
T1606117A
T1505059A
T1506064A
T1705147A
T1701144A

M1504007A
T1512002A 
T1612028A 
T1503003A 
W1504008A 
W1408007A 
T1501028A 
T1505015A 
T1401079A 

T1601234A
T1605091A
T1606042A 

T1701187A 

M1606111A
10M40071A 
W1606112A 

T1706019A
T1705037A 
T1701110A 

W1808017A
M1607064A
T1805034A
T1806110A
T1801169A 

T1605198A
T1605197A
T1606063A
T1605209A
T1603139A
T1603140A

M1601184A
W1601185A
M1601186A
W1801070A
T1803030A
M1804034A
W1804035A
T1807050A

T1801040A
T1805013A 
T1605196A 
T1606059A 
T1706089A 
T1807150A 
W1804102A 
M1804101A 
M1704064A 
T1812008A 
W1704065A
M1704020A

T1607055A
T1707019A
T1807003A
T1807004A
T1801012A
T1712006A
W1504014A
12M10097A
T1601069A
T1712005A
T1812001A
T1405023A
13M60003A
T1606155A
13M60006A
T1512003A
W1402022A
T1607056A
W1404004A 
13W50010A 
W1604054A
M1704026A
T1805002A
M1504013A
M1404003A
W1704027A
T1505014A
T1707018A
14M60005A
T1507022A
T1701030A
T1605047A
T1705021A
T1501027A
M1504013A
M1604053A

T1606077A
T1706083A
T1701143A
T1705125A
T1801034A
T1605169A

T1705144A

W1704069A
M1704068A
T1705150A

M1704048A
T1807050A 
T1703041A 
T1801078A 
M1802053A 
T1805030A 
W1704049A 
T1706058A

T1705139A 
T1703049A 
T1706077A 

T1706059A
T1603163A
T1605247A
T1601121A 

T1703047A

T1606076A
T1705034A 
T1701102A 

W1704019A
M1704018A
T1703024A
T1701155A
W1802155A
M1802154A
T1712003A
W1802153A
W1702061A
M1702063A
W1702064A 
M1702062A 

T1605146A
T1501011A
T1601156A
T1601158A
T1606060A
W1401040A
T1601159A
T1601157A
M1401039A
T1401045A
T1402058A
T1605147A 

T1801014A 
W1808070A 
M1802008A 
T1807153A 
M1807130A 
T1805105A 
W1802004A 
M1804120A 
W1804121A
W1808004A 
T1806117A 
T1807018A

T1806092A
T1505045A 

T1605238A
T1705045A 
T1706082A
T1606129A
T1805061A
T1806043A
T1801160A 
T1601317A 
T1501166A 
T1701138A 

M1804070A
W1804139A
T1805036A 
T1706030A
T1806103A
T1605195A
T1803111A
T1705046A
T1701101A
11M60036A
12M10074A
T1801214A
12W10075A 
W1608020A

T1706023A 
11M20027A 
T1706022A 
T1706077A 
12M40029A
T1712002A
12M30053A 
12M60050A
T1701131A
W1704017A
M1704016A
T1705043A
T1705044A
T1605066A
T1806023A
T1605074A
12M60064A
M1801069A
T1805028A
T1705127A

T1605072A
W1802068A
T1807008A
T1607102A 
T1605250A 
T1405004A 
T1605071A 
T1812015A 
T1603072A 
T1807076A 
W1502032A 
T1807077A 
T1810107A 
T1601103A 
T1807078A
T1601147A
T1601150A
T1601151A
T1601149A
T1601148A
T1607103A
T1607104A 
T1407015A 
W1608071A 
W1408005A 
T1603073A 
T1803088A 
T1607105A 
M1804049A 
M1802008A 
W1804050A 
W1808004A 

T1601189A 
T1601190A 
T1601191A 
T1603095A 
T1606025A 
T1607107A 
T1701084A 
T1603096A 
T1701196A 
T1601188A 
T1605075A
T1701197A 
T1701198A 
T1701199A 

T1606113A
W1701003A
M1706001A
M1401067A
T1706003A
M1701002A
W1401068A
T1607187A
13M30028A
T1601164A
T1607161A
W1501067A
W1701005A
T1602137A
T1701004A
T1605186A
T1501025A
T1707002A
T1705002A
T1607133A
T1705003A 

T1606082A
T1506035A
T1706034A
T1807057A 
T1802116A 
T1805074A
W1406013A 
M1406012A 
14W10049A
14M10048A
13M30007A
M1502027A
T1503028A
14M20004A 
14W60003A 
13W70012A 
M1404024A 
W1404025A 
T1801092A 
T1603127A 
T1602138A 
T1607187A 
T1405024A
T1607065A
M1607174A 
T1705069A 
M1605167A
W1701061A
M1701060A
T1703025A
13W10058A 
T1406011A
13M10056A
T1607182A
13M40004A
13M10057A
11M60103A
M1601160A
W1601161A

T1812011A
T1801087A
T1806031A
T1607011A 
M1807007A 
T1805091A 
W1804125A
M1804124A
T1801116A

W1801167A
T1603101A
14W10067A
T1803052A
T1803051A
T1705096A
T1705097A
T1503081A
T1503082A
T1703034A 
T1703033A 
13M10047A
13M30013A
14M10066A
11M70040A
M1601210A
W1601211A
T1505092A 
T1505025A 
T1505024A 
W1701203A 
11M60032A
M1701202A
T1501076A
T1501075A
T1605098A
T1605099A
13M20004A
M1706055A
W1506013A 
W1806060A 
W1406005A 
M1406004A 
W1606036A 
M1806056A 
W1706056A 
M1506012A 
M1606035A 
T1805067A 
T1805068A 
M1801166A 
T1405018A 
T1603102A 
T1607118A 
T1403001A 
11W50045A 
11M50044A 

T1805120A
T1803103A
T1807050A
M1807086A
T1806091A
M1807012A

T1601258A
T1807128A
T1605111A
T1601259A
T1606130A 

T1603162A
T1605246A 
T1601318A 
T1606148A 
M1803045A 
W1803046A 
T1806036A 
T1506066A 
T1601002A 
T1805048A 

W1608080A
T1607124A
T1601107A
W1602123A
M1602122A
M1507032A
M1607079A
W1508021A 
W1608057A
T1303021A
T1603099A
W1603100A
M1605165A
W1505072A
W1605166A

T1706100A
T1705001A 
T1701001A 
T1707001A 

T1606043A
M1601320A
T1703018A
T1605184A
T1607120A
T1607168A
W1608091A
W1601321A
T1601322A

M1601173A
T1601172A
13W10028A 
T1805077A 
T1605129A 
T1801211A 
T1805084A 
T1605130A 
TT1405015A 
T1405005A 
13W10068A 
12M60003A 
T1607148A 
T1606079A 
T1601282A 
T1501105A 
T1606078A 

T1601312A
T1605199A 

T1601309A
T1603135A
T1605189A
T1606050A 

T1601239A
T1605100A
T1601316A
T1603095A 
T1606109A
W1604095A
M1604094A

T1706019A

M1601307A
W1601308A
T1605188A
T1607170A
T1707074A
W1602142A
M1602141A
T1403024A
T1603134A

14W10005A
13W10016A
W1601207A
12W10124A
W1502037A
13W20008A
11M60096A
T1607161A
W1404011A
08W20049A
13M70016A
T1503039A
W1608075A
W1602119A
13W10082A
W1505061A
W1506039A
13W50017A
12W20025A
12W10173A
12M30042A
13M30026A
W1405043A
12W10144A 
12M60035A 
12M60042A 

T1705141A 
W1704060A
M1702091A
W1702092A 
T1703050A 
W1708006A 
M1704061A 
M1702051A 
W1702052A
T1701040A
T1806102A
T1706097A

T1606100A
T1606102A
11M40024A
T1607191A
14W10051A 
14M10050A 
14M10052A 
12W10212A 
12M10211A 
11M30186A 
T1601238A 
T1605218A 
T1607186A 
T1605225A 

T1603173A
T1605039A
T1601056A
T1601012A
M1604108A
W1604109A 

T1601268A
T1501101A
T1603133A

T1805108A
T1705124A
T1403013A
T1603116A
T1605187A

T1712012A
T1812012A
T1801017A
T1801217A
T1705149A 

T1805051A 
T1805052A 
T1801083A 
W1808011A 
W1801081A 
T1607096A 
M1801080A 
T1806118A 
T1807157A 

T1603121A
T1501050A
T1707005A
T1501049A
T1303029A
T1805022A
T1505134A
T1706071A 
T1606141A 
T1806083A
T1801057A
T1605143A
T1505031A
T1705073A
T1801056A
T1701083A
T1701082A
T1601096A
T1601097A
14M10059A 
14M10060A 
12M30046A 
13M30021A 
12M10058A
11M30068A
11M60100A
13M30002A

T1801035A
T1501037A
T1501036A
W1702086A
13W50006A
13M50005A
T1605227A
T1505041A
T1601109A
W1604101A
M1604100A
M1704050A
M1504052A
W1504053A
T1701076A
T1601108A
T1701075A
M1804012A
M1603109A
W1603110A
M1603158A
W1603159A
M1803013A
W1803014A
W1804013A
W1608088A
T1606097A
T1706057A
T1406020A 
T1506034A 
T1806011A 
11M60110A 
T1705122A 
T1407053A 
T1607190A 
T1607065A 
T1801036A 
T1805012A
T1607187A

T1805111A
T1805110A
T1801177A
T1801176A
T1806089A 
T1806090A 

T1707017A
W1702014A
11M60017A
W1608002A
11M10053A
11W20021A
W1703009A 
M1702013A 
08W70006A 
08M60018
11M10052A 
09M40036B
09M40036C 
M1706006A 
T1607167A 
M1703008A 
T1507045A 
08M70005A 
11M60024A 
T1707009A 
11M30008A 
09M20004A
11M30009A
T1705018A
T1705019A

T1505009A
11M60114A
T1612027A
T1503060A
M1604086A
W1604087A
T1607081A 
T1603151A 
T1605060A 
T1607188A 
T1601112A 

W1604079A 
M1604078A 
T1603129A 

T1601315A
T1605226A 
W1706080A 
M1706079A 
T1603152A 

T1606051A
T1601196A
T1501147A
T1505078A
T1605168A
T1706020A 
M1704014A 
W1704015A 
12W10096A 
T1705036A 
13M30038A 
T1405022A 
T1701109A 
T1401081A 

T1803011A
T1601141A
T1601012A
T1803012A
W1802021A
M1802020A
W1602101A
M1602100A
T1605070A
T1501106A
T1806106A
T1707100A

W1704035A
T1801039A
T1803029A
T1501184A
T1605121A
T1605041A 
T1506061A 
12M70031A
12W20032A 
T1703038A 
T1501161A 
T1503046A 
T1501183A 
T1701179A 
T1601272A 
T1501121A
T1501160A
M1704034A
T1805023A
T1705107A
M1604098A
W1604099A
12W50025A
12M50024A
T1603157A
T1806022A 
T1706054A
T1606037A
M1804032A
W1804015A 
W1804014A 

W1702045A
M1702046A
T1705064A
T1607141A
T1701122A
14W10069A 
14M10068A 
14W10065A
14M10064A
14M60007A
14M20006A
T1701163A
T1601106A
13M50011A
T1607076A
13W50012A
W1604052A
M1504013A
M1604051A
M1604053A
T1801042A
T1501062A
T1607078A
M1607077A
M1602086A
M1602061A
T1701164A 
T1603049A 
W1602087A 
T1805015A
T1605115A
T1607142A
W1802025A
13M30011A
W1608055A
M1802024A
W1308004A
W1608056A
W1408001A
T1712007A
T1812005A
T1606038A
M1704022A
W1804019A
M1804018A 
T1712006A 
T1801043A 
14W20007A
W1704023A
12M60039A
T1605114A
13M60007A
T1607134A

W1401050A
W1401052A
T1701162A
T1705061A
T1501071A
12M10074A
14W10075A
T1505003A
T1605182A
T1505064A
T1605195A
12M30044A
M1606085A
W1606086A 
M1406015A 
W1406016A 
T1706085A 
T1606089A
T1406038A

T1601278A
T1505048A
T1501090A
T1601050A
T1501021A
T1501091A
T1606131A
T1601279A
T1501132A
T1501026A
T1601158A
T1503057A
T1503021A
T1503056A

M1607115A
M1601200A
W1608074A
W1601201A
M1604069A
W1604070A
T1603112A 
T1605079A 
T1607176A 
T1407050A 
11M60095A 
T1607116A 
T1606056A 
11M30062A 

T1601247A
T1601248A
M1601290A
W1601291A
T1605170A
T1605171A
M1602143A
W1602144A
T1603155A
T1603156A
T1606090A
T1606091A
T1506003A
T1707055A

T1506083A
T1606068A
T1806048A
12M40032A
13M40015A 
T1506007A 
T1505038A 
T1407033A 
T1605202A 
T1605203A 
T1607173A 
T1805098A 
T1705113A 
13M30038A 
T1501080A 
13M10068A 
T1405022A 
13M60015A 
T1603160A 
T1601292A 
13W10066A 
T1503076A 
M1801158A 
W1801159A 
T1701137A 

M1606114A
11M40019A 
11M40018A 
W1606115A 
M1405045A 
W1405046A
M1605096A
W1605097A
W1601206A
11W10136A
M1601204A
11M10134A
W1601205A
M1601203A
11M10133A

M1804133A
W1804134A 
T1803102A 
W1606162A 
M1606161A 
T1601098A 

W1806013A
M1806012A
T1809002A
M1801041A
W1808019A
M1807024A
M1807023A 
M1802022A 
W1808020A 
W1801131A 
T1810007A 
T1810005A 
W1802023A 
T1807022A
T1805014A
W1808021A
M1807026A
W1802029A
M1802028A
W1804016A
M1804017A
W1810008A
T1810006A
T1810003A
W1810009A
M1810010A
T1803016A

T1705023A
T1701052A 
W1701051A 
M1701050A 
T1606044A 
M1604043A 
11M40033A 
W1702023A 
M1701050A 
11W10261A 
12W10154A 
W1602077A 
M1601076A
T1605101A
11W10261A 
M1702022A
M1602076A
W1702021A
W1608081A
12M10158A 
11M10260A 

T1806054A
W1802101A 
T1805107A
T1806077A

T1405012A
T1705008A
T1707005A
T1605118A
T1607144A
T1707082A
T1705093A
T1605104A
T1507034A
T1701071A 
T1701072A 
T1505030A 
T1601143A 
T1807146A 
T1805121A 
T1701010A 
T1601144A 
T1607135A 
T1701012A
T1801125A
T1701186A
T1701009A 
T1701185A 
T1701011A 
T1801124A 
T1803110A 
T1705007A 
T1503054A 
T1707004A 
T1707083A 
M1807149A 
T1705094A 
M1407036A
T1601269A
T1601270A
T1706086A
13M60019A
13M30044A
13M10081A
13M50013A
13W50014A
T1401078A
T1401077A
T1401076A
T1405054A
T1407039A
M1403018A
T1606132A
12M40049A
T1406018A
T1405055A
T1407046A

T1806058A
T1706076A
T1805081A
T1705035A
T1705146A 
T1801186A 
T1701194A 
T1701105A 
T1803059A 

11M40058A
M1606075A
W1606074A 

T1701058A
T1705089A
M1802125A
W1608026A
T1806057A 
W1704031A 
T1706052A 
T1806001A 
M1704030A 

T1605240A
T1606135A 
T1803107A 

M1802109A
W1802110A 
T1801127A 
T1807084A
T1805070A
T1806049A
T1706095A
T1706091A
T1706096A
T1803054A 

M1607106A
W1702036A
M1702035A
T1601169A
W1702038A
W1704013A
T1605164A
M1702037A
T1603120A
M1704012A 
T1606121A 

T1705105A 
M1801037A 
W1801081A 
W1801038A 
W1701086A 
T1703052A 
T1803092A 
T1503087A 

M1801112A
M1807062A
M1801108A
W1805076A 
M1801111A 
M1805075A 
M1807089A 
W1807090A 
M1807063A 
W1801115A 
W1801113A
W1807064A 
W1801114A
W1807065A
T1607189A
11M60134A
W1806074A 
M1806073A 
M1706064A
W1706065A
M1606095A
W1606090A
08M40039A
08M40039B
M1601128A 
M1601127A 
W1607090A 
W1601133A 
M1601131A 
M1607165A 
W1607088A 
W1605163A 
W1601134A 
M1607086A 
W1607166A 
W1601132A 
M1607085A 
W1605162A 

T1601252A
T1601251A 
T1607161A
11W10179A
11M10178A
09M60039A
11W20043A
11M30082A
11M30081A
W1606126A
11M40006A
11M40007A
N1606125A
W1606122A 
M1606123A 
M1605223A 
W1605224A 
M1601249A 
W1601250A
M1505069A
T1605210A 
W1605211A
T1603141A
T1603142A 
W1505068A 
T1503041A 
T1503042A 

M1602133A
T1605178A
T1601289A 
T1606159A 
T1506101 
M1604076A
W1604077A 
W1602134A

11W20033A
11M10143A
11W10144A
M1606103A
11M40042A
11M70033A
11W70034A 
T1601255A 
M1505062A
T1605155A
M1501077A
T1705046A
12M10103A
12W10104A
W1605156A
W1606104A
12M70011A
12M60043A
M1601253A
T1601254A

T1706039A
T1701160A 
T1705104A 
T1703036A 

T1607140A 
M1607183A 
T1706078A 
T1601117A 
M1607077A 
T1602089A 
W1608060A 
T1605222A 

T1601265A
T1701140A
T1605154A
M1602056A
W1602059A 
M1502007A 
T1603125A
T1606050A 
13M40038A

T1606040A 
W1608067A 
W1601135A 
M1607092A 
T1601171A 
T1607095A 
T1601136A 
T1605138A 

M1604106A
M1602010A
W1604107A 
W1602013A
W1708021A
M1602010A
T1606157A

T1706018A
T1701116A
T1605076A
T1701014A 
T1705038A 
T1605078A 
T1703045A 
T1701113A 
T1601193A
T1606153A

T1605105A 
T1405062A 
T1607136A 
12M40007A 
12M40006A 
T1406035A 
T1605106A 
T1603153A
T1606116A
M1601091A
M1601090A 
W1601093A 
T1506031A
14M10026A 
W1401063A 
W1601092A 

M1801142A 
T1801145A
T1801147A
T1805055A
W1808011A
M1807007A 
M1804005A
W1804004A
T1806041A
T1601180A
T1605185A
T1607169A
T1601181A
11M40059A 
M1606075A
T1606009A
M1406027A

T1706035A 
T1803044A
T1605219A
T1607182A
T1601104A
T1601105A
M1802079A 
W1802084A 


T1801192A
T1805085A
T1801191A 
M1804122A 
T1803093A 
T1806105A 

T1805109A
T1806081A
T1801212A
W1808077A
T1606031A
T1606032A 
T1605090A
T1606031A

T1706072A
T1801164A
T1705119A 
T1701191A 

T1706044A
M1607163A
W1707069A
M1701145A
13M30031A
T1705066A 
W1705067A 
13W30032A 
T1601199A 
T1605200A 
T1605201A 
W1701146A
11W10263A 
12M40036A

T1706098A 
T1705062A 

T1706036A
M1601286A
W1601287A
14M10042A
14W10043A
T1605228A
T1605221A
T1605220A
T1705076A
T1705077A
M1701168A
W1701169A 

T1705090A
M1804060A 
W1804061A 
T1805056A
M1404012A
W1404013A
T1612001A
T1712003A
T1701148A
T1601231A
T1801004A
T1707014A
T1806042A

M1601113A 
T1604071A 
W1601114A 
T1607082A 
T1607162A 
T1605158A
W1601116A
W1601004A
T1506038A
T1406042A 
T1602088A
W1402027A
T1402003A
M1402026A 
W1502021A 
M1602139A 
W1602140A
T1612024A
T1712009A
W1401055A
W1708021A
M1507029A
T1607001A
M1601115A
T1606041A 
T1404023A 
W1708021A 
M1607083A 
W1608059A 
T1607084A 

T1605094A
T1606064A
T1601240A

M1607122A
W1608079A
T1605141A
T1601226A
M1601224A
W1601225A
T1606127A

T1605206A
T1601170A
T1603138A
T1606147A

T1603148A
T1706047A
T1601264A
T1801079A
T1603147A
T1605217A
T1503071A
T1805031A 

T1606081A 
T1706098A 
T1506021A
T1805065A
T1501131A
T1705062A 
T1801210A
T1505055A
W1606088A
T1505115A 
T1806075A
T1701167A 
T1601288A 
T1605139A 

T1601215A
11M20002A
T1303030A
T1606045A 
T1603107A 
M1302029A 
T1603106A 
T1603046A 
T1603016A 
T1503052A 
T1605082A 
T1603107A 
T1405071A 
13M30005A 
W1302020A 
M1602120A 
W1602121A 

T1612033A
W1502001A
W1604050A
M1604049A
M1504001A
T1707039A

M1606057A
T1601263A
T1601232A
W1606058A
M1604065A
W1604066A
13M50022A
13W50004A
13W50023A
13M50003A
T1605180A

T1606052A
T1601229A 
T1605172A 

T1601228A

T1601237A
T1601236A
T1501118A

T1803089A
T1705103A
12M30056A
T1405051A
T1701188A
12W10131A
11W10127A
12M10130A
11M10075A
T1605193A
T1505066A
T1601267A
T1501073A
12M40026A
T1401075A

T1805113A
T1807139A
T1806096A
T1806094A
T1806093A
T1806095A
T1805115A
T1801126A

T1603130A
T1601306A
T1605181A

T1605073A
12M40042A
T1606023A
14M10053A
14W10054A
T1601174A
14M20005A
T1601175A
T1501038A
12M30061A
W1608088A

T1506080A
W1604097A
M1604096A
T1605237A
T1701193A

T1606066A
W1706099A
T1706094A
T1806104A
11M60019A
M1605214A
M1505058A
T1405034A
W1605213A
W1505057A 
W1501051A 
M1501052A 
T1701136A 
T1705095A 

T1601138A 
W1604068A 
M1604067A 
T1605128A
T1606124A

W1601167A
M1601166A
W1601168A 
M1601165A 
T1605142A 
T1807045A 
T1607151A 
T1606062A 
W1604083A 
W1703003A 
T1603118A
M1703002A
M1602135A
M1502028A
W1608085A
W1508024A
W1602136A
T1603119A
M1604082A
T1605142A 
T1807045A 

T1606151A
T1705050A
T1701142A
T1601260A 
T1605112A 
T1703043A
W1708022A

T1601261A
T1605113A 
T1801123A
T1606070A

T1705049A 
T1706025A 
T1605125A 
T1601280A 
T1705049A 
T1606156A
T1505094A
T1701141A 
T1706095A 

W1804136A
M1804135A
M1809007A
W1808080A
W1808081A
W1802004A 
T1805116A
T1807139A 
T1803105A 
M1807042A 
W1808058A 
T1801090A 
M1812016A 
W1802017A 

T1605085A
T1601221A
T1612017A

M1805027A
W1805026A
W1604056A
M1604055A
W1806047A
M1806046A
12W50010A
12M50009A
M1706045A
M1606083A
W1606084A
M1405019A
W1405020A
12M70007A
T1505035A 
W1801051A 
W1601284A 
T1501115A 
M1601285A 
M1801052A 
M1701070A 
T1501172A 
T1605135A 
12M40034A 
T1605134A 
T1705099A 
14M10026A 
T1705100A 
14W10027A 
W1701069A 

M1702041A
M1707053A
T1707052A
W1608073A 
T1607180A
W1702044A
W1708011A
W1702042A 
M1702043A
T1607177A
T1601310A
T1607178A
T1601311A
T1607179A
T1703020A
T1703021A

M1804025A 
W1804027A 

M1604009A
W1604011A
W1604021A 
M1604004A 
W1604012A
W1604017A
M1604005A
W1604020A

M1504038A
W1504039A

T1607043A
W1602051A
M1603109A
T1807019A 
T1809001A
T1701022A
10M20055A
T1801130A
T1607073A
T1607041A
W1602060A
M1602057A
W1608037A
M1801106A
T1606010A
T1607036A 
T1601140A 
T1607029A 
T1607080A 
T1607074A 
T1603002A 
T1607017A 
T1607040A 
T1605038A 
W1604020A 

T1603008A 
W1602058A 
W1602054A 
M1602061A 
T1607043A 
M1607028A 
T1601005A 
T1506023A 
M1607002A 
M1604019A 
M1507042A 
T1605039A 
T1607143A 
W1608089A 
T1605102A
T1605110A
T1505093A
T1601256A 
T1601245A 
T1501123A 
T1701008A 
T1605180A 
T1801011A 
M1804131A
W1808047A
T1807070A
M1807068A
M1807069A
W1808048A
M1607005A
W1608029A
T1607017A 
T1607040A 
W1608022A
T1607074A 

M1602044A
M1602044A
M1602044A
W1802004A
T1601029A
M1707008A
M1707007A
T1701026A
W1302028A
M1702002A
T1807018A
T1601023A
W1801023A
M1807017A
M1807017A
M1807017A
M1807017A
M1807011A
M1807011A
W1808015A
W1808015A
W1602047A
T1807019A
M1802016A
M1807014A
M1805011A
W1808007A
W1808007A
W1808008A
T1807008A
M1807010A
W1801021A
T1807015A
T1807015A
T1807015A
T1601030A
T1601030A
M1602030A
M1702002A
T1601033A
T1807009A
T1807009A
N1502003A
N1502003A
W1602036A
W1602036A
M1707010A
W1607006A
T1607072A
T1705014A
T1605011A
T1605011A
T1601021A
T1601021A
T1601016A
T1601016A
T1805009A
T1805009A
T1805009A
T1805009A
T1805009A
T1701026A
W1308001A
M1801029A
M1801029A
T1605013A
W1702004A
T1601020A
W1602036A
M1602030A
T1603026A
T1701026A
T1701026A
T1701026A
T1701026A
T1601016A
T1601016A
T1601016A
W1701039A
W1701039A
W1701039A
W1701039A
N1701038A
N1701038A
N1701038A
W1708002A
W1708002A
W1708002A
W1708002A
T1705017A
T1705017A
T1705017A
T1705017A
T1705011A
T1705011A
T1705011A
T1705011A
M1704004A
M1704004A
M1704004A
M1704004A
M1704004A
T1603001A
T1603001A
T1603001A
T1603001A
T1703004A
T1703004A
T1703004A
M1807017A
M1807017A
T1803005A
T1803005A
T1803005A
T1803005A
T1807018A
M1707007A
M1707007A
M1707007A
M1707007A
T1803010A
T1707072A
T1707072A
T1705012A
W1701037A
W1701037A
W1701037A
W1701037A
T1705016A
T1703006A
T1605016A
12M60014A
T1705014A
T1603022A
T1605003A
T1705011A
T1605004A
T1605013A
T1407038A
T1705014A
T1601016A
M1707016A
M1707016A
M1707016A
M1707016A
T1505004A
T1505013A
T1601030A
T1701026A
T1705017A
14W10047A
T1701018A
T1701018A
T1801033A
T1801033A
T1705011A
W1801027A
W1801028A
W1801023A
W1803006A
T1705015A
T1705015A
T1705015A
T1705015A
T1705015A
W1808003A
T1801033A
T1803009A
T1803009A
M1803007A
W1808002A
T1605011A
T1605003A
M1704014A
W1708020A
T1501015A
T1603014A
M1602030A
W1602036A
M1604005A
W1604012A
W1612004A
T1612002A
T1705072A
T1705072A
T1705082A
T1705082A
T1703028A
T1703028A
T1703030A
T1703030A
T1703027A
T1703027A
T1703029A
T1703029A
T1707073A
T1707073A
T1705008A
T1705008A
T1705084A
T1705084A
T1707015A
T1707015A
T1707015A
W1602036A
W1602036A
W1702005A
W1702005A
W1702005A
M1802033A
W1802034A
W1802034A
T1805011A
T1805011A
T1805011A
T1805011A
T1805011A
T1812002A
T1812002A
T1812002A
T1812002A
T1812002A
T1812002A
T1806005A
T1806005A
T1806005A
T1806005A
T1806015A
T1806015A
T1806015A
T1806015A
T1806007A
T1806007A
T1806007A
T1806007A
T1806010A
T1806010A
T1806010A
T1806010A
T1701181A
T1701181A
T1701175A
T1701175A
T1701173A
T1701173A
T1705083A
T1705083A
T1705085A
T1705085A
T1701182A
T1701182A
W1702073A
W1702073A
T1701174A
T1701172A
T1701177A
T1705080A
T1705080A
T1705071A
T1705071A
T1707071A
T1707071A
W1702074A
W1702074A
M1702075A
T1701178A
T1707070A
T1707070A
T1806006A
T1806006A
T1806006A
T1806006A
T1806006A
T1806009A
T1706012A
W1702001A
W1702001A
W1702001A
W1402021A
W1703001A
W1703001A
W1703001A
W1704001A
W1704001A
T1501011A
T1501011A
T1501011A
W1502012A
W1502012A
W1502012A
W1502012A
M1702007A
M1702007A
M1702007A
W1702009A
W1702009A
W1704005A
W1704005A
W1704005A
W1704005A
M1801002A
T1801013A
T1807015A
W1808004A
W1804007A
T1807009A
T1807009A
T1807008A
M1807014A
M1807012A
W1808008A
W1808008A
W1808012A
W1808012A
M1803007A
T1801014A
W1808011A
W1808009A
M1802009A
M1802008A
W1802006A
M1802010A
W1802011A
T1807018A
T1807018A
T1805008A
W1808006A
T1805005A
T1805005A
W1801028A
T1807013A
W1801027A
T1801019A
M1802005A
W1808005A
M1804006A
T1801018A
M1807007A
M1801024A
T1809001A
W1804004A
W1612011A
M1602030A
T1706029A
T1706005A
W1607006A

T1605019A
T1805115A
M1607064A
W1608035A
T1705088A
T1605108A
T1607036A
M1807107A
T1805058A
T1605067A
T1805074A
M1807079A
W1708021A
T1607034A
T1607063A
T1607041A
W1808034A
M1607010A
W1608033A
T1607140A
T1607103A
W1807118A
T1607096A
T1707078A
T1803041A
T1807057A
M1807044A
T1807140A

W1612009A
T1612010A
T1607161A
M1704070A
T1701122A
W1504014A
T1805120A
T1807050A
W1607006A
T1607073A
T1607038A

T1806001A
T1501027A
T1805002A
W1608070A
T1602089A
T1403005A
T1607063A
T1607193A
W1602054A
T1806039A
T1503067A
M1606123A
T1503022A
T1603106A
M1604006A
T1603031A
T1706005A
T1612022A
W1604077A
W1801196A
T1607036A
T1607074A
T1607017A
11M60178A
11M60021A
11M60110A
11M60021A
T1603095A
T1603096A
T1605075A
T1607107A
T1601191A
T1601188A
T1601189A
T1601190A
T1701084A
T1807003A
T1807004A
M1802120A
W1802119A
T1601237A
T1607040A
T1603041A
T1603031A
T1607043A
M1602063A
T1607073A
M1602055A
T1607036A
M1607005A
M1805075A
T1705147A
11M60192A
M1804131A
W1804132A
T1812014A
M1804017A
W1608037A
T1503002A
T1501007A
T1406045A
M1606083A
T1812013A
M1809006A
W1808076A
M1602055A
W1602058A
T1603009A
W1608035A
T1603002A
T1607073A
T1607036A
T1607017A
T1705030A
T1601059A
12M70040A
W1602054A
T1605007A
11M10068A
T1501106A
T1701191A
T1501026A
T1607033A
W1602060A
T1607036A
T1605006A
T1607074A
T1607042A
T1607039A
W1608034A
T1605019A
M1602063A
T1605035A
T1601057A
T1601050A
T1607034A
T1605041A
W1608029A
W1608022A
T1603098A
M1607010A
T1601054A
T1605032A
T1605019A
T1607014A
T1607017A
T1607033A
T1607073A
T1603008A
T1607041A
T1607036A
W1705086A
M1809006A
W1808079A
T1805091A
T1801087A
T1801043A
M1604028A
T1603116A
T1805118A
W1605211A
T1705001A
T1605206A
T1605117A
T1801177A
T1605240A
T1605190A
12M40023A
13M70018A
12M70017A
12M40022A
12W70018A
T1503058A
T1503059A
12M70019A
12M60006A
T1412008A
W1308002A
T1612023A
W1604039A
M1403009A
M1604038A
12W50006A
13M40042A
13M40041A
T1601237A
T1601236A
T1701160A
T1612021A
T1705098A
T1707085A
T1607139A
T1507013A
T1607036A
W1602058A
M1602055A
W1602036A
M1602063A
W1602062A
T1702071A
W1702086A
W1302010A
W1502013A
W1608024A
W1602047A
T1805130A
T1607017A

T1807003A
T1805002A
T1801012A
T1607043A
T1807004A
T1812001A
T1705021A
T1712005A
T1603049A 
T1701030A
T1707019A
T1707018A
M1504013A 

T1612028A 
T1605199A 
T1706077A 
T1801007A 
T1801006A 
T1801009A 
T1806002A
W1604020A
T1805125A
M1702055A
W1608020A
T1705039A
T1605180A
T1601120A
T1512002A 
T1601312A
W1604010A
M1604004A 
M1604018A
W1604021A
W1608057A
M1502014A
W1502013A 
T1607062A 
W1302010A 
12M30040A 
T1503037A 
M1602049A 
W1602051A 
M1704004A 
M1701145A 
M1601204A 
T1607037A 
T1607036A 
T1607182A 
T1607140A 
T1607133A 

W1508006A 
W1608024A 
T1701017A 
T1601056A 
T1601028A
T1607013A 
M1607009A 
T1607016A 
T1606011A 
T1506015A 
T1603014A 
T1603022A 
W1604010A 
T1607074A 
T1605180A 
T1807019A 
T1605011A 
T1803010A 
W1808015A 
M1807017A 
T1805009A 
T1805011A 
T1805009A 
T1807013A 
T1805010A 
W1604041A 
M1604042A 
T1603049A 
T1607080A 
M1702046A 
T1801057A 
W1608034A 
M1602044A 
T1805045A 
M1704070A 
T1707077A 
T1701052A 
T1705023A 
T1807050A 
W1708020A 
M1807068A 
T1807050A 
T1701160A 
T1605077A 
T1601236A 

T1607036A 
T1607057A 
W1608029A 
T1607073A 
T1607040A 
T1603045A 
W1608024A
T1607034A
T1606144A
M1602055A
T1505014A
W1702077A
T1607039A
T1607036A
T1607013A 
12M40029A 
T1506004A 
T1707018A 
T1507022A 
T1507021A
T1701131A
T1607014A
T1607190A 
W1603110A
T1705069A 
T1706097A 
W1508009A
W1608037A
T1605039A
M1802028A
T1607042A 
T1607016A 
W1602058A 
W1702004A 
M1602055A 
T1806097A 
T1606143A 

W1708015A 
T1807019A
M1807012A
M1807010A
13M20002A
11M60071A
T1607012A
11M60133A
M1607005A
M1607160A 
T1607127A
M1707053A
T1810004A
T1810003A

M1610007A 
M1610009A 
11M80006A
11W80007A
W1610008A
W1610010A
13M80004A
13M80011A
M1610022A
13M80005A
W1610006A
M1610005A
M1710009A
12M80012A
W1410005A 
13M80007A 
M1810069A 
W1810051A
W1610023A
12M80007A
M1710010A
W1410011A
11W80012A
M1610011A
M1510036A
M1710008A
11W80002A
13M80008A
13M80009A
W1610012A
W1510035A
W1710007A
T1810130A
M1810059A
M1810124A
W1810125A 
T1710021A 
11M80011A 
12M80007A 
W1510016A 
M1510014A 
W1810099A 
M1810098A 
M1610013A
W1610014A
T1710040A 
T1810110A 
M1810114A 
W1810112A 
W1410005A 
11M80001A

T1605039A
T1605039A
T1605039A
T1605039A
T1807151A
T1810072A 
M1602055A 
M1804032A 
T1605006A 
W1804033A
T1501022A
T1607014A
T1607014A
M1602055A 
T1806079A
W1608024A
T1812014A
T1812014A
W1804132A
M1802098A
T1801068A
T1807140A
T1806092A
T1806092A
T1806092A
M1804131A
W1802097A
W1808047A
T1802102A
W1803101A
M1802096A
M1807143A
T1804130A
M1803100A
T1802095A
W1808048A 
W1803099A 
M1807069A 
M1802093A 
M1807145A 
W1802019A 
W1802094A 
T1805117A 
T1601014A 
W1502013A 
T1605026A 
T1503022A 
T1607057A 
T1603045A 
T1605026A 
T1505011A 
T1706005A 
T1603032A 
T1701022A 
T1601056A 
T1501022A 
T1603002A 
W1602054A 
T1607073A 
T1607029A 
M1607010A 
T1607074A 
T1707014A 
T1603049A 
W1608048A
T1701022A 
M1607077A
T1607036A
T1607014A
T1701022A 
T1607013A
T1607014A
T1605027A
T1501020A
T1607017A
M1602049A
T1601059A
T1607074A 
T1607042A
W1602051A
W1608024A
T1607063A
W1608029A
T1606006A
M1604019A
M1604003A
W1604021A 
W1604010A 
M1607010A 
T1601057A 
W1608033A 
W1602058A 
W1508005A 
T1501021A 
T1601014A 
W1608026A 
T1601014A 
T1501021A 
W1508005A 
T1603002A 
W1808015A 
W1808004A 
W1808012A
M1807012A
T1807018A
M1807017A
W1808005A
T1807015A
T1807009A
W1808010A
M1807011A
W1808008A
T1807006A
T1807019A
W1808007A
W1808014A 
W1808013A
T1806008A
T1806010A
T1805009A
M1804006A
T1801014A
T1807008A
W1804007A
W1802011A 
T1801031A
W1802006A
T1801013A
M1804008A
M1802008A
M1802014A
M1802009A
W1801025A
W1802013A
W1802007A
W1802017A
M1802010A
T1803010A
T1801030A
M1805006A
T1803009A
T1801018A
M1801029A
W1801027A
M1802012A
T1805010A
M1801024A
W1804009A 
T1801033A 
T1801019A 
W1808011A 
W1802015A 
T1807013A 
T1803005A 
T1805008A 
M1802016A 
W1804011A 
T1806015A 
T1807008A 
T1807018A 
W1808007A 
T1807018A 
T1807009A 
W1808015A 
M1807011A 
W1808004A 
W1808014A 
T1807015A
W1808004A 
T1807013A 
T1807013A 
T1807015A
W1608024A 
W1808011A 
M1807014A 
M1807007A 
W1808008A 
M1807012A 
M1607010A 
T1607040A 
T1807009A 
W1808011A 
W1808013A 
M1807017A 
T1807015A 
T1607039A 
W1808014A 
W1808015A 
M1807010A 
W1808005A 
W1808010A
T1707015A
T1807019A
M1807007A 
M1807014A 
M1802016A 
T1801019A 
W1804004A
W1805007A
W1808005A
M1801029A
M1802014A
T1801033A 
T1806014A
M1802012A
W1802007A
T1801013A
W1802017A
W1801025A
M1802009A
T1801013A
T1801032A
W1801025A
M1801024A
T1801031A
T1803010A
W1801027A 
M1802016A 
M1805006A 
T1803009A 
W1802017A 
W1808012A 
W1802013A 
W1804009A 
W1801028A 
T1801032A 
M1801026A 
M1802016A 
T1806008A 
W1802006A
W1802011A 
W1802015A 
T1801030A
T1805009A
M1801022A
M1804005A
M1802055A
M1802010A
M1804008A
T1803005A 
M1802012A
M1801029A
T1805005A
W1802004A
T1801018A
W1801023A
T1801014A
M1802008A
T1801033A 
M1801024A
T1812002A
T1806006A
T1806014A
T1806005A
T1806009A
T1806007A
T1806007A
T1806004A
T1806008A 
T1801032A 
W1801028A 
M1801026A 
M1802005A 
T1805008A 
M1803007A
W1801028A 
T1805009A
M1801026A 
W1802015A 
T1805005A
T1805011A
M1804010A
W1805007A 
T1805004A 
T1805005A 
T1801018A
T1805008A 
T1805010A 
T1805011A 
W1801027A 
W1802006A 
W1804007A 
M1804006A 
M1804010A 
T1806004A 
T1812002A 
W1804011A 
T1806014A
T1806005A
T1806010A
T1812003A
W1604021A 
W1604021A 
W1604021A 
W1604021A 
W1604021A 
W1604021A 
M1604019A
M1604019A
M1604019A
M1604019A
M1604019A
W1604103A
W1604103A
W1604103A
W1604103A
W1604103A
W1604103A
W1604103A
W1604103A
M1604102A
M1604102A
M1604102A
M1604102A
M1604102A
M1604102A
T1812012A
T1805118A
T1607014A
W1608034A 
W1608034A 
T1607034A 
T1607034A 
T1607034A 
T1607042A 
T1607039A 
T1607039A 
T1801217A 
W1608022A 
W1608022A 
W1608022A 
M1602049A 
M1602049A 
W1602051A 
M1602055A 
W1602054A 
W1802068A 
T1607014A 
T1605019A 
T1607017A 
T1601056A 
W1604020A
W1604020A
M1604003A
M1604003A
W1604010A 
W1604010A 
M1604018A
M1604018A
T1607038A
T1607057A 
T1605094A
T1601240A
W1608062A
W1608062A
W1608062A
T1806063A
T1603002A 
T1603002A 
T1603002A 
T1603002A 
T1806037A
T1607074A 
M1807113A
M1807113A
M1807113A
M1807113A
M1807113A
M1807113A
M1807113A
T1803082A
T1803082A
T1803082A
T1803082A
T1803083A
T1803083A
T1803083A
T1803083A
T1803083A
T1807109A
T1807109A
T1801199A
T1801199A
T1801199A
T1801199A
M1807108A
M1807108A
M1807108A
M1807108A
W1808076A 
W1808076A 
W1808076A 
M1807135A 
M1807135A 
M1807135A 
T1805128A 
T1805128A 
T1805128A 
T1805128A 
T1805128A 
M1504040A 
W1604021A 
W1504041A
M1604018A
M1604018A
W1604103A
T1506004A
T1607041A
T1603045A 
W1302010A
T1607014A
M1707011A
T1507005A
T1505012A
T1603049A 
W1602064A
T1603020A
07M20003A
M1801020A
T1607057A 
T1603043A
T1607015A
W1704015A 
T1607014A 
T1601060A 
T1607017A 
W1708003A 
T1607043A 
11M60091A
M1302021A
M1602057A
T1607038A
W1602054A 
T1603008A
T1603045A 
T1605038A
T1503018A
W1804003A
W1604010A 
T1601028A
T1507003A
W1604020A
T1603032A 
T1603020A
W1802004A
T1807018A
W1804004A
M1602055A 
W1608022A 
T1603002A 
M1602057A
T1607036A
M1607010A 
W1608029A
T1605019A 
M1604019A 
W1604021A 
W1602058A 
T1603002A 
T1605039A 
M1607005A 
T1605039A 
T1605038A 
T1603002A 
W1602058A 
W1604021A 
W1502013A 
T1807019A 
T1807009A 
M1807012A 
W1808011A 
M1807011A 
W1808011A 
W1808012A 
W1808008A 
W1808007A 
W1808007A 
T1807008A
M1807012A
T1807009A
M1807014A 
T1807019A
T1807013A 
T1805009A
T1803010A
T1805010A
W1805007A
M1803007A
T1803005A 
M1801026A 
T1805011A
T1805008A 
T1805005A
M1802005A 
M1804008A
M1801020A
M1802016A 
M1802008A
M1802010A
M1801029A
T1801014A
T1801018A
W1801027A
T1801032A
W1801021A
M1801020A
M1801026A 
T1801033A 
W1801028A 
W1802006A
T1801013A
W1801025A 
M1802016A 
W1802015A 
M1802014A 
W1802004A 
W1804009A 
M1804008A 
M1804006A 
W1804007A 
W1802013A 
M1802012A 
T1801032A 
W1801023A 
T1801018A 
T1801031A 
T1805008A 
W1802017A 
M1802009A 
T1801032A 
M1802012A 
W1802011A 
M1802005A 
W1802007A
M1802008A
W1802013A
W1802015A 
M1802014A
T1805009A
T1801030A
T1803010A
T1803005A 
W1805007A
M1802014A
T1805010A
W1804009A 
T1805008A 
T1801013A
T1805011A
T1801019A 
W1801027A
T1801018A 
T1801013A 
T1803010A 
T1801030A 
M1802010A 
M1801029A 
M1801026A 
M1802009A 
M1801024A 
W1802013A 
W1802015A 
M1802014A 
T1801031A 
W1801025A 
W1802017A 
M1802009A 
W1802013A 
M1804010A 
T1801019A 
T1801033A 
M1802016A 
M1802012A 
W1802011A 
M1802010A 
W1801025A 
M1801024A 
W1801028A 
M1804008A 
W1808006A 
M1807012A
T1807008A 
M1807017A 
M1807010A 
M1807011A
T1807018A
T1807009A
W1808015A 
M1807017A
M1807011A
T1807013A 
T1807008A 
M1807012A 
M1807010A 
T1807015A 
M1807007A 
M1807014A 
M1807007A 
M1807017A 
M1807011A 
T1807013A 
M1807010A 
T1807015A 
M1807010A 
T1807018A 
T1807019A 
T1807006A 
W1808010A 
W1808004A 
W1808015A 
W1808008A 
W1808011A 
W1808005A 
W1808015A 
W1808007A 
W1808012A 
W1808008A
W1808007A
W1808014A 
W1808007A
W1808008A
W1808014A 
W1808012A 
T1812003A 
T1806009A 
T1806007A 
T1806005A 
T1806008A 
T1806006A 
T1806014A 
T1806005A 
W1804011A 
T1806004A
T1806010A
T1806015A 
T1501011A
M1807012A
M1807012A
T1806014A
M1804008A
T1701018A
T1801014A
W1808009A
M1804006A
T1805010A
W1808002A
W1804007A
T1801018A
T1801030A
M1802008A
W1804004A
T1805005A
T1805011A
T1806010A
T1801031A
T1806004A
T1801018A
T1806015A 
T1806014A
T1812002A
T1806005A
T1806015A 
T1701018A
T1612017A
T1701025A
M1807012A
W1804007A
T1807008A
M1804006A
T1501015A
M1701081A
T1701025A
W1508002A
T1705011A
T1701025A
T1801168A
T1801168A
T1801168A
T1801168A
T1801161A
T1801161A
T1801161A
T1801161A
M1802074A
M1802074A
M1802074A
M1802074A
M1802074A
W1808056A
T1805069A
T1805069A
T1805069A
W1802132A
W1802132A
T1807109A
T1807109A
T1807109A
W1808060A
W1804059A
T1506020A
T1606008A
T1606008A
T1506003A 
T1506015A 
W1604010A 
W1604010A 
M1604019A 
W1604021A 
M1604003A 
M1804002A 
M1807068A 
W1502013A 
W1302010A 
T1702071A 
W1602036A 
W1702004A
M1807012A
W1608058A
M1707053A
T1807015A
T1807006A
M1807007A 
T1807018A
M1807010A 
T1807019A
T1807009A
W1808015A 
T1807013A 
M1807011A 
W1808012A 
M1807017A 
W1808004A 
W1808014A 
W1808013A 
T1806007A 
T1801033A 
W1802007A 
W1802007A 
M1802008A 
T1801031A 
M1801024A 
M1805006A 
M1804010A 
T1801014A 
T1801019A 
T1805004A 
T1801032A 
M1802010A 
M1802009A 
W1804009A 
W1801025A 
W1801028A 
T1806010A 
W1804011A 
T1806004A 
M1801029A 
M1801026A 
W1802013A 
W1804007A 
T1805009A 
M1804008A 
T1801014A 
T1805010A
T1803005A 
W1801027A 
W1802015A 
M1804006A 
T1803010A 
W1802006A 
M1802012A 
W1802011A 
T1806005A
M1802014A
M1802005A 
T1806004A
T1806015A 
T1806007A
M1802008A
T1806005A
T1806010A
T1806009A
T1806014A 
T1806006A 
M1804010A 
M1804006A 
W1804007A 
T1812002A 
T1806008A 
T1801031A 
W1802017A 
M1807014A 
T1803005A 
T1803009A 
W1804004A 
M1801029A 
M1801026A 
T1801018A 
M1807007A 
W1801028A 
W1801027A 
T1807008A 
T1805010A 
T1803010A 
T1801032A
T1801033A 
W1802006A
W1802007A
M1802016A 
M1802055A
';

        $gong = explode(PHP_EOL,$shang);
        $gong_s = [];
        foreach ($gong as &$v){

            if(strlen($v)>2){
                $gong_s[] = trim($v);
            }
        }
        return $gong_s;
    }

    public function wu(){
        $fashion_code = '
T1601079A
T1601078A
T1601081A
T1601080A
M1607064A
T1605058A
W1808018A
T1607072A
W1608050A
T1605056A
T1605059A
T1605057A

T1805100A
T1807129A
T1806076A
T1706042A
T1701074A
T1805101A
T1706043A
T1705024A
T1705029A
T1807055A
T1801189A
T1801190A
T1701056A

T1801109A
T1807058A
T1807059A
T1805039A
M1802069A
M1807060A
M1802082A
W1802083A
W1802086A
M1804053A
W1804054A
W1808043A
W1808044A
M1807061A
M1803042A
T1805043A
T1801110A
T1806034A
T1803043A
W1802070A
M1804051A
W1804052A
T1806033A
T1601257A
T1706063A
T1606061A
T1806088A
T1506075A
T1705025A
T1605126A
T1805042A
T1701057A
T1801107A
T1803083A
T1803053A
T1803082A
M1807113A
M1807127A
M1802136A
W1802139A
M1807126A
M1807123A
W1808066A
T1801182A
M1807122A
T1806069A
M1803086A
T1805097A
W1803087A
W1804104A
M1804103A
T1607019A
W1808067A
M1802138A
W1802137A
W1808069A
M1804108A
W1808071A
T1805104A
M1807133A
T1807131A
W1804109A
W1804111A
M1804110A
M1802140A
W1802141A
T1805103A
T1607013A
T1805128A

T1807112A
M1807105A
M1807106A
T1807111A
T1805089A
T1805088A
M1801197A
W1801198A
W1801196A
M1801194A
W1808063A
T1807072A
M1804079A
W1804080A
T1806038A
T1806039A
T1806055A
W1802124A
W1802122A
M1802121A 
M1807094A 
T1801132A 
T1807095A 
T1805080A 
W1808055A 
T1807096A 
M1807093A 
T1809003A 
T1801161A
M1807046A
W1808076A
T1807115A
T1805066A
W1808062A 
T1805069A 
T1706093A
12M60001A
11M60041A
T1707101A
11M60181A 
T1706092A 
11M60041A 
11W10159A 
M1705132A 
T1707102A 
W1705138A 
11M10157A 
W1705131A 
T1707094A 
W1705134A 
T1606004A 
11M20036A 
M1705137A 
W1802046A
M1802045A
M1607064A
W1808060A
M1810034A
W1808017A
W1808034A
M1804031A
W1802048A
M1807043A
M1802047A
T1806116A
T1812007A 
T1801055A 
T1805021A 
W1804030A 

T1807109A
M1807135A
M1807108A
M1807104A
M1807107A
T1606158A
12M60001A
T1601046A
T1607152A
T1607195A
T1606152A
M1602057A 
W1602060A 
T1603165A 
T1605242A
T1605243A
T1705116A 
T1703037A 
T1607020A 
T1607019A 
W1708005A 
W1708004A 
W1702015A 
M1702008A 
T1705117A 
T1701031A 

T1801207A
T1807119A 
T1805096A 
T1807121A 
T1806079A
T1805106A
M1807012A
T1807019A 
T1807134A
T1803090A
W1808074A
T1801213A
W1804113A
M1804112A
W1802073A
M1802071A
T1606004A 
T1706092A 
W1705136A
11M60181A 
12M60001A 
M1705137A 
T1707101A 
M1705132A 
M1701094A 
T1707102A 
11M60041A
W1705131A 
11M10157A 
11W10159A 
T1803050A 
W1803062A 
W1808057A 
T1805062A 
T1807080A 
T1801156A 
W1804064A
M1804062A
T1807081A
W1802104A
W1808065A
W1808050A
M1802105A
W1802106A
T1804063A
M1807079A
T1801143A
T1807074A
T1801146A
T1807098A
M1802103A
T1807073A 
T1801178A
M1607064A
T1805071A
M1804071A
M1602060A
W1804072A
T1805130A
T1807151A
T1801180A
M1807100A
T1805095A
T1807116A
T1806065A
T1806080A
M1804116A

T1805062A 
W1808065A
T1801156A 
W1802104A 
M1807079A 
T1806045A 
M1802103A 
T1801143A 
T1806063A 
T1810087A 
T1810088A
T1810086A
M1807117A
12M60003A
12M20027A
12M20028A
12M10182A
T1705032A
T1707038A
12W10183A
W1702017A 
T1706004A 
T1706061A 
W1702040A 
M1702039A 
T1706016A 
W1708005A 
M1702016A 
T1705033A 
M1707047A 
T1701096A 
T1707042A 
T1701093A 
T1601021A 
T1703019A 
T1601056A 
T1601082A
W1602075A
M1602074A
T1607054A
T1605046A
T1701201A
T1603051A
W1808017A 
T1707056A 
T1601068A 
W1802019A
T1701091A
T1606020A
T1606022A
W1608072A
M1607145A
T1701200A
T1705047A
M1607064A
W1708004A 
T1703023A
M1602074A
T1706048A 
T1706049A 
T1607053A 
W1602075A 

W1802128A
T1805086A
T1807103A
T1807101A
T1805053A
T1805087A
M1802059A
M1802129A
W1808072A
W1802072A
W1808073A
M1803071A
W1803075A
T1803080A
M1803072A
T1803077A
T1803069A
T1803067A
W1803074A
T1803079A
T1803068A
M1807056A
M1807102A
M1804094A
M1804086A
W1804097A
W1804096A
T1801193A
T1806100A
T1806101A
T1806053A
T1801170A
T1801171A
W1810092A 
W1810094A
M1810091A
M1810093A
T1605253A
T1606163A
T1701152A
T1707064A
M1707062A
W1708013A
T1707065A
T1701153A
T1707063A
T1707061A
T1707060A
T1701151A
T1701150A
T1706051A 
T1607074A 
T1706050A 
T1705039A 
T1705040A 
T1607036A 
T1701022A
T1605019A 
T1607034A
T1607036A
T1801151A
T1701147A
T1805057A
T1705057A
T1806032A
T1701087A 
T1701088A 
T1701089A 
T1701090A 
T1607037A 
M1702031A 
W1702032A 
M1702033A 
W1702034A 
T1705030A 
W1708004A
T1606027A
T1507016A
T1607041A
M1702039A 
13M10083A
T1703015A
T1707048A
W1608035A
W1508007A
T1703017A
M1602055A
W1702040A 
12M60014A
W1502011A
T1706015A
T1701078A
M1602061A
M1307001A
12M60001A
T1707068A
M1504027A
W1604087A
W1704057A
W1508005A
T1807015A
T1607014A 
T1705048A
M1704056A
T1805092A
T1705009A
W1604060A
T1603122A
M1604059A
T1503038A
T1605049A
T1607018A
T1607040A 
T1606021A
T1601074A
T1601075A
T1607017A
T1701139A
T1706024A
T1801201A
M1702053A
W1702054A
T1804063A 
T1806045A 
T1806063A 
T1807109A
W1808076A
W1804059A
M1804058A
T1801199A
W1808061A
W1808059A
W1804117A
W1804080A
M1804079A
T1807095A 
T1806055A
T1801200A 
M1802074A
W1808056A 
W1802132A 
M1802131A 
T1801168A 
T1801199A
M1802090A 
W1802130A 
W1802091A 
W1808060A
M1801203A 
M1807107A
M1804116A
M1804058A
W1804059A 
T1807110A 
W1804117A 
M1810119A 
W1810120A 
W1810113A 
W1808061A 
W1808059A 
T1801195A 
W1813008A
T1803031A
T1801076A 
T1813003A 
M1804043A 
W1804036A 
M1807047A 
T1801072A 
W1808038A 
T1806025A 
W1802050A 
T1801068A 
T1803032A 
W1810049A 
T1810048A 
T1810046A 
T1801073A 
T1801067A 
W1808035A 
T1801074A 
M1813006A 
T1801075A 
T1805035A 
T1807049A
T1805029A
M1807048A
M1802049A

T1801204A
T1805095A
T1807116A
W1808064A
W1807118A
M1807117A
W1804072A
M1804071A
M1607064A
W1808017A
T1801178A
M1602030A
W1602036A
T1805071A 
T1807073A 
T1801143A
T1807074A
T1806063A 
T1807098A
M1804062A
M1802103A
T1807081A
W1802104A
T1807073A 
T1801146A
T1806080A 
T1801200A 
M1801203A 
W1808056A
T1801195A
T1801138A
T1801085A
T1801133A
T1810050A
T1810046A 
T1810060A
M1810047A
T1810054A
T1813005A
W1813009A
T1810053A
T1806024A
T1807053A
T1801157A
M1807052A
W1808039A
M1802055A
T1801085A
T1803039A
T1803037A 
T1803035A 
T1803036A 
T1803038A 
T1803040A 
W1802052A 
W1802056A 
W1810049A 
M1802051A 
T1810046A 
M1813007A 
W1807051A 
T1801139A 
T1801077A 
T1801137A 
T1801136A 
T1801135A 
T1805033A 
T1801088A 
W1804038A 
T1805032A 
M1804037A 
M1807048A 
T1801089A
T1801049A
T1801050A
M1802038A
M1802039A
W1802040A
W1802041A
M1803020A
W1803021A
W1803022A
M1803023A
W1803024A
M1803025A 
W1803026A 
W1803028A 
M1807038A 
M1807044A 
W1808029A 
W1808030A 
W1808031A 
T1806018A 
T1806019A 
T1806020A 
T1806021A 
M1804028A 
W1804029A 
T1805019A 
T1805020A 
T1805024A 
T1807040A 
T1801054A 
T1801062A 
T1807039A 
W1808033A 
W1807041A 
W1813001A 
M1813002A 
M1813004A 
T1810036A
M1810018A
M1810019A
M1810020A
M1810039A
T1810021A
T1810022A
W1810023A
T1810024A
W1810025A
W1810026A
T1810029A
T1810030A
T1810031A
T1810027A
T1810028A
T1810032A
T1810033A
W1808032A
T1810046A 
T1401088A
T1701092A
M1707047A 
T1712011A
M1704062A
W1704063A
W1606015A
T1601277A
12M30004A 
T1505053A
T1501130A
12M30001A
12M30006A
12M20003A
12M60002A
12M60005A
12M10186A
T1606003A
T1705063A
M1302023A 
M1702060A 
W1302028A 
W1702059A 
W1708004A 
T1701149A 

W1808060A
T1307006A
M1307008A
M1307007A
T1307010A
M1307011A
W1307013A
W1307014A
M1307012A
W1308008A
T1301007A
T1307001A
T1305001A
T1307009A
T1301008A
T1301009A
T1301010A
T1301011A
T1401003A
T1307003A
T1307002A
T1307004A
T1307005A
T1407006A
M1304001A
W1304002A
T1707028A
M1707031A
M1707030A
T1707029A
M1707032A
M1707033A
T1701047A
T1707022A
T1705022A
T1707021A
T1701042A
T1701043A
T1701044A
T1701045A
T1707024A
T1707023A
T1707025A
T1707026A
M1704008A
W1704009A
T1501033A 
T1501031A 
T1501032A 
T1501034A 
T1501030A 
W1501141A 
M1501140A 
M1604032A 
W1308009A 
W1308010A 
W1810023A
W1802132A 
M1802131A 
T1806080A 

M1807012A
T1805106A
T1807019A 

W1808056A
T1803082A
W1808061A
M1804116A 
W1804117A 
W1808076A
T1801161A
T1807109A
T1801168A 
M1807135A
T1803083A
W1802091A 
M1802090A 
T1807110A 
M1807113A
T1805066A
W1804059A
M1804058A
T1806080A 
T1805069A 
T1805053A 
W1808060A 
M1807107A
T1801199A
T1801200A 
T1801195A 
M1807046A 
W1802132A 
M1802131A 
M1801203A 
M1807108A 
W1802130A 
M1802074A 
M1807104A 
W1808059A 

T1807156A
T1805092A
T1806107A
M1807012A
T1805129A
T1806053A
T1607041A
T1607036A
T1607014A 
T1607032A
W1602058A
M1602055A
W1608024A
T1607042A
T1603008A 
T1607017A 
T1607034A 
T1607013A 
T1607074A 
T1607073A 
T1507008A 
T1607039A 
W1508007A
W1608035A
W1608073A
T1606152A
T1701033A
T1701032A
M1807012A 
T1806079A 
T1805106A 
T1807019A 
T1807134A 
T1803090A 
M1802071A 
W1808074A 
M1804112A 
M1804110A 
T1805103A 
M1802140A 
W1802141A 
T1805104A 
W1804111A 
W1808071A
M1807133A
T1805103A
T1807131A
T1705031A
T1707036A
T1701092A
T1607072A 

W1608034A 
T1607014A 
T1607041A
W1608024A
T1607074A 
T1603008A 
T1606061A
T1806008A
T1606158A
T1705057A

T1706092A 
W1508005A
T1806033A
T1801107A
T1601257A
T1601046A
M1807105A
T1807112A

T1801134A 

M1801220A
M1801228A 
W1801221A
M1801224A
M1801229A
W1801223A
W1801227A
W1801225A
W1801226A
M1801222A

T1806037A 
T1805038A
TW1707088A
T1806003A
12M30020A
13M30001A
T1707079A
T1806087A

T1503047A 

T1807103A 
T1807101A 
M1802059A 
W1802128A 
T1801171A 
M1807056A 
W1808072A 
M1807056A 
T1801171A 
M1807010A
M1807094A
T1807153A 
T1701150A
T1701153A
T1701151A
T1707064A 
T1701152A 
T1707060A
T1707065A
M1707062A
T1705152A
M1807048A
T1607018A
T1607020A
W1807118A
T1706017A
T1607059A
T1707040A
T1605048A
M1307012A
M1704008A
M1307012A
T1701022A
M1707047A
T1706016A
M1602030A
T1606152A
T1807034A
M1807105A
W1808067A
T1805097A
T1801182A
T1807119A
T1810052A
T1810050A
T1706051A
T1607036A
W1604021A
T1501022A
T1607014A 
T1607033A
T1607074A
M1602049A
T1605019A 
T1501010A
M1602055A
T1606010A
T1801180A
T1605006A
W1508007A
T1607041A
W1608033A
W1502015A
M1502008A 
T1605026A 
T1601012A 
T1603008A 
T1605035A 
W1608025A
T1605003A
T1607017A
T1605038A
T1601059A
T1505012A
T1505003A
M1602057A 
M1502014A
W1602060A
T1501014A
T1506020A
T1706016A
T1810043A
T1801076A
T1801067A
T1806025A
M1807048A
M1802051A
T1806025A
T1806025A
T1803035A
T1805033A
T1805032A
T1803040A
T1803035A
T1803039A
T1801085A
T1803038A
T1807053A
W1813001A
M1813002A
M1803004A

T1806027A 
T1805040A 
W1804046A 
W1802065A 
M1802064A 
T1806029A 
M1607005A 
T1607039A 
T1607040A 
W1608034A 
T1607016A 
T1607034A 
T1705130A
T1703035A 
T1503075A 
W1604085A 
W1504030A 
T1706066A 
W1704033A 
T1607073A 
11M60014A 
M1704032A
M1604084A
M1504029A
W1708005A
W1608035A
W1308001A
T1607181A
07M60020C
T1507006A
T1607194A
T1607034A
11M60014A 
T1507006A
T1607036A
T1607036A
T1806088A
T1803070A
T1806101A
T1806100A
M1802129A
T1706049A 
M1807054A
W1804109A
M1804108A
T1805103A
T1807131A
M1807133A
M1802140A
T1805042A
T1801151A
T1801109A
M1502066A
M1602055A
W1502011A

T1606006A
T1607152A
T1607152A
M1707010A
M1707010A
M1707010A
M1707010A
T1805042A 
T1606008A 
M1804071A 
T1607020A 
T1607020A 
W1808031A 
W1808031A 
M1705133A 
W1705134A 
T1705108A 
T1805062A 
T1801014A 
T1801014A 
T1801014A 
T1801014A 
T1605027A 
T1601058A 
T1601238A 
T1601238A 
T1801078A 
T1705111A 
T1805069A 
T1605250A 
T1605250A 
T1601251A 
T1701078A 
T1601141A 
T1601056A 
T1601059A 
T1801095A 
T1801189A 
W1601114A 
T1801005A 
T1601079A 
T1601079A 
T1601081A 
T1601181A 
T1601149A 
T1601283A 
T1601283A 
T1701125A 
T1701141A 
T1701141A 
T1601247A 
T1701126A 

M1807038A
M1807044A
M1807048A
T1807112A
M1810018A

T1701108A 
T1706066A 

T1701033A

M1807035A 
W1801047A 
T1805016A
M1807037A
W1808027A
W1808028A
M1801046A
T1807034A

W1802072A
W1802072A
W1802072A
W1802072A
W1802072A
M1807056A
M1807056A
M1807056A
M1807056A
M1807056A
M1807056A
M1807056A
M1807056A
M1807056A
T1805087A
T1805087A
T1805087A
T1607074A
T1607074A
T1607074A
T1607074A
T1607074A
T1805087A
T1805087A
T1805087A
M1802129A
M1802129A
M1802129A
T1807072A
T1807072A
W1803076A
W1803076A
W1803076A
W1803076A
W1803076A
W1803076A
M1807102A
M1807102A
M1807102A
M1807102A
M1802059A
M1802059A
M1802059A
M1802059A
M1802059A
M1810093A
M1810093A
W1810094A
M1810091A
M1810091A
W1810092A
M1802129A
M1802129A
M1802129A
M1802129A
M1802129A
M1802129A
T1803081A 
T1803081A 
T1803081A 
M1801197A 
T1803078A 
T1803078A 
T1803078A 
T1803078A 
M1807102A 
M1807102A 
M1807102A 
M1807102A 
M1807102A 
M1807102A 
M1807102A 
M1807102A 
M1804095A 
W1808073A 
W1808073A 
W1808073A 
W1808073A 
W1808072A 
T1806101A
T1806101A
T1806101A
T1806101A
T1806101A
M1804095A 
M1804095A 
M1804095A 
M1804095A 
W1804098A
W1804098A
W1804098A
W1804098A
W1804098A
W1804098A
M1804094A
W1802128A
W1802128A
W1802128A
W1802128A
W1802128A
W1802128A
W1802128A
M1803073A
W1803076A
T1807101A
M1802129A
';
        $gong = explode(PHP_EOL,$fashion_code);
        $gong_s = [];
        foreach ($gong as &$v){

            if(strlen($v)>2){
                $gong_s[] = trim($v);
            }
        }
        return $gong_s;


    }

    public function getTemp($data){
        $element = function ($data){


            $temp = [];
            foreach ($data as $v){
                for ($i=0;$i<$v['fashion_num'];$i++){
                    $temp[]= $v['fashion_code'].$v['fashion_size'];
                }
            }
            return $temp;
        };
        $temp = [];

        foreach ($data as $v){
            $temp[$v['id']]['container'] = $v['stock']['stock_sn'];
            $temp[$v['id']]['element'] = $element($v['stockScanStockDetail']);

        }
        return $temp;
    }

    public function In($data){

        $stock_in_model = StockInModel::find(100);

        $stock_service = ObjectHelper::getInstance(StockService::class);
        $stock_service -> stockIns($stock_in_model,$data);
    }

    public function Out($data){
        $stock_service = ObjectHelper::getInstance(StockService::class);
        $stock_service -> stockOuts($data);
    }

}


