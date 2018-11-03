<?php

namespace App\Http\Controllers\Admin\MyScan;

use App\Helper\CommonHelper;
use App\Helper\ExcelHelper;
use App\Models\Admin\FashionModel;
use App\Models\Admin\OfflineCollectModel;
use App\Models\Admin\OfflineExcelModel;
use App\Models\Admin\OfflineUserModel;
use App\Models\Admin\SellBatchModel;
use App\Models\Admin\SelllBatchPrintFashionModel;
use App\Service\Admin\MyScanService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class IndexController extends Controller
{
    //零售扫描
    public function index(Request $request){
        $search_con = $this->searchCon($request);
        $datas = MyScanService::xiaoShouFaHuoList($search_con);

        return view('admin.myscan.index',compact('datas','search_con'));
    }

    ///处理零售发货 展示页面
    public function dealFaHuo(Request $request){
        $batch_id = $request->route('batch_id');///批次的id
        //$source = $request -> get('source');///数据来源
        $batch_data = deal(MyScanService::sellBatchData($batch_id));///批次信息

        $fa_huo_data =  deal(MyScanService::faHuoData($batch_id));

        $k_d_c=MyScanService::readCache(config('app.user_config').'k_d_c');



        return view('admin.myscan.deal_fa_huo',compact('batch_data','fa_huo_data','k_d_c'));
    }

    ///增加发货单展示页面
    public function addFaHuo(Request $request){
         if($request->isMethod('post')){
            ////如果是post过来的数据 那就把发送过来的订单编号添加批次
              $order_sn = $request->post('order_sn');
              $fa_huo_time = $request->post('fa_huo_time');  //发货时间
              $note = $request->post('note');  //发货时间
              $source = $request->post('source');
              $res = MyScanService::addSellBatch($order_sn,$fa_huo_time,$note,$source);

             return response()->json($res);
         }

         $start = $request->get('start');
         $end = $request->get('end');

        $all_sell_data =  MyScanService::getAllSellData($start,$end);

        return view('admin.myscan.add_fa_huo',compact('all_sell_data','start','end'));
    }


    /**
     * 编辑一个商品的关联编码 ajax
     * @param fashion_code *alias_code
     * @return mixed
     */

    public function editAliasCode(Request $request){
             $fashion_code = $request->get('fashion_code');
             $alias_code = $request->get('alias_code');

        $res = FashionModel::where('code',$fashion_code)->update(['alias_code'=>$alias_code]);

        if(!$res){
            return response()->json(['code'=>1,'msg'=>'ok']);
        }
        $res_s = SelllBatchPrintFashionModel::where('fashion_code',$fashion_code)->update(['fashion_alias_code'=>$alias_code]);
        if(!$res_s){
            return response()->json(['code'=>1,'msg'=>'ok']);
        }
        return response()->json(['code'=>0,'msg'=>'ok']);

    }

    /**
     * 设置快递公司 ajax
     * @param  $k_d_c
     * @return mixed
     */

    public function setKDCompany(Request $request){
        $k_d_c  = $request->get('k_d_c');
        $res =  MyScanService::setKDCompany([$k_d_c],config('app.user_config').'k_d_c');
        return response()->json($res);
    }


    /**
     * 打印学生vip 展示页面
     * @param
     * @return mixed
     */

    public function printVip(Request $request){
        $batch_id = $request->get('batch_id');///批次的id

        $search_con = $this->searchCon($request);


        $batch_data = deal(MyScanService::sellBatchData($batch_id));///批次信息
        ///

        $will_print_data = MyScanService::willPrintData($batch_id,$search_con,$batch_data);



        return view('admin.myscan.print_vip',compact('batch_data','will_print_data','search_con'));


}

/**
 * 打印所有的学生vip
 * @param
 * @return mixed
 */

public function printAllList(Request $request){
    $batch_id = $request->get('batch_id');///批次的id
    $print_type = $request->get('print_type');///打印的类型 分为vip 打印  快递打印  产品清单打印
    $search_con = $this->searchCon($request);//搜索的条件
    $batch_source = $request->get('batch_source');///数据来源
    switch ($print_type){
        case "vip":
            $print_data = MyScanService::printListVip($batch_id,$search_con);
            break;
        case 'A4_vip':
            $print_data = MyScanService::printListVipWithA4($batch_id,$search_con,$batch_source);
            break;
    }

    return response()->json(msg(0,'ok',$print_data));
}

/**
 * 打印已选择的vip
 * @param
 * @return mixed
 */

public function printList(Request $request){

    $batch_id = $request->post('batch_id');///批次的id
    $print_type = $request->post('print_type');///打印的类型 分为vip 打印  快递打印  产品清单打印
    $one_code = $request->post('one_code');///所选择的学校条码
    $batch_source = $request->post('batch_source');///所选择的学校条码
    $search_con = $this->searchCon($request);

    switch ($print_type){
        case "vip":
            $print_data = MyScanService::printListVip($batch_id,$search_con,$one_code);
            break;
        case 'fashion_list':
            $print_data = MyScanService::printListFashionList($batch_id,$batch_source,$one_code);
            break;
        case 'k_d_list':
            $print_data = MyScanService::printListKD($batch_id,$batch_source,$one_code);
            break;
        case 'A4_vip':
            $print_data = MyScanService::printListVipWithA4($batch_id,$search_con,$batch_source,$one_code);
            break;

    }
    return response()->json(msg(0,'ok',$print_data));
}

/**
 * 搜索条件
 * @param
 * @return mixed
 */

    public function searchCon($request){
        $key_word = $request->get('key_word');
        return ['key_word' => $key_word];
}

/**
 * 正式扫描页面
 * @param
 * @return mixed
 */

protected function scaning(Request $request){
    $batch_id = $request->get('batch_id');///批次的id

    $user = 'admin';
    $batch_data = deal(MyScanService::sellBatchData($batch_id));///批次信息
    ///
    $scaning_data = MyScanService::scaningCollection($batch_id);

    $scan_model = $this->getScanModel();///扫描方式   分为简化 详细
    $send_model = $this->getSendModel();///扫描方式   分


    return view('admin.myscan.scaning',compact('batch_data','scaning_data','scan_model',
        'send_model','user'));

}


    /**
     * AJAX  扫描页面扫描包裹列表
     * @param
     * @return mixed
     */

    public function scanList(Request $request){
        $batch_id = $request->get('batch_id');///批次的id
        $batch_source = $request->get('batch_source');///所选择的学校条码

        $data = MyScanService::scanList($batch_id,$batch_source);

        return response()->json(msg(0,'ok',$data));
    }

    /**
     * 扫描方式 分为简化和详细
     * @param
     * @return mixed
     */

    protected function getScanModel(){
        return '详细';
    }

    /**
     * 快递获取方式
     * @param
     * @return mixed
     */
    protected function getSendModel(){
       return 'new';
    }


    /**
     * 一个包裹的详情
     * @param
     * @return mixed
     */

    public function packageDetail(Request $request ){
        $batch_id = $request->get('batch_id');///批次的id

        $one_code = $request->get('one_code');


        $package_detail = MyScanService::packageDetail($batch_id,$one_code);


        return view('admin.myscan.package_detail',compact('package_detail'));

    }

    /**
     * 预先取快递
     * @param
     * @return mixed
     */

    public function prevKD(){


    }

    /**
     * 清空一个已经扫描的包裹
     * @param
     * @return mixed
     */
    public function clearPackage(Request $request){
        $batch_id = $request->get('batch_id');///批次的id

        $one_code = $request->get('one_code');


        $res =  MyScanService::clearPackage($batch_id,$one_code);

        return response()->json($res);
    }

    /**
     * 打印 清单 实时的 扫描数据
     * @param
     * @return mixed
     */

    public function printListScan(Request $request){
        $batch_id = $request->get('batch_id');///批次的id

        $one_code = $request->get('one_code');
        $type = $request->get('type');///fashion_list  数据来源模式
        $batch_source = $request->get('batch_source');///批次来源


        $print_data = MyScanService::printListScan($batch_id,$one_code,$type,$batch_source);

        return response()->json(msg(0,'ok',$print_data));

    }

    /**
     * 扫描结束 配送出库页面
     * @param
     *
     * @return mixed
     */
    public function endScan(Request $request){
        $batch_id = $request->get('batch_id');///批次的id

        $batch_data = deal(MyScanService::sellBatchData($batch_id));///批次信息

        $need_chu_ku = MyScanService::endScan($batch_id,$batch_data['batch_source']);

        return view('admin.myscan.end_scan',compact('batch_data','need_chu_ku'));
    }

/**
 * 确认出库页面
 * @param
 * @return mixed
 */
    public function goOut(Request $request){
        $batch_id = $request->get('batch_id');
        $wu_liu = $request->get('wu_liu');
        $operate = $request->get('operate');
        $out_time = $request->get('out_time');
        $res = SellBatchModel::find($batch_id)->update(['out_status'=>'2','out_time'=>$out_time,'operate'=>$operate,'wu_liu'=>$wu_liu]);
        if(!$res){
            return response()->json(msg(1,'false'));
        }
        return response()->json(msg(0,'ok'));

    }


    /**
     *导出理货单
     * @param
     * @return mixed
     */

    public function exportLiHuo(Request $request){
        $batch_id = $request->get('batch_id');///批次的id
        $fa_huo_data =  MyScanService::exportLiHuo($batch_id);
        $headArr = ['产品名称','产品编码','简写编码','尺码','数量'];
        $sheet_title=['理货单'];
        ExcelHelper::exports($fa_huo_data,$headArr,'理货单',$sheet_title);
    }


    /**
     * 导出发货的已经有快递信息的 数据: 姓名 年级 班级 性别 条形码 快递号码 快递公司
     * @param
     * @return mixed
     */

    public function exportKuaiDi(Request $request){
        $batch_id = $request->get('batch_id');///批次的id
        //$will_print_data = MyScanService::willPrintData($batch_id);
        $batch_source = $request ->get('batch_source');
        $export_kuai_di_data = MyScanService::exportKuaiDiData($batch_id,$batch_source);
        $headArr = ['姓名','快递','快递公司','学校','年级','班级','性别','条形码','发货产品','缺货产品','换货产品'];
        $sheet_title=['有快递单的','无快递单的'];

        ExcelHelper::exports($export_kuai_di_data,$headArr,'快递信息',$sheet_title,['I','J','K'],[['I','30'],['J','30'],['K','30'],['H','30'],['B','30'],['A','20']]);

    }

    /**
     * 导出缺货信息 数据:姓名 年级 班级 条形码 缺货产品
     * @param
     * @return mixed
     */
    public function exportProInfo(Request $request){
        $batch_id = $request->get('batch_id');///批次的id
        $batch_source = $request ->get('batch_source');

        $export_kuai_di_data = MyScanService::exportProInfo($batch_id,$batch_source);
        $headArr = ['姓名','学校','年级','班级','性别','条形码','发货产品','缺货产品','换货产品'];
        $sheet_title=['所有的扫描','扫描正常的','缺货的扫描','换货的扫描'];

        ExcelHelper::exports($export_kuai_di_data,$headArr,'扫描数据',$sheet_title,['G','H','I'],[['G','30'],['H','30'],['I','30'],['F','30']]);

    }


    /**
     * 导出出库产品清单 数据:产品名称 产品编码 尺码 数量
     * @param
     * @return mixed
     */

    public function exportOutList(Request $request){
        $batch_id = $request->get('batch_id');///批次的id
         $export_out_list_data = MyScanService::exportOutList($batch_id);
        $headArr = ['产品编码','尺码','数量'];
        $sheet_title=['出库数据汇总'];

        ExcelHelper::exports($export_out_list_data,$headArr,'出库数据汇总',$sheet_title);
    }

    /**
     * 编辑一个人的收货地址
     * @param
     * @return mixed
     */

    public function editAddress(Request $request){

        if($request->method()=='POST'){
            $address_id = $request->post('address_id');
            $name = $request->post('name');
            $tel = $request->post('tel');
            $province = $request->post('province');
            $city = $request->post('city');
            $area = $request->post('area');
            $detail = $request->post('detail');
           $batch_source = $request -> post('source');

            $res = MyScanService::editAddress($address_id,$name,$tel,$province,$city,$area,$detail,$batch_source); ///编辑收货地址

            return response()->json(msg(0,'ok'));
        }

         $batch_id = $request->get('batch_id');///批次的id
         $one_code = $request->get('one_code');///要编辑的学生的条码
         $batch_source = $request -> get('source');///批次来源

         $address_info  = MyScanService::getAddressInfo($batch_id,$one_code,$batch_source); ///获取收货地址

        return view('admin.myscan.edit_address',compact('address_info','batch_source'));
    }

    /**
     *修改产品编码 为空 针对领带领结等无尺码的产品
     * @param
     * @return mixed
     */
      public function editFashionSize(Request $request){

          if($request->method()=='POST'){
              $fashion_code=$request -> get('fashion_code');
              $fashion_size = $request -> get('size');
              $batch_id = $request -> get('batch_id');
              $res =MyScanService::editFashionSize($batch_id,$fashion_code,$fashion_size);

              return response()->json($res);
          }

          return view('admin.myscan.edit_fashion_size');
     }


     /**
      * 删除一个批次 及以下所包含的所有产品
      * @param
      * @return mixed
      */
    public function delBatch(Request $request){
        $batch_id = $request -> get('id');
        $res =MyScanService::delBatch($batch_id);

        return response()->json($res);

     }

     /**
      * 从线下的数据 导入 到发货单
      * @param
      * @return mixed
      */
     public function importOffline(Request $request){

            if($request->method()=='POST'){
                $res = CommonHelper::upload($request,'file');//上传文件  获取上传地址
                $note = $request->get('note');
                if($res['code']==0){
                    $file_path = $res['data']['url'];//文件路径
                    $ext = $res['data']['ext'];//文件后缀
                     try{
                         $excel_data = ExcelHelper::import($file_path,$ext);//表格原始数据

                         $excel_type =  MyScanService::judgeExcel($excel_data);//去判断excel的类型  根据类型的不同来处理表格

                             $excel_data = MyScanService::dealRawExcelData($excel_data,$excel_type);///对原始数据进行处理得到的结果



                         ///存入数据库
                         $res = MyScanService::saveExcelData($res['data']['or_file_name'],$excel_data,$note,$excel_type);////对处理完毕的数据保存起来
                         if($res['code']==0){
                             return redirect()->route('admin.myscan.index.import_offline');///重定向到订单展示页面
                         }


                     }catch (\Exception $e){
                        abort(404,$e->getMessage());
                     }
                }else{
                    abort('404',$res['msg']);
                }

            }
         $search_con = $this->searchCon($request);

         $data =  OfflineExcelModel::where(function ($query)use($search_con){
             if($search_con['key_word']){
                 $query->where('uid','like','%'.$search_con['key_word'].'%')->orwhere('excel_name','like','%'.$search_con['key_word'].'%');
             }
         })->orderBy('created_at','desc')->paginate(config('app.page_size'));

         $links = $data->links('admin.links');

         return view('admin.myscan.import_offline',compact('data','search_con','links'));
     }

     /**
      * 导入的发货数据展示
      * @param
      * @return mixed
      */

     public function OfflineDisplay(Request $request){
         $search_con = $this->searchCon($request);

         $data =  OfflineExcelModel::where(function ($query)use($search_con){
             if($search_con['key_word']){
                 $query->where('uid','like','%'.$search_con['key_word'].'%')->orwhere('excel_name','like','%'.$search_con['key_word'].'%');
             }
         })->orderBy('created_at','desc')->paginate(1);

        $links = $data->links('admin.links');
        return view('admin.myscan.offline_display',compact('data','search_con','links'));

     }

     /**
      * 发货数据详细展示
      * @param
      * @return mixed
      */

     public function OfflineDetail(Request $request){
         $uid = $request -> route('uid');
         $search_con = $this->searchCon($request);

         $data =  OfflineUserModel::formatGet($uid,$search_con);
           //dd($data['data']->toArray());
         return view('admin.myscan.Offline_detail',compact('data','search_con'));

     }
     
     /**
      * 导入的数据转换为批次发货数据
      * @param 
      * @return mixed
      */

     public function convertOfflineData(Request $request){
            $excel_uid = $request -> get('uid');
            $note  = $request -> get('note');
            $fa_huo_time  = $request -> get('fa_huo_time');
            $source  = $request -> get('source');
             try{
               $res =  MyScanService::convertOfflineData($excel_uid,$note,$fa_huo_time['date'],$source);///去转化

                 if($res['code']==0){
                     return redirect()->route('admin.myscan.index.index');///重定向到发货首页
                 }else{
                     abort(404,$res['msg']);
                 }
             }catch (\Exception $e){
                 abort(404,$e->getMessage());
             }


     }


}
