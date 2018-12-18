<?php

namespace App\Models\Admin;

use App\Service\Admin\MyScanService;
use Illuminate\Database\Eloquent\Model;

class SellBatchDetailsModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_sell_batch_details';

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



}
