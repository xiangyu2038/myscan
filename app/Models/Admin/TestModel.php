<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class TestModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_a_test';

    protected $guarded = [];




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
