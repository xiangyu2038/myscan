<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
/**
 * 订单信息表
 * @param
 * @return mixed
 */
class BookingOrderModel extends Model
{
    protected $table = 'ts_booking_order';
    protected $guarded = [];

    public function sellStudent()
    {
        return $this->belongsTo('App\Models\Admin\SellStudentModel', 'student_info_id');
    }

    public function bookingAddress()
    {
        return $this->belongsTo('App\Models\Admin\BookingAddressModel', 'address_info_id');
    }

    public function bookingPay()
    {
        return $this->hasOne('model\shop\BookingPayModel', 'order_sn', 'order_sn');
    }
    public function sellPrint()
    {
        return $this->hasOne('model\shop\SellOrderPrintModel', 'order_sn', 'order_sn');
    }
    public function bookingSuit()
    {
        return $this->hasMany('model\shop\BookingSuitModel', 'order_sn', 'order_sn');
    }
public function sellFashion(){
    return $this->hasMany('App\Models\Admin\SellOrderFashionModel','order_sn','order_sn');
}

    public function sizeOrder()
    {
        return $this->belongsTo('model\shop\SizeOrderModel', 'booking_id', 'order_id')->select('order_id', 'ispay', 'name', 'status', 'otype', 'u_id', 'isgrade', 'isclass', 'isqq');
    }

    public function bookingBatchPrint()
    {
        return $this->belongsToMany('model\shop\BookingBatchModel', 'ts_booking_batch_print_end', 'booking_order_id', 'booking_batch_id')->withPivot('one_code', 'is_print', 'kuaidi_is_print', 'kuaidi', 'kuaidi_company', 'kuaidi_time', 'is_scan', 'scan_time');
    }

    public function bookingBatchScan()
    {
        return $this->belongsToMany('model\shop\Booking_Batch', 'ts_booking_batch_scan_end', 'booking_order_id', 'booking_batch_id');
    }

    public function bookingFashionLoseOrChange()
    {
        return $this->hasMany('model\shop\Booking_fashion_lose_or_change', 'order_sn', 'order_sn');
    }



    public function getPayStatusAttribute($value)
    {
        switch ($value) {
            case 1:
                return '待支付';
                break;
            case 2:
                return '已支付';
                break;

        }
    }
}