<?php
namespace App\Http\Controllers\Admin\MyScan;

use App\Helper\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\FashionModel;
use App\Models\Admin\ScanCnDetailsModel;
use App\Models\Admin\ScanCnModel;
use App\Models\Admin\SellBatchModel;
use App\Service\Admin\MyScanService;
use Illuminate\Http\Request;
use XiangYu2038\Wish\XY;

class BindBoxController extends Controller
{
    public function index(Request $request){

        $batch_id = $request->get('batch_id');///批次的id
        ///
        $need =  [
            'scanCn'=>[
                'id','order_batch_id','code','scanDetails'=>[
                    'id','scan_cn_id','code','student'=>['id','student_code','studentDetails'=>[
                        'scan_student_id'
                    ]]
                ]
            ],'sellBatchPrintEnd'=>['sell_batch_id']
        ];
         $user = 'main';
        $batch_model = SellBatchModel::where('id',$batch_id)->withxy($need)->first();

        $batch_data = $batch_model -> formatInfo();///批次的信息
        $sell_batch_data =  XY::with($batch_model)->wish('scanCn')->add('element')->only('id','code','element')->except('scanDetails')->get();
        $box_data =  $sell_batch_data->scanCn;

        $sell_batch_statistics = XY::with($batch_model)->add('box_num','scan_box_num','package_num','scan_package_num','fashion_num','scan_fashion_num')->only('box_num','scan_box_num','package_num','scan_package_num')->except('scanCn')->get();

        return view('admin.myscan.bind_box.index',compact('batch_data','box_data','sell_batch_statistics','user'));
    }


    /**
     * 添加一个箱子
     * @param
     * @return mixed
     */
    public  function addBox(Request $request){
        $batch_id = $request -> get('batch_id');
        $box_num = $request -> get('num');

        $box_model = new ScanCnModel();

       $res = $box_model -> addBox($batch_id,$box_num);
        return response()->json($res);

    }
    /**
     * 删除空箱子
     * @param 
     * @return mixed
     */
    public function delBox(Request $request){
        $box_id = $request -> get('box_id');
        $res = ScanCnModel::whereIn('id',$box_id)->delete();

        if(!$res){
            return response()->json(msg(1,'false'));
        }else{
            return response()->json(msg(0,'ok'));
        }
    }

    /**
     * 获取箱子的信息 准备打印箱号
     * @param
     * @return mixed
     */
    public function boxInfo(Request $request){
        $batch_id = $request -> post('batch_id');
        $box_id = $request -> post('box_id');
        $custom = $request -> post('custom_name');///客户名称
        $type = $request -> post('type');///发货类型
        ///
        $box_data=  ScanCnModel::whereIn('id',$box_id)->get();

        $batch_sn = SellBatchModel::find($batch_id)->batch_sn;

        $data  = [];
        foreach ($box_data as $box){
            $data[] = $box -> getBoxInfo($custom,$type,$batch_sn);
        }
        return response()->json(msg(0,'ok',$data ));

    }
    
