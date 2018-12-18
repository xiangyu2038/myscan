<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockOutModel extends BaseModel
{
    protected $table = 'ts_stock_out';
//    public $timestamps = false;
    protected $guarded = [];

    /**
     * 记录一个出库
     * @param 
     * @return mixed
     */
public function add(){
   $this_build =  $this -> build('出库单','常规出库','陈翔宇');
    return $this -> create($this_build);
}

/**
 * 构建一个出库数据
 * @param 
 * @return mixed
 */
public function build($name,$type,$operate){
   $temp = [];
   $temp['stock_out_sn'] = $this -> stockOutSn();
   $temp['name'] = $name;
   $temp['type'] = $type;
   return $temp;

}

/**
 * 生成一个本实例的条码
 * @param 
 * @return mixed
 */
public function stockOutSn(){
    $order_id_main = date('Ymd') . rand(10, 99);
    $order_id_len = strlen($order_id_main);
    $order_id_sum = 0;
    for ($i = 0; $i < $order_id_len; $i++) {
        $order_id_sum += (int)(substr($order_id_main, $i, 1));
    }
//echo $order_id_main;
    $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

    $order_id=substr($order_id,3,9);
    return 'CKOUT_'.$order_id;
}

    /**
     * 获取编码
     * @param
     * @return mixed
     */
    public function getSnAttribute(){
        return $this -> stock_out_sn;
    }

    
}
