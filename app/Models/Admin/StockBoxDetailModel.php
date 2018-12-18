<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockBoxDetailModel extends BaseModel
{
    protected $table = 'ts_stock_box_detail';
//    public $timestamps = false;
    protected $guarded = [];

    public function fashion(){
       return $this->hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }

public function stockBox(){
    return $this->belongsTo('App\Models\Admin\StockBoxModel','box_id');
}


protected function forThis($this_model,$p='null'){

    $temp = [];
    $temp['fashion_name'] = $this_model -> fashion_name;
    $temp['fashion_code'] = $this_model -> fashion_code;
    $temp['fashion_size'] = $this_model -> fashion_size;
    $temp['fashion_num'] = $this_model -> fashion_num;

    foreach ($p as $v){
        $f = 'hasOne'.ucfirst(convertUnderline($v));
        $temp[$v] = $this -> $f($this_model);
    }

    return $temp;

}


public function hasSchoolName($this_model){
    return $this_model->fashion->school;
}



/**
 * 所属仓库
 * @param
 * @return mixed
 */
    public function hasLocation($this_model){
        if($this_model->stockBox&&$this_model->stockBox->stock){
            return $this_model->stockBox->stock->stock_name.'('.$this_model->stockBox->stock->stock_sn.')';
        }
        return '';
    }

    public function hasFashion($relation,$temp){
        return for_this($this->$relation,$temp);
    }

    public function chen_xiang_yu_fashion($relation,$p){
        return  [$p[0]=>$this->$relation->school];
    }
    public function chen_xiang_yu_fashionInfo($relation,$p){
        return this($this->fashion,$p);
    }

}
