<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SelllBatchPrintFashionModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_sell_batch_print_fashion';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = true;




///属性转换
    //    protected $casts = [
//        'id' => 'boolean',
//    ];


/**
 * 为自己
 * @param
 * @return mixed
 */
protected function forThis_S($this_model_s){
    $temp = [];
    foreach ($this_model_s as $v){
          $temp [] = $this->forThis($v);
      }
     return $temp;
}

/**
 *
 * @param
 * @return mixed
 */
protected function forthis($this_model){
    $temp = [];
    $temp['order_sn'] = $this_model->order_sn;
    $temp['fashion_name'] = $this_model->fashion_name;
    $temp['fashion_en_name'] = $this_model->fashion_en_name;
    $temp['fashion_code'] = $this_model->fashion_code;
    $temp['fashion_size'] = $this_model->fashion_size;
    $temp['fashion_num'] = $this_model->fashion_num;
    $temp['fashion_alias_code'] = $this_model->fashion_alias_code;
    $temp['fashion_price'] = $this_model->fashion_price;
    return $temp;
}


}
