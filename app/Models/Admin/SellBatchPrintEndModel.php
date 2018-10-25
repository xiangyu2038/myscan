<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SellBatchPrintEndModel extends Model
{
    //
    //指定表名
    protected $table = 'ts_sell_batch_print_end';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = true;




///属性转换
    //    protected $casts = [
//        'id' => 'boolean',
//    ];

/**
 * 对应的零售订单表
 * @param
 * @return mixed
 */
public function sellOrder(){
    return $this->belongsTo('App\Models\Admin\BookingOrderModel', 'sell_order_sn','order_sn');
}

/**
 * 扫描异常的情况
 * @param
 * @return mixed
 */

    public function scanError(){
        ///扫描异常表
        return $this->hasMany('App\Models\Admin\ScanErrorModel','scan_sn','one_code');
    }

    /**
     * 学生扫描情况
     * @param
     * @return mixed
     */
    public function scanStudent(){
        return $this->hasOne('App\Models\Admin\ScanStudentModel','student_code','one_code');
    }

    /**
     * 已打印 未打印
     * @param
     * @return mixed
     */
    public function getIsPrintAttribute($value)
    {
        switch ($value) {
            case 0:
                return '未打印';
                break;
            case 2:
                return '已打印';
                break;

        }
    }




}
