<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class SellFashionOutStatusModel extends BaseModel
{

    protected $table = 'ts_sell_fashion_out_status';
    protected $guarded = [];

    /**
     * 构建一个保存数组
     * @param
     * @return mixed
     */
    protected function build($fashion_info,$info){
        foreach ($fashion_info as &$v){
            $v['batch_id'] = $info['sell_batch_id'];
            $v['sell_order_sn'] = $info['sell_order_sn'];
            $v['created_at'] = current_time();
            $v['updated_at'] = current_time();

        }
        unset($v);
        return $fashion_info;
    }

    /**
     * 判断一个产品的出库状态
     * @param 本模型群  *目标产品模型
     * @return mixed
     */
    protected function judgeOutStatus($this_model_s,$sell_fashion_model){

        ////申请售后的件数

        foreach ($this_model_s as $v){
               if($v->sell_order_sn==$sell_fashion_model->order_sn){
                   if($sell_fashion_model->sellConfig->fashion->code.$sell_fashion_model->chi_ma == $v->fashion_code.$v->fashion_size){
                    return $sell_fashion_model->num-$v->fashion_num;
                   }
               }
         }
       return $sell_fashion_model->num;
    }

}