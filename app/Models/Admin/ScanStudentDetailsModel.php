<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 10:24
 */
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class ScanStudentDetailsModel extends  BaseModel{
    protected $table='ts_scan_student_details';

    protected $guarded=[];

    protected function formate($this_model){


        $scan_fashion=trim($this_model->fashion_code);
        $a = strripos($scan_fashion,'A');
        $array=[];
        $array['fashion_code']=substr($scan_fashion,0,$a+1);
        $array['fashion_size']=substr($scan_fashion,$a+1,3);
        $array['fashion_num']=1;

        return $array;
    }
}