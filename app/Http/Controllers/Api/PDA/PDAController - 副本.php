<?php

namespace App\Http\Controllers\Api\PDA;

use App\Helper\ObjectHelper;
use App\Models\Admin\StockBoxModel;
use App\Models\Admin\StockCountDetailModel;
use App\Models\Admin\StockCountModel;
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
       dd(__LINE__);
    }
    /**
     * @api {post} /api/pda/stockCountList 盘点单列表
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

        })->where('status','开启')->orderBy('created_at','desc')->paginate(15);

        $stock_count_detail = this($stock_count_model,['id','stock_count_sn','stock_name','title','operate','status','created_at']);
        $paginate = paginate($stock_count_model,$stock_count_detail);



        return response()->json(msg(0,'ok',$paginate));
    }


    /**
     * @api {post} /api/pda/submitScan 盘点数据提交
     * @apiVersion 1.0.0
     * @apiName submitScan
     * @apiGroup group_pda
     * @apiPermission 所有用户
     *
     * @apiParam {string} type 类型 1代表盘点 2代表出库
     * @apiParam {string} stock_count_id 若为类型1  必须提供盘点单的id
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
        $_POST =  Cache::store('file')->get('dada');

        $type =  $_POST['type'];
        $data =  $_POST['data'];
        //Cache::store('file')->put('dada',$_POST,1000);

       // $type =  2;

      //  TestModel::create(['json'=>'heihei']);

       // Cache::store('file')->put('dada',$_POST,1000);
     //   $a = Cache::store('file')->get('dada');
       // $data = $a['data'];
        //$type = $a['type'];


        $data = $this -> heihei($data);

        $data = [
            [
                'container'=>'4-SA01Z01C01',
                'element'=>['07M10008A180','07M10008A180','07M10008A190','CKXH201896c728']
            ],
            [
                'container'=>'CKXH201896c728',
                'element'=>['07M10008A180','07M10008A180','07M10008A180']
            ]
        ];
        $data = [
            [
                'container'=>'4-SA01Z01C01',
                'element'=>['07M10008A180','07M10008A180','CKXH201896c728']
            ]
        ];

        $stock_service = ObjectHelper::getInstance(StockService::class);
        if($type == 1){
            /////这是盘点动作
            $stock_count_id = $_POST['stock_count_id'];
            $stock_count_id = 1;
            ////first blood  写入一条盘点记录
           // $stock_count_id = 1;
            \DB::beginTransaction();
            try{
                TestModel::create(['json'=>json_encode($data)]);
                $stock_service->addRecord($stock_count_id,$data,'盘点');
                \DB::commit();
            }catch (\Exception $e){

                \DB::rollBack();
              return response()->json(msg(1,$e->getMessage()));
          }

         }elseif($type == 2){
            /////这是出库动作  首先记录出库记录

            $stock_out = ObjectHelper::getInstance(StockOutModel::class);
            \DB::beginTransaction();
            try{
                $res = $stock_out -> add($data);///生成一个出库单

                $res = $stock_service -> addRecord($res->id,$data,'出库');
                \DB::commit();
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->json(msg(1,$e->getMessage()));
            }
        }

          $return = $stock_service -> formatScanData($data);

          return response()->json(msg(0,'ok',$return));
    }


    public function heihei($data){
        $data = json_decode($data,true);
        foreach ($data as &$v){
            $v['element'] = json_decode($v['element'],true);
        }
        unset($v);
        return $data;
    }




}


