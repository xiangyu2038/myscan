<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class BookingFashionModel extends BaseModel
{
    protected $table = 'ts_booking_fashion_info';
    protected $guarded = [];

    public function sizeOrderConfigFashion(){
        return $this->belongsTo('App\Models\Admin\SizeOrderConfigFashionModel','sizeorder_config_fashion_id');
    }

/**
 * forthis
 * @param  forthis
 * @return mixed
 */
protected function forThis($this_model){
    $temp = [];
    $temp['fashion_name'] = $this_model->sizeOrderConfigFashion->fashion->real_name;
    $temp['fashion_en_name'] = $this_model->sizeOrderConfigFashion->fashion->en_name;
    $temp['fashion_code'] = $this_model->sizeOrderConfigFashion->fashion->code;
    $temp['fashion_num'] = $this_model->num;////注意 这个地方每个表默认为一个数量
    $temp['fashion_size'] = $this_model->chi_ma;
    $temp['fashion_alias_code'] = $this_model->sizeOrderConfigFashion->fashion->alias_code;
    $temp['fashion_price'] = $this_model->sizeOrderConfigFashion->price;
    return $temp;
}



}
