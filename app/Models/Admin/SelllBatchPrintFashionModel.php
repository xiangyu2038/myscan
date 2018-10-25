<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SelllBatchPrintFashionModel extends Model
{
    //
    //指定表名
    protected $table = 'ts_sell_batch_print_fashion';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = true;




///属性转换
    //    protected $casts = [
//        'id' => 'boolean',
//    ];





}
