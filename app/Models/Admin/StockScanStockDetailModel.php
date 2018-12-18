<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockScanStockDetailModel extends BaseModel
{
    protected $table = 'ts_stock_scan_stock_detail';
//    public $timestamps = false;
    protected $guarded = [];
public function stock(){
    return $this -> belongsTo('App\Models\Admin\StockModel','stock_id');
}

public function stockCount(){

}
    public function fashion(){
        return $this -> hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }
}
