<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockScanStockModel extends BaseModel
{
    protected $table = 'ts_stock_scan_stock';
//    public $timestamps = false;
    protected $guarded = [];
   public function build($op_sn,$stock_id,$type){
       return compact('op_sn','stock_id','type');
    }

    public function stockScanStockDetail(){
        return $this -> hasMany('App\Models\Admin\StockScanStockDetailModel','stock_scan_stock_id');
    }

    public function stockScanBox(){
        return $this -> hasMany('App\Models\Admin\StockScanBoxModel','stock_scan_stock_id');
    }

    public function stock(){
        return $this -> belongsTo('App\Models\Admin\StockModel','stock_id');
    }





}
