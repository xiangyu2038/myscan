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
ScanStudentModel extends  \Illuminate\Database\Eloquent\Model{
    protected $table='ts_scan_student';

    protected $guarded=[];
public function studentDetails(){

    return $this->hasMany('App\Models\Admin\ScanStudentDetailsModel','scan_student_id');
}

public function scanError(){
    ///扫描异常表
    return $this->hasMany('App\Models\Admin\ScanError','scan_sn','one_code');
}

}