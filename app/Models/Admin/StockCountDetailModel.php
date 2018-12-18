<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockCountDetailModel extends BaseModel
{
    protected $table = 'ts_stock_count_detail';
//    public $timestamps = false;
    protected $guarded = [];

    public function fashion(){
        return $this->belongsTo('App\Models\Admin\FashionModel','fashion_code','code');
    }

    public function stock(){
        return $this->belongsTo('App\Models\Admin\StockModel','stock_id');
    }

    public function stockBox(){
        return $this->belongsTo('App\Models\Admin\StockBoxModel','stock_box_id');
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
     * 盘点的本产品的账面数量
     * @param 
     * @return mixed
     */
    public function chen_xiang_yu_PaperNum(){
        return $this->fashion->hasPaperNum();
    }
    
    /**
     * 盘点的产品的库位信息
     * @param 
     * @return mixed
     */
    public function hasStockInfo($temp){
          if($this->stock){
              return for_this($this->stock,$temp);
          }
          return '';
    }

    /**
     * 盘点的产品的箱子信息
     * @param
     * @return mixed
     */
    public function hasStockBoxInfo($temp){
        if($this->stockBox){
            return for_this($this->stockBox,$temp);
        }
        return '';
    }




}
