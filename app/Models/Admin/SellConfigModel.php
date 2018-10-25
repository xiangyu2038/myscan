<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class SellConfigModel extends Model
{

    protected $table = 'ts_sell_config';
    protected $guarded = [];
    public function fashion()
    {
        return $this->belongsTo('App\Models\Admin\FashionModel', 'fashion_id')->select('id', 'name', 'code', 'old_code', 'fileurl')->select('name','id','code','real_name','alias_code');
    }
}