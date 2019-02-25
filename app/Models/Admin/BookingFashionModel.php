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

    public function bookingFashionRefund(){
        return $this->hasOne('App\Models\Admin\BookingFashionRefund','booking_fashion_info_id');
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

    public function judge($this_model){
        if($this_model['type']=='退款'){
            ///如果为仅退款，那么客服审核通过就可以了
            if($this_model['is_look']=='Y'&&$this_model['is_pass']=='Y') {
                ////审核通过
                return true;
            }
            return false;
        }else{

            //否则需要配送审核
            if($this_model['pei_song_is_look']=='Y'&&$this_model['pei_song_is_pass']=='Y') {
                ////审核通过
                return true;
            }
            return false;
        }

    }

    
}
