<?php

namespace App\Models\Admin;

use App\Helper\ObjectHelper;
use Illuminate\Database\Eloquent\Model;

class StockCountModel extends BaseModel
{
    protected $table = 'ts_stock_count';
//    public $timestamps = false;
    protected $guarded = [];

    /**
     * 增加一个盘点单
     * @param
     * @return mixed
     */
    public function add($data){

        $ts_stock_count = $this->getStockCountSn();
        $data['stock_count_sn'] = $ts_stock_count;


       return  $this->create($data);

    }
    
    /**
     * 生成一个盘点编号
     * @param 
     * @return mixed
     */
    public function getStockCountSn(){
        $order_id_main = date('Ymd') . rand(10, 99);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
//echo $order_id_main;
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

        $order_id=substr($order_id,3,9);
        return 'CKPD_'.$order_id;
    }

    /**
     * 开启一个盘点单
     * @param
     * @return mixed
     */
    public function start(){
        return $this->update(['status'=>'开启']);
    }

    /**
     * 获取编码
     * @param
     * @return mixed
     */
    public function getSnAttribute(){
       return $this -> stock_count_sn;
    }






    






}
