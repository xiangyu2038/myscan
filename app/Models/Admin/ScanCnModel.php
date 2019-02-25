<?php
namespace App\Models\Admin;

class ScanCnModel extends  BaseModel{
    protected $table='ts_scan_cn';

    protected $guarded=[];
    static $num=null;//箱子数量
    public function scanDetails(){
        return $this->hasMany('\App\Models\Admin\ScanCnDetailsModel','scan_cn_id');
    }
    public function student(){
        return $this->hasMany('\App\Models\Admin\ScanStudentModel','scan_cn_id');
    }

    public function getCodeAttribute($box_code)
    {
        $len = strlen($box_code);
        switch ($len){
            case 1:
                return '000'.$box_code;
            case 2:
                return '00'.$box_code;
            case 3:
                return '0'.$box_code;
            case 4:
                return $box_code;
        }
    }

    /**
     * 这个箱子所用的元素数量 包含箱子和产品
     * @param
     * @return mixed
     */
    public function getElement(){
        return count($this -> scanDetails);
    }

    /**
     * 一个箱子有几个包裹
     * @param
     * @return mixed
     */
    public function getPackage(){
       $package = [];
        foreach ($this -> scanDetails as $v){
            if($this -> judgeCodeType($v -> code) == 'package'){
               $package[] = $v;
           }
       }

     return  $package;

    }

    /**
     * 判断是箱子还是产品
     * @param
     * @return mixed
     */
    public  function judgeCodeType($code){
        $str=substr($code,0,2);///前两位
        if($str=='XX'&&strlen($code)==12){
          ///说明是包裹集合
            return 'package';
        }else{
            return 'fashion';
        }
    }

    /**
     * 添加箱子
     * @param
     * @return mixed
     */
    public function addBox($batch_id,$box_num){
        $box_builds = [];

        for ($x=1; $x<=$box_num; $x++) {
            $box_builds[] = $this -> build($batch_id);
        }

        $res = ScanCnModel::insert($box_builds);
        if(!$res){
            return msg(1,'false');
        }

        return ['code'=>0,'msg'=>'ok'];

    }

    /**
     * 构建
     * @param
     * @return mixed
     */
    public  function build($batch_id){
        $array=[];
        $array['order_batch_id']=$batch_id;
        $array['code']=$this->scanCnCode($batch_id);
        $array['created_at'] = $array['updated_at'] = current_time();

        return $array;
    }

    /**
     * 生成箱子编码
     * @param
     * @return mixed
     */
    public  function scanCnCode($batch_id){

        if($a=static::$num){
            static::$num=$a+1;
        }else{
            $data = ScanCnModel::where('order_batch_id',$batch_id)->get()->last();
if(!$data){
    $num = 0;
}else{
    $num=$data->code;
}

            static::$num=$num+1;


        }

        return static::$num;
    }

    /**
     * 箱子的打印信息
     * @param
     * @return mixed
     */
    public function getBoxInfo($custom,$type,$batch_sn){
        $box_info = [];
        $box_info['box_sn']=$this -> code;
        $box_info['type']=$type;
        $box_info['custom']=$custom;
        $box_info['date']=$this -> updated_at;
        $box_info['one_code']=$batch_sn.$this -> code;
        return $box_info;
    }

    /**
     * 箱贴的头部信息
     * @param
     * @return mixed
     */
public function getBoxStickTitle($box_title_data,$batch_data){
    $temp = [];
    $temp['pei_song_sn'] = $box_title_data['pei_song_sn'];
    $temp['order_xiao_shou_sn'] = $box_title_data['order_xiao_shou_sn'];
    $temp['custom_name'] = $box_title_data['custom_name'];
    $temp['zhuang_xiang_sn'] = $batch_data -> batch_sn;
    $temp['zhuang_xiang_time'] = $batch_data -> created_at;
    $temp['xue_bu'] = '';
    return $temp;

}

/**
 * 这个箱子拥有的产品  仅仅是包裹的里面的产品
 * @param
 * @return mixed
 */
public function getPackageFashion(){
    $packages = $this -> getPackage();
    $temp = [];
    foreach ($packages as $package){
        $temp[] = $package -> getFashion();
    }

 return   collapse($temp);
}

/**
 * 这个箱子游离的Fashion
 * @param
 * @return mixed
 */
public function getFreeFashion(){
    $free_fashion = [];
    foreach ($this -> scanDetails as $v){
        if($this -> judgeCodeType($v -> code) == 'fashion'){
            $free_fashion[] = $v;
        }
    }

    return $free_fashion;
}

/**
 * 这个箱子拥有的所有产品 包括包裹的和直接装箱的
 * @param
 * @return mixed
 */
public function getFashions(){
    $package_fashion  = $this -> getPackageFashion();
     $free_fashion = $this -> getFreeFashion();


}


/**
 * 这个箱子所有的学生信息
 * @param
 * @return mixed
 */
public  function getStudents(){
    $students = [];
    foreach ($this -> scanDetails as $v){
       if($v -> type ==2){
           $students [] = $v ->  student;
       }

    }
  return $students;
}



}