<?php

namespace App\Http\Controllers\Api\Storage;
use App\Helper\ObjectHelper;
use App\Http\Controllers\Api\Controller;
use App\Models\Admin\BoxModel;
use App\Models\Admin\StockBoxModel;
use http\Exception;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Exception\FatalErrorException;


class BoxManageController extends Controller
{
    /**
     * @apiDefine group_so 仓储管理模块
     */

    /**
     * @api {get} /api/boxList 箱子列表 (id字段改变 12.10)
     * @apiVersion 2.0.0
     * @apiName boxList
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiDescription 箱子列表api
     *
     * @apiSampleRequest  /api/boxList
     *
     * @apiSuccess (返回值) {int}  id 箱子的id
     * @apiSuccess (返回值) {string} box_sn  箱子的编号
     * @apiSuccess (返回值) {string} created_at  创建时间
     *
     * @apiSuccessExample {json} 成功示例:
     * {"state":"1","msg":"ok","data":{"current_page":1,"prev_page_url":null,"next_page_url":null,"total":10,"data":[{"id":3,"box_sn":"4H-SH-A01-Z01-C01","created_at":{"date":"2018-11-27 14:11:10.000000","timezone_type":3,"timezone":"PRC"}},{"id":4,"box_sn":"4H-SH-A01-Z01-C02","created_at":{"date":"2018-11-27 14:11:10.000000","timezone_type":3,"timezone":"PRC"}},{"id":5,"box_sn":"B_c0f4","created_at":{"date":"2018-11-27 14:11:10.000000","timezone_type":3,"timezone":"PRC"}},{"id":6,"box_sn":"B_3994","created_at":{"date":"2018-11-27 16:00:13.000000","timezone_type":3,"timezone":"PRC"}},{"id":7,"box_sn":"B_5f5d","created_at":{"date":"2018-11-27 16:16:26.000000","timezone_type":3,"timezone":"PRC"}},{"id":8,"box_sn":"B_6ad4","created_at":{"date":"2018-12-03 09:28:14.000000","timezone_type":3,"timezone":"PRC"}},{"id":9,"box_sn":"B_326a","created_at":{"date":"2018-12-03 09:31:21.000000","timezone_type":3,"timezone":"PRC"}},{"id":10,"box_sn":"B_4b7f","created_at":{"date":"2018-12-03 09:32:18.000000","timezone_type":3,"timezone":"PRC"}},{"id":11,"box_sn":"B_0239","created_at":{"date":"2018-12-03 09:32:23.000000","timezone_type":3,"timezone":"PRC"}},{"id":12,"box_sn":"B_984a","created_at":{"date":"2018-12-10 08:45:42.000000","timezone_type":3,"timezone":"PRC"}}]}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function boxList(){

        $box_model = BoxModel::paginate(config('app.page_size'));

        $box_list = this($box_model,['id','box_sn','created_at']);

        return response()->json(api_msg('1','ok',paginate($box_model,$box_list)));

    }

    /**
     * @api {get} /api/addBox 添加箱子
     * @apiVersion 1.0.0
     * @apiName addBox
     * @apiGroup group_so
     * @apiPermission 所有的人
     *
     * @apiParam {int}  box_num 需要添加的箱子数量
     * @apiDescription 添加箱子api
     *
     * @apiSampleRequest  /api/addBox
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":null}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":1,"msg":"\u8bf7\u8f93\u5165\u6b63\u786e\u7684\u7bb1\u5b50\u6570\u91cf","data":null}
     */

    public function addBox(Request $request){
        $num = $request -> get('box_num');
        try{
            $res = ObjectHelper::getInstance(BoxModel::class)->add($num);
            if($res){
                return response()->json(msg(0,'ok'));
            }

        }catch (\Exception $e){
            return response()->json(api(1,$e->getMessage()));
        }

    }


    /**
     * @api {get} /api/delBox 删除一个箱子
     * @apiVersion 1.0.0
     * @apiName delBox
     * @apiGroup group_so
     * @apiPermission 所有用户
     *
     * @apiParam {int} box_id 箱子的id
     * @apiDescription 删除箱子api
     *
     * @apiSampleRequest   /api/delBox
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":1,"msg":"\u4e0d\u5b58\u5728\u7684\u7bb1\u5b50","data":null}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":1,"msg":"\u4e0d\u5b58\u5728\u7684\u7bb1\u5b50","data":null}
     */
    public function delBox(Request $request){
       $box_id = $request -> get('box_id');
       try{
           $box_model =  BoxModel::where('id',$box_id)->first();
           if(!$box_model){
               return response()->json(msg(1,'不存在的箱子'));
           }

           $res = $box_model->delete();
           if($res){
               return response()->json(msg(0,'ok'));
           }else{
               return response()->json(msg(1,'删除箱子失败'));
           }

       } catch (\Exception $e){
           return response()->json(msg(1,$e->getMessage()));
       }



    }





}
