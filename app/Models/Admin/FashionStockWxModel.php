<?php

namespace App\Models\Admin;



class FashionStockWxModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_fashion_stock_wx';

    protected $guarded = [];



    public function fashion(){
        return $this->hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }

    public function getSchoolName(){
        return $this -> fashion -> school;
    }



}
