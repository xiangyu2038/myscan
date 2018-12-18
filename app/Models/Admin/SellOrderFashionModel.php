<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
/**
 * 订单的地址信息表
 * @param
 * @return mixed
 */
class SellOrderFashionModel extends BaseModel
{
    protected $table = 'ts_sell_order_fashion';
    protected $guarded = [];
public function sellConfig(){
    return $this->belongsTo('App\Models\Admin\SellConfigModel','sell_config_id');
}

public function sellFashionRefund(){
    return $this->hasOne('App\Models\Admin\SellFashionRefundModel', 'booking_fashion_info_id');
}

public function sellFashionOutStatus(){
    return $this->hasMany('App\Models\Admin\SellFashionOutStatusModel', 'sell_order_sn','order_sn');
}




/**
 * 判断售后申请有没有通过
 * @param
 * @return mixed
 */
protected function judgeIsRefund($this_model_with_sell_fashion_refund_model){

    if(!$this_model_with_sell_fashion_refund_model->sellFashionRefund){
      ///如果没有申请
       return false;
   }

   return SellFashionRefundModel::judgeIsRefund($this_model_with_sell_fashion_refund_model->sellFashionRefund);

}

/**
 * 判断是否发货
 * @param 一个本实例模型   *循环查询需要with 'sellFashionOutStatus','sellConfig.fashion'
 * @return mixed
 */
protected function judgeIsOut($this_model){

    $sell_out_status_model = $this_model ->sellFashionOutStatus;///需要with

   return  SellFashionOutStatusModel::judgeOutStatus($sell_out_status_model,$this_model);


}





}
