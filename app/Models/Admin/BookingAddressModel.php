<?php

namespace model\shop;

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

/**
 * 订单的地址信息表
 * @param
 * @return mixed
 */
class BookingAddressModel extends BaseModel
{

    protected $table = 'ts_booking_address_info';
    protected $guarded = [];

}
