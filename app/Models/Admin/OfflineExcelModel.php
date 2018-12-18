<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class OfflineExcelModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_offline_excel';

    protected $guarded = [];

/**
 * 构造本模型导入的数据
 * @param 
 * @return mixed
 */
    protected function offlineExcel($excel_name,$note){
     $array = [];
     $array['uid'] = $this->getUid();
     $array['excel_name'] = $excel_name;
     $array['note'] = $note;
     return $array;

}

/**
 * 获取uid的唯一识别码
 * @param
 * @return mixed
 */

protected function getUid(){
    $order_id_main = date('Ymd') . rand(10, 99);
    $order_id_len = strlen($order_id_main);
    $order_id_sum = 0;
    for ($i = 0; $i < $order_id_len; $i++) {
        $order_id_sum += (int)(substr($order_id_main, $i, 1));
    }
//echo $order_id_main;
    $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

    $order_id=substr($order_id,3,9);

    return 'excel_'.$order_id;
}



}