    /**
     * 箱子的内容 打印箱贴 准备箱贴
     * @param 
     * @return mixed
     */
    public function boxContent(Request $request){
        $batch_id = $request -> post('batch_id');

        $box_id = $request -> post('box_id');
        $custom = $request -> post('custom_name');///客户名称
        $data_type = $request -> post('data_type');///发货类型

        ///
        $batch_model = SellBatchModel::where('id',$batch_id)->withxy(['batchDetails'=>['sell_batch_id','fashion_code']])->first();

        $this -> source = $batch_model -> source;
         //$this -> data_source = $batch_model -> source;//////数据来源
        $batch_data =  XY::with($batch_model)->only('id','batch_sn','created_at','note','out_status')->wish('batchDetails')->only('fashion_code')->get();

        $box_title_data = $batch_model->boxCommonInfo('',$batch_data,$custom);////获取单子的头部通用信息
        ///
        ///
        //$box_id = [47];

        if($batch_model -> source == '线上零售'){
            $scan_data = ScanCnModel::whereIn('id',$box_id)->with('scanDetails.student.studentDetails') -> withxy(['scanDetails.student'=>['*','batchPrintEnd'=>['id','one_code','sell_order_sn','sellOrder'=>['order_sn','student_info_id','bookingStudent'=>['id','sex','school_id','grade_id','class_id','name','school'=>['id','name'],'grade'=>['id','name'],'gradeClass'=>['id','name']]]]]])->get();///获取扫描的数据

        }elseif($batch_model -> source == '线下导入'){

            $scan_data = ScanCnModel::whereIn('id',$box_id)->with('scanDetails.student.studentDetails')->get();///获取扫描的数据

        }elseif($batch_model -> source == '微信预售'){
            $scan_data = ScanCnModel::whereIn('id',$box_id)->with('scanDetails.student.studentDetails') -> withxy(['scanDetails.student'=>['*','batchPrintEnd'=>['id','one_code','sell_order_sn','sellOrder'=>['order_sn','student_info_id','bookingStudent'=>['id','sex','school_id','grade_id','class_id','name','school'=>['id','name'],'grade'=>['id','name'],'gradeClass'=>['id','name']]]]]])->get();///获取扫描的数据
        }

        $all_html = [];
        foreach ($scan_data as $v){
            $all_html[] = $this ->boxStick($v,$box_title_data,$data_type,$batch_data);
        }

        return response()->json(msg(0,'ok',$all_html ));


    }
    
    
    /**
     * 箱贴
     * @param 
     * @return mixed
     */
    public function boxStick($box,$box_title_data,$data_type,$batch_data){
         $title = $box ->getBoxStickTitle($box_title_data,$batch_data);

         $fashions = $box -> getPackageFashion();///这个箱子 拥有的产品
          ///格式化 这个数据
        foreach ($fashions as &$fashion){
            $fashion = ObjectHelper::getInstance(FashionModel::class) -> fashionCode($fashion->fashion_code);
        }
        unset($fashion);
        $free_fashions = $box -> getFreeFashion();///这个箱子 拥有的产品
        foreach ($free_fashions as &$fashion){
            $fashion = ObjectHelper::getInstance(FashionModel::class) -> fashionCode($fashion->code);
        }
        unset($fashion);

        $all = array_merge($fashions,$free_fashions);



        $fashions = ObjectHelper::getInstance(FashionModel::class)-> assortFashionNoSize($all);
        $fashions = ObjectHelper::getInstance(FashionModel::class) ->addFashionName($fashions);
        $total_fashion = 0;
        foreach ($fashions as $v){
            $total_fashion = $total_fashion + $v['fashion_num'];
        }

        $students = $box -> getStudents();

        foreach ($students as &$student){
               if($student){
                   $student = $student -> getstudent($this -> source);///提取学生信息
               }
        }
            unset($student);

    $boy_num = 0;
    $girl_num = 0;
     $w_z = 0;
    foreach ($students as $v){
       if($v['sex'] == '男'){
           $boy_num = $boy_num + 1;
       }else{
           if($v['sex'] == '女'){
               $girl_num = $girl_num +1;
           }else{
               $w_z = $w_z =$w_z +1;
           }
       }


    }
    $total = $boy_num + $girl_num +$w_z;

        $items  = [];
        $items['title'] = $title;
        $items['fashion_detail']['total'] = $total_fashion;
        $items['fashion_detail']['fashions'] = $fashions;
        $items['student_detail']['student'] = $students;
        $items['student_detail']['static']['boy_num'] = $boy_num;
        $items['student_detail']['static']['girl_num'] = $girl_num;
        $items['student_detail']['static']['total'] = $total;



        return view('admin.myscan.bind_box.box_stick',compact('items'))->render();
    }
/**
 * 箱子的详情 准备展示
 * @param
 * @return mixed
 */
public function boxDetail(Request $request){
    $box_id = $request -> get('box_id');
    $batch_id = $request -> get('batch_id');
//    $batch_id = 33;
//    $box_id = 47;
    $batch_model = SellBatchModel::where('id',$batch_id)->first();

   if($batch_model -> source == '线上零售'){
       ////构造xy
       $xy = ['scanDetails.student'=>['*','batchPrintEnd'=>['id','one_code','sell_order_sn','sellOrder'=>['order_sn','student_info_id','sellStudent'=>['id','sex','school_id','grade_id','class_id','name','school'=>['id','name'],'grade'=>['id','name'],'gradeClass'=>['id','name']]],'sellBatchPrintFashions'=>['one_code','fashion_name','fashion_size','fashion_num','fashion_code'],'scanStudent'=>['student_code','id','studentDetails'=>['scan_student_id','fashion_code']],'scanError'=>['*']]]];
   }elseif($batch_model -> source == '微信预售'){
       $xy = ['scanDetails.student'=>['*','batchPrintEnd'=>['id','one_code','sell_order_sn','sellOrder'=>['order_sn','student_info_id','bookingStudent'=>['id','sex','school_id','grade_id','class_id','name','school'=>['id','name'],'grade'=>['id','name'],'gradeClass'=>['id','name']]],'sellBatchPrintFashions'=>['one_code','fashion_name','fashion_size','fashion_num','fashion_code'],'scanStudent'=>['student_code','id','studentDetails'=>['scan_student_id','fashion_code']],'scanError'=>['*']]]];
   }elseif($batch_model -> source == '线下导入'){
       $xy = ['scanDetails.student'=>['*','batchPrintEnd'=>['id','one_code','sell_order_sn','offlineUser'=>['order_sn','id','sex','name','school','grade','class'],'sellBatchPrintFashions'=>['one_code','fashion_name','fashion_size','fashion_num','fashion_code'],'scanStudent'=>['student_code','id','studentDetails'=>['scan_student_id','fashion_code']],'scanError'=>['*']]]];
   }

    $box_model = ScanCnModel::where('id',$box_id)->with('scanDetails.student.studentDetails') -> withxy($xy)->first();


   $myscan_service = ObjectHelper::getInstance(MyScanService::class);

   $items = $myscan_service -> getPackagesAndFashions($box_model,$batch_model -> source);



    return view('admin.myscan.bind_box.box_detail',compact('items'));
}

/**
 * 批量扫描箱子
 * @param
 * @return mixed
 */

public function batchScanBox(Request $request){
   $box_id = $request -> get('box_id');
    $unit = $request -> get('unit');
    $num = $request -> get('num');
    $array=[];
    for ($x=0; $x<$num; $x++) {
        $array[]=$unit;
    }

    $box_data=ScanCnModel::find($box_id);

    $collection_id=$box_data->id;

    $data = $this->getUnitsSaveDataWithBox($collection_id,$array);

    ScanCnDetailsModel::Insert($data);

    return response()->json(msg(1,'ok'));
}
    public function getUnitsSaveDataWithBox($collection_id,$units){
        $function=function($code,$collection_id,$units_type){
            $array=[];
            $array['scan_cn_id']=$collection_id;
            $array['code']=$code;
            $array['type']=$units_type;
            $array['created_at']=date('Y-m-d H:i:s');
            $array['updated_at']=date('Y-m-d H:i:s');
            return $array;
        };

        $units_type=$this->getUnitType($units);///得到一个单位的类型

        $array=[];
        foreach ($units as $v){
            /////判断单位是什么
            $array[]=$function($v,$collection_id,$units_type);
        }

        return $array;
    }
    public function getUnitType($units){
        foreach ($units as $v){
            $str=substr($v,0,2);
            if($str=='XX'&&strlen($v)==12){
                return '2';//包裹
            }else{
                return 1;///商品
            }
        }
    }

