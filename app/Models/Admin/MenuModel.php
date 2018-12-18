<?php

namespace App\Admin\Models;

use App\Models\Admin\BaseModel;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'as_menu';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = false;

//设置时间戳格式为Unix
    protected $dateFormat = 'U';
    protected $guarded=[];

    public function getIdAttribute($value)
    {
        return $value-1;
    }

    public function getIconAttribute($value)
    {
        return url($value);
    }

    public function getUrlAttribute($value)
    {
        return url($value);
    }

    /**
     * 获取以及菜单
     * @param
     * @return mixed
     */

    protected function getMenu(){
        $menus = MenuModel::where('type',1)->get();///获取菜单列表
        return $menus;
    }
}


