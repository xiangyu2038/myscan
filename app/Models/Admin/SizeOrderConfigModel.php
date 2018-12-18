<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SizeOrderConfigModel extends BaseModel
{
    protected $table = 'ts_sizeorderconfig';
    protected $guarded = [];

    public function configFashion(){
        return $this->hasMany('App\Models\Admin\SizeOrderConfigFashionModel','soc_id')->select('id','name','EN_name','soc_id','fashion_id','price','imgurl','num','status','sort','operator_id')->where('status',1);
    }

}
