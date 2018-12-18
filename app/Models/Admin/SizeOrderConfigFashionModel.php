<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SizeOrderConfigFashionModel extends BaseModel
{
    protected $table = 'ts_sizeorderconfig_fashion';
    protected $guarded = [];

    public function fashion(){
        return $this->belongsTo('App\Models\Admin\FashionModel','fashion_id')->select('id','name','real_name','en_name','sex','code','old_code','status','alias_code');
    }

}