    /**
     * 清空一个箱子
     * @param
     * @return mixed
     */
    public function clearBox(Request $request){
        $box_id = $request -> get('box_id');

        $box_detail_model = ScanCnDetailsModel::where('scan_cn_id',$box_id)->delete();
       if(!$box_detail_model){
           return response()->json(msg(1,'已经是空箱了'));
       }

        return response()->json(msg(0,'成功'));

    }

    /**
     * 移箱操作
     * @param
     * @return mixed
     */
    public function moveBox(Request $request){
        $fashion_code = $request -> get('code');
        $box_id = $request -> get('box_id');
        $need_box_id = $request -> get('need_box_id');
        /////首先删除 这个箱子的这个编码

        $res =  ScanCnDetailsModel::where('scan_cn_id',$box_id)->where('code',$fashion_code)->delete();
       if(!$res){
           return response()->json(msg(1,'移箱失败,目前仅支持移包裹'));
       }


        $str=substr($fashion_code,0,2);///前两位
        if($str == 'XX'&&strlen($fashion_code == 12)){
            ///说明是包裹集合
            $type=2;
        }else{
            $type=1;
        }


        $created_array = [];
        $created_array['scan_cn_id'] = $need_box_id;
        $created_array['code'] = $fashion_code;
        $created_array['type'] = $type;

        $res_save = ScanCnDetailsModel::create($created_array);

        if(!$res_save){
            return response()->json(msg(1,'移箱失败'));
        }else{
            return response()->json(msg(0,'移箱成功'));
        }


    }

}