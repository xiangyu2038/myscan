<?php

namespace App\Http\Controllers\Admin\Tool;

use App\Admin\Models\MenuModel;
use App\Admin\Models\StoreFashionModel;
use App\Admin\Models\TestModel;
use App\Helper\CommonHelper;
use App\Helper\ExcelHelper;
use App\Service\Admin\TestService;
use App\Service\Admin\ToolService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    /**
     * 数据转换 (只是尺码统计)
     * @param 
     * @return mixed
     */
    public function index(Request $request){

        if($request->isMethod('post')){
            $res = CommonHelper::upload($request,'file');//上传文件  获取上传地址
            if($res['code']==0){
                $file_path = $res['data']['url'];
                $ext = $res['data']['ext'];
               $data =  ExcelHelper::import($file_path,$ext);///导入的表格数据
               $temp = [];
                foreach ($data as $v){
                   $temp[]= ToolService::dealData($v);////处理完毕的数据
               }

               $sheet_title = ['裤子','裙子','其他'];
               $head_arr=['姓名','产品名称','编码','尺码','数量'];
                //////////////执行导出
                ExcelHelper::exports($temp[0],$head_arr,$title='导出表格',$sheet_title);

            }else{
                abort('404',$res['msg']);
            }
        }
        return view('admin.tool.index');
    }

    /**
     * 数据转换 库位统计
     * @param 
     * @return mixed
     */
    public function convertData(Request $request){

        if($request->isMethod('post')){

            $res = CommonHelper::upload($request,'file');//上传文件  获取上传地址
            if($res['code']==0){
                $file_path = $res['data']['url'];
                $ext = $res['data']['ext'];
                $data =  ExcelHelper::import($file_path,$ext);///导入的表格数据
                $data =  ToolService::dealDataNew($data);////处理完毕的数据
                $sheet_title = ['裤子','裙子','其他'];
                $head_arr=['姓名','产品名称','标准编码','特殊编码','尺码','数量','货架','需要拣货编码'];
                //////////////执行导出
                ExcelHelper::exports($data,$head_arr,$title='导出表格',$sheet_title);

            }else{
                dd($res['msg']);
            }
        }
        return view('admin.tool.convert_data');
    }
}
