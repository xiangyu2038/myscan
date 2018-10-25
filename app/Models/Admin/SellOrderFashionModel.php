<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
/**
 * 订单的地址信息表
 * @param
 * @return mixed
 */
class SellOrderFashionModel extends Model
{
    protected $table = 'ts_sell_order_fashion';
    protected $guarded = [];
public function sellConfig(){
    return $this->belongsTo('App\Models\Admin\SellConfigModel','sell_config_id');
}

public function sellFashionRefund(){
    return $this->hasOne('model\shop\SellFashionRefundModel', 'booking_fashion_info_id');
}


}
