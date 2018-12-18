<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
/**
 * 订单的地址信息表
 * @param
 * @return mixed
 */
class SellFashionRefundModel extends BaseModel
{
    protected $table = 'ts_sell_fashion_refund';
    protected $guarded = [];

    public function bookingFashion()
    {

        return $this->belongsTo('model\shop\SellOrderFashionModel', 'booking_fashion_info_id');

    }

    public function getReasonAttribute($value)
    {
        switch ($value) {
            case 1:
                return '商品质量问题';
                break;
            case 2:
                return '尺码不合适';
                break;
            case 3:
                return '商家发错货';
                break;
            case 4:
                return '拍错商品';
                break;
            case 5:
                return '退款重拍';
                break;
            case 6:
                return '未收到货';
                break;
            case 7:
                return '其他';
                break;

        }
    }

    public function getTypeAttribute($value)
    {
        switch ($value) {
            case 1:
                return '退款';
                break;
            case 2:
                return '退货退款';
                break;
            case 3:
                return '换货';
                break;
        }
    }

    protected function judgeIsRefund($this_model){
        if ($this_model->type == '退款') {
            if($this_model->is_look=='Y'&&$this_model->is_pass=='Y') {
                ////审核通过
                return true;
            }
            return false;
        }else{
//否则需要配送审核
            if($this_model->pei_song_is_look=='Y'&&$this_model->pei_song_is_pass=='Y') {
                ////审核通过
                return true;
            }
            return false;
        }
    }
}