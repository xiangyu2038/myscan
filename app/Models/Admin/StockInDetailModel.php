<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;



class StockInDetailModel extends BaseModel
{
    protected $table = 'ts_stock_in_detail';
//    public $timestamps = false;
    protected $guarded = [];

    public function stock(){
        return $this->belongsTo('App\Models\Admin\StockModel','stock_id');
    }

    public function stockIn(){
        return $this->belongsTo('App\Models\Admin\StockInModel','stock_in_id');
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
