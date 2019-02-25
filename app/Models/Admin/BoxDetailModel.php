<?php

namespace App\Models\Admin;

use App\Helper\ObjectHelper;
use Illuminate\Database\Eloquent\Model;

class BoxDetailModel extends BaseModel
{
    protected $table = 'ts_box_detail';
//    public $timestamps = false;
    protected $guarded = [];

public function box(){
    return $this->belongsTo('App\Models\Admin\BoxModel','box_id');
}

    public function fashion(){
        return $this -> hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }
    public function getStockSn(){

        return $this -> box -> stock_sn;
    }

    public function getBoxSn(){
        return $this -> box -> box_sn;
    }



}
