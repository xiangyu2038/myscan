<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 10:24
 */
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class
ScanStudentModel extends  BaseModel{
    protected $table='ts_scan_student';

    protected $guarded=[];
public function studentDetails(){
    return $this->hasMany('App\Models\Admin\ScanStudentDetailsModel','scan_student_id');
}

public function scanError(){
    ///扫描异常表
    return $this->hasMany('App\Models\Admin\ScanErrorModel','scan_sn','one_code');
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

}