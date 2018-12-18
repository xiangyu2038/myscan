<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SellBatchPrintEndModel extends BaseModel
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

    /**
     * 有许多的产品  ts_sell_batch_print_fashion
     * @param 
     * @return mixed
     */

    public function sellBatchPrintFashions(){
    return $this->hasMany('App\Models\Admin\SelllBatchPrintFashionModel','one_code','one_code');
}

/**
 * 属于一个用户
 * @param
 * @return mixed
 */
    public function offlineUser(){
        return $this->belongsTo('App\Models\Admin\OfflineUserModel','sell_order_sn','order_sn');
    }

    /**
     * 对应的微信预售的订单
     * @param
     * @return mixed
     */
    public function bookingOrder(){
        return $this->belongsTo('App\Models\Admin\BookingOrderModel', 'sell_order_sn','order_sn');
    }


    /**
     * 所对应的批次信息
     * @param
     * @return mixed
     */
    public function batch(){
        return $this->hasOne('\App\Models\Admin\SellBatchModel','id','sell_batch_id');
    }

    /**
     * 每一个包裹所对应的缺货信息
     * @param $this_model *sellBatchPrintFashions
     * @return mixed
     */
    protected function que($this_model){

       ////首先这一个包裹这个人应该发的产品
        $should_fashion = $this->shouldFashion($this_model->sellBatchPrintFashions);
        ////包裹的产品 已经扫描的产品

             $package_fashion = ScanStudentModel::hasFashions($this_model->scanStudent);


        $que = $this->compare($should_fashion,$package_fashion);

        return $que;
    }

    /**
     * 本批次需要发货的产品
     * @param
     * @return mixed
     */
    protected function shouldFashion($sell_batch_print_fashions){
        ////需要出库的产品
        return  SelllBatchPrintFashionModel::forThis_S($sell_batch_print_fashions);

    }

    /**
     * 对比商品 找出缺货的
     * @param 要发的商品
     * @param 已经扫描的商品
     * @return mixed
     */

    public function compare($should_fashion,$package_fashion){
        $temp = [];
        foreach ($should_fashion as $v){
            $temp[] =  $this->help1($v,$package_fashion);
        }
return $temp;
    }

    /**
     *
     * @param
     * @return mixed
     */
    public function help1($should_fashion_one,$package_fashion){

        $num = $should_fashion_one['fashion_num'];////这个值最终为这个产品未发货的数量
        foreach ($package_fashion as $v){
            if($v['fashion_code'].$v['fashion_size'] == $should_fashion_one['fashion_code'].$should_fashion_one['fashion_size'] ){
                ////这个产品在发货里面
                $num = $should_fashion_one['fashion_num']-$v['fashion_num'];
            }
        }
        if($num == $should_fashion_one['fashion_num']){
            ////说明没有被发货 为缺
            return $should_fashion_one;
        }elseif($num == 0){
           ///已经发了
            //return $should_fashion_one;
            return [];
        }
        $should_fashion_one['fashion_num'] = $num;
        return $should_fashion_one;

    }


}
