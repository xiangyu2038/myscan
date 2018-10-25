<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    //
    //指定表名
    protected $table = 'test';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = true;




///属性转换
    //    protected $casts = [
//        'id' => 'boolean',
//    ];

///访问器
    /**
     * 获取用户的名字
     *
     * @param  string  $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

}
