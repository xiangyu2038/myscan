<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 10:24
 */
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class ScanStudentModel extends  BaseModel{
    protected $table='ts_scan_student';

    protected $guarded=[];
public function studentDetails(){
    return $this->hasMany('App\Models\Admin\ScanStudentDetailsModel','scan_student_id');
}

public function scanError(){
    ///扫描异常表
    return $this->hasMany('App\Models\Admin\ScanErrorModel','scan_sn','one_code');
}
    public function batchPrintEnd(){
        return $this->hasone('\App\Models\Admin\SellBatchPrintEndModel','one_code','student_code');
    }
///一个扫描的包裹所包含的商品
protected function hasFashions($this_model){
 if(!$this_model){
     return [];
 }

   $temp = [];

   foreach ($this_model->studentDetails as $v){
       $temp[] = ScanStudentDetailsModel::formate($v);
   }

    $fashion=$this->assortFashion($temp);
     return $fashion;
}

/////整合一下产品
protected function assortFashion($all_fashions){
    $array = [];

    foreach ($all_fashions as $v){
        $array[$v['fashion_code']][$v['fashion_size']][]=$v;
    }

    $one = function ($data){
        $num = 0 ;
        foreach ($data as $v){
            $num = $num + $v['fashion_num'];
        }

        $data[0]['fashion_num'] = $num;
        return $data [0];
    };
    /////计算个数
    $new_array = [];
    foreach ($array as $v){
        foreach ($v as $vv){
            $new_array[]=$one($vv);
        }
    }
    return $new_array;
}

/**
 * 一个包裹换货的商品
 * @param
 * @return mixed
 */
protected function hasFashionWithChange($this_model){
    $temp = [];
    foreach ($this_model->scanError as $v){
        $temp[] =$v->o_fashion_code.' 换 '.$v->r_fashion_code;
    }
    return $temp;
}

/**
 * 一个包裹详情 包裹扫描情况和换货情况
 * @param
 * @return mixed
 */
protected function packageDetail($scan_cn_model){
    $scan =   $this->hasFashions($scan_cn_model);///一个包裹已扫描的产品
    $huan =   $this->hasFashionWithChange($scan_cn_model);///一个包裹已换货的商品

    return compact('scan','huan');

}

/**
 * 获取学生的信息
 * @param
 * @return mixed
 */
public  function getStudent($source){

    if($source == '线上零售'){
        if(isset($this -> batchPrintEnd -> sellOrder ->sellStudent)){
            $class_name = $this -> batchPrintEnd -> sellOrder -> sellStudent -> gradeClass ->name;
            $grade_name = $this -> batchPrintEnd -> sellOrder -> sellStudent -> grade -> name;
            $sex = $this -> batchPrintEnd -> sellOrder -> sellStudent -> sex;
            $name  = $this -> batchPrintEnd -> sellOrder -> sellStudent -> name;
            return compact('class_name','grade_name','sex','name');
        }else{
            $class_name = '';
            $grade_name = '';
            $sex = '';
            $name  = '';
            return compact('class_name','grade_name','sex','name');
        }


    }elseif($source == '微信预售'){
        if(isset($this -> batchPrintEnd -> sellOrder -> BookingStudent)){
            $class_name = $this -> batchPrintEnd -> sellOrder -> BookingStudent -> gradeClass ->name;
            $grade_name = $this -> batchPrintEnd -> sellOrder -> BookingStudent -> grade -> name;
            $sex = $this -> batchPrintEnd -> sellOrder -> BookingStudent -> sex;
            $name  = $this -> batchPrintEnd -> sellOrder -> BookingStudent -> name;
            return compact('class_name','grade_name','sex','name');
        }else{
            $class_name = '';
            $grade_name = '';
            $sex = '';
            $name  = '';
            return compact('class_name','grade_name','sex','name');
        }
    }elseif($source == '线下导入'){

        if(isset($this -> batchPrintEnd -> offlineUser )){

            $class_name = $this -> batchPrintEnd -> offlineUser ->class;
            $grade_name = $this -> batchPrintEnd -> offlineUser -> grade ;
            $sex = $this -> batchPrintEnd  ->offlineUser -> sex;
            $name  = $this -> batchPrintEnd -> offlineUser ->  name;
            return compact('class_name','grade_name','sex','name');
        }else{
            $class_name = '';
            $grade_name = '';
            $sex = '';
            $name  = '';
            return compact('class_name','grade_name','sex','name');
        }
    }

}

/**
 * 获取学生的年级名称
 * @param 
 * @return mixed
 */
public function getGradeName1(){
    dd(__LINE__);
}
/**
 * 获取学生的性别
 * @param 
 * @return mixed
 */
    public function getSex1(){

    }
/**
 * 获取学生的班级名称
 * @param 
 * @return mixed
 */
    public function getClassName1(){
        
    }
    /**
     * 获取学生的姓名
     * @param 
     * @return mixed
     */
    public function getName1(){
        
    }

}