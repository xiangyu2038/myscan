<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 10:24
 */
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class SizeOrderModel extends BaseModel {
    protected $table='ts_sizeorder';
//    public $timestamps = false;
    protected $guarded = [];

    public function batch(){
        return $this -> hasMany('App\Models\Admin\SellBatchModel','order_id','order_id');
    }

    public function bookingOrder(){
        return $this -> hasMany('App\Models\Admin\BookingOrderModel','booking_id','order_id');
    }

    public function sizeOrderConfig(){
        return $this -> hasMany('App\Models\Admin\SizeOrderConfigModel','orderid','order_id')->where('status','!=',3);
    }

    /**
     * 给这个预售增加一个批次  处理好关联关系 不然速度很慢
     * @param 预售的id
     * @param 备注
     * @param 本批次所包含的产品列表
     * @return mixed
     */
    protected function addBatch($order_id,$note,$fashion_code_s,$source,$fa_huo_time){

        $size_order = SizeOrderModel::where('order_id',$order_id)->with('bookingOrder.bookingSuit.bookingFashion.sizeOrderConfigFashion.fashion')->with('bookingOrder.bookingSuit.sizeOrderConfig')->with('bookingOrder.bookingSuit.bookingFashion.bookingFashionRefund')->first();


        $size_order_all_fashion = $this->allOrder($size_order);///一个预售所有的订单

         $will_fa_fashion = $this->willFaFashion($size_order_all_fashion,$fashion_code_s);
        $order_sns = array_unique(array_column($will_fa_fashion,'order_sn'));

        if($will_fa_fashion){
             return  SellBatchModel::add($will_fa_fashion,$fa_huo_time,$note,$order_sns,$source,$order_id);
         }
        throw new \Exception('插入批次失败');
    }

    /**
     * 一个预售下所有的订单
     * @param $this_model
     * @param $fashion_code_s
     * @return mixed
     */
    protected function allOrder($this_model){

        if(!$this_model){
             return [];
         }

         $temp = [];
         foreach ($this_model->bookingOrder as $v){

             //             if($v -> status == -1 ){
//                continue;
//             }

//             if($v  ->is_pay == 2 && $v -> pay_status == '已支付' ){
                 $temp[] = BookingOrderModel::forThis($v);
          //  }
         }

         return $temp;

    }

    /**
     * 获取一个预售下每个订单本批次需要发货的商品
     * @param
     * @return mixed
     */
protected function willFaFashion($size_order_all_fashion,$fashion_code_s){

    $temp = [];
    foreach ($size_order_all_fashion as $v){
        foreach ($v['suit'] as $vv){
           foreach ($vv['suit_fashion'] as $vvv){
               $vvv['order_sn'] = $v['order_sn'];
               if(in_array($vvv['fashion_code'],$fashion_code_s)){
                   $temp[] = $vvv;
               }
           }
        }
    }
    return $temp;
}

/**
 * 一个预售所拥有的所有的产品
 * @param
 * @return mixed
 */

protected function hasAllFashions($this_model){
    $array=[];
    $fashion=[];
    foreach ($this_model->sizeOrderConfig as $v){
        foreach ($v->configFashion as $vv){
            $fashion['show_name']=$vv['fashion']['name'];
            $fashion['id']=$vv['fashion']['id'];
            $fashion['code']=$vv['fashion']['code'];
            $fashion['old_code']=$vv['fashion']['old_code'];
            $array[]=$fashion;
        }
    }

    $temp = [];
    foreach ($array as $v){
        $temp[$v['id']][] = $v;
    }

    $first = function($data){
        foreach ($data as $v){
            return $v;
        }
    };

    $n_temp = [];
foreach ($temp as $v){
    $n_temp[] = $first($v);
}
return $n_temp;

}




}