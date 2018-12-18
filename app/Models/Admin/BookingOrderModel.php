<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 订单信息表
 * @param
 * @return mixed
 */
class BookingOrderModel extends BaseModel
{
    protected $table = 'ts_booking_order';
    protected $guarded = [];

    public function sellStudent()
    {
        return $this->belongsTo('App\Models\Admin\SellStudentModel', 'student_info_id');
    }

    public function bookingStudent(){
        return $this->belongsTo('App\Models\Admin\BookingStudentModel','student_info_id');
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
        return $this->hasMany('App\Models\Admin\BookingSuitModel', 'order_sn', 'order_sn');
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

   public function sellBatchPrint(){
       return $this->hasMany('\App\Models\Admin\SellBatchPrintEndModel','sell_order_sn','order_sn');///
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

    /**
     * 判断一个订单的发货批次信息 以及每个批次下面发货的产品
     * @param  本实例模型  *本实例需要关联模型 sellBatchPrint batch.scanStudent.studentDetails batch.scanStudent.scanError
     * @return mixed
     */
    protected function outDetail($this_model){
     //$sell_batch_print_end = $this_model->sellBatchPrint;///发货中间表

        $temp = [];

        if($this_model->type==1){
            ///预售无疑

           if(!count($this_model->sellBatchPrint)){
              /////老系统发货无疑
               $temp[] = $this->heihei($this_model);
               return $temp;
           }else{
               foreach ($this_model->sellBatchPrint as $v){
                   $temp[] = SellBatchModel::batchData($v->batch,$v);
               }
           }

        }elseif($this_model->type==2){
            ////零售无疑
            foreach ($this_model->sellBatchPrint as $v){
                $temp[] = SellBatchModel::batchData($v->batch,$v);
            }
        }

        $temp = $this->giveName($temp);///增加产品名称

        return $temp;

    }

    /**
     * 判断一个零售订单时候转换为批次
     * @param
     * @return mixed
     */

    protected function judgeSellIsC($this_model){

       if(count($this_model->sellBatchPrint)){
           return '已转换';
       }
       return '未转换';
}

/**
 * 整理自己
 * @param
 * @return mixed
 */
protected function forthis($this_model){
$temp = [];
$temp['id'] = $this_model->id;
$temp['order_sn'] = $this_model->order_sn;
$temp['suit'] = $this->allSuit($this_model);///一个订单的所有的套装
return $temp;
}

/**
 * 一个订单有几个套装
 * @param
 * @return mixed
 */
protected function allSuit($this_model){
$temp = [];
foreach ($this_model->bookingSuit as $v){
    $temp[] = BookingSuitModel::forThis($v);
}
return $temp;

}

/**
 * 一个订单的地址信息
 * @param
 * @return mixed
 */
protected function address($this_model){
    $temp = [];
    $temp['pei_model'] = '快递到家';
    $temp['phone'] = $this_model->bookingAddress->phone;
    $temp['name'] = $this_model->bookingAddress->name;
    $temp['address'] = $this_model->bookingAddress->province.$this_model->bookingAddress->city.$this_model->bookingAddress->area.$this_model->bookingAddress->detail;
    return $temp;

}

////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
/// 非线性函数区

protected function giveName($data){
  $fashions = [];
  foreach ($data as $v){
      foreach ($v['package_detail']['scan'] as $vv){
          $fashions[] = $vv['fashion_code'];

      }
  }

 $fashion =  FashionModel::whereIn('code',$fashions)->get();
    foreach ($data as &$v){
        foreach ($v['package_detail']['scan'] as &$vv){
           $vv['fashion_name'] = $this -> getFashionName($vv['fashion_code'],$fashion);
        }
        unset($vv);
    }
    unset($v);
    return $data;
}

public function getFashionName($fashion_code,$fashion){
    foreach ($fashion as $v){
        if($fashion_code == $v->code){
            return $v->real_name;
        }
    }
    return '';
}

/**
 * 嘿嘿 临时函数 不用管
 * @param
 * @return mixed
 */
public function heihei($this_model){
    $order_data_cate_student =  DB::table('ts_order_data_cate_student')->where('order_sn',$this_model->order_sn)->get()->toArray();
    $order_data_cate_id = array_unique(array_column($order_data_cate_student,'id'));////这个学生的数据采集

    ////查出有几个批次
    $a =  DB::table('ts_order_batch_print_end')->whereIn('student_order_id',$order_data_cate_id)->get();

$temp = [];
$one = function ($data){
    $temp = [];
    $temp['package_detail']['scan'] = [];
    $temp['package_detail']['huan'] = [];
    $temp['batch_data'] = [];
    $temp['wu_liu']['kuaidi'] = $data->kuaidi;
    $temp['wu_liu']['kuaidi_company'] = $data->kuaidi_company;
    return $temp;
};

foreach ($a as $v){
    $temp[] = $one($v);
}
return $temp;

}

}