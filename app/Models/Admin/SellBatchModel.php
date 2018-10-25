<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SellBatchModel extends Model
{
    //
    //指定表名
    protected $table = 'ts_sell_batch';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = true;

    protected $guarded = [];
//设置时间戳格式为Unix
   // protected $dateFormat = 'U';


///属性转换
    //    protected $casts = [
//        'id' => 'boolean',
//    ];

    /**
     * 获取用户的名字
     *
     * @param  string  $value
     * @return string
     */
    public function getOutStatusAttribute($value)
    {
        switch ($value){
            case 0:
                return '未出库';
            case 1:
                return '出库';
        }
    }


}
