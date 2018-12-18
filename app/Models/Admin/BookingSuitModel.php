<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Comparator\Book;

class BookingSuitModel extends BaseModel
{
    protected $table = 'ts_booking_suit_info';
    protected $guarded = [];

    public function sizeOrderConfig(){
        return $this->belongsTo('App\Models\Admin\SizeOrderConfigModel','sizeorderconfig_id')->select('id','name','EN_name','price','sex');
    }
    public function bookingFashion(){
        return $this->hasMany('App\Models\Admin\BookingFashionModel','booking_suit_info_id');
    }

    /**
     * 本身
     * @param 
     * @return mixed
     */
protected function forThis($this_model){
    $temp = [];
    $temp['suit_id'] = $this_model->id;
    $temp['suit_name'] = $this_model->sizeOrderConfig->name;
    $temp['suit_en_name'] = $this_model->sizeOrderConfig->EN_name;
    $temp['suit_fashion'] = $this -> allFashion($this_model);

    return $temp;
}

/**
 * 一个套装下所有的产品
 * @param 
 * @return mixed
 */
public function allFashion($this_model){
    $temp = [];
    foreach ($this_model->bookingFashion as $v){
        $temp [] = BookingFashionModel::forThis($v);
    }
return asssort($temp);///整合一下产品

}





}
