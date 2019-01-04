<?php

namespace App\Models\Admin;
class StockMoveModel extends BaseModel
{
    protected $table = 'ts_stock_move';

    protected $guarded = [];

    public function stockMoveDetail(){
        return $this->hasMany('App\Models\Admin\StockMoveDetailModel','stock_move_sn','stock_move_sn');
    }

    /**
     * 构建一个编号
     * @param
     * @return mixed
     */
    public function stockMoveSn(){
        $order_id_main = date('Ymd') . rand(10, 99);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
//echo $order_id_main;
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

        $order_id=substr($order_id,3,9);
        return 'CKXH_'.$order_id;
    }
    public function build($name,$operate){
        $temp_build = [];
        $temp_build['stock_move_sn'] = $this -> stockMoveSn();
        $temp_build['name'] = $name;
        $temp_build['operate'] = $operate;
        return $temp_build;
    }

}
