<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockOutDetailModel extends BaseModel
{
    protected $table = 'ts_stock_out_detail';
//    public $timestamps = false;
    protected $guarded = [];

    public function stock(){
        return $this->belongsTo('App\Models\Admin\StockModel','stock_id');
    }

    public function stockOut(){
        return $this->belongsTo('App\Models\Admin\StockOutModel','stock_out_id');
    }

    public function fashion(){
        return $this->belongsTo('App\Models\Admin\FashionModel','fashion_code','code');
    }

    /**
     * 所属学校
     * @param
     * @return mixed
     */
    public function hasSchoolName(){
        if($this->fashion){
           return $this->fashion->school;
       }
        return '';
    }

    /**
     * 所属库位号
     * @param
     * @return mixed
     */
    public function hasStockSn(){
        if($this->stock){
            return $this->stock->floor.'层:  '.$this->stock->stock_name.'('.$this->stock->stock_sn.')';
        }
        return '';
    }

}
