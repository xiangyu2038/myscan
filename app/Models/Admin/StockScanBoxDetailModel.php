<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockScanBoxDetailModel extends BaseModel
{
    protected $table = 'ts_stock_scan_box_detail';
//    public $timestamps = false;
    protected $guarded = [];

    public function box(){
        return $this -> belongsTo('App\Models\Admin\BoxModel','box_id');
    }

    public function fashion(){
        return $this -> hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }

    public function stockScanBox(){
        return $this -> belongsTo('App\Models\Admin\StockScanBoxModel','stock_scan_box_id');
    }
}
