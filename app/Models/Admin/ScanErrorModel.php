<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 10:24
 */
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class ScanErrorModel extends  BaseModel{
    protected $table='ts_scan_error';

    protected $guarded=[];

    /**
     * 创建要保存的数组
     * @param
     * @return mixed
     */
    protected function build($data){

        $array=[];
        $array['scan_sn'] = $data['scan_sn'];
        $array['o_fashion_code']= $data['o_fashion_code'];
        $array['r_fashion_code'] = $data['r_fashion_code'];
        $array['type'] = $data['type'];
        return $array;
    }

}