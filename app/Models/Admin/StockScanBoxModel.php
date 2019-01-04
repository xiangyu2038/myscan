<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class StockScanBoxModel extends BaseModel
{

    protected $table = 'ts_stock_scan_box';
    protected $guarded = [];

    public function build($op_sn,$box_id,$type){
         return compact('op_sn','box_id','type');
    }

    public function stockScanBoxDetail(){
        return  $this -> hasMany('App\Models\Admin\StockScanBoxDetailModel','stock_scan_box_id');
    }
    public function box(){
        return $this -> belongsTo('App\Models\Admin\BoxModel','box_id');
    }
    public  function  stockScanStock(){
        return $this -> belongsTo('App\Models\Admin\StockScanStockModel','stock_scan_stock_id');
    }

    public function setTest(){
        return 5;
    }

}

