<?php

namespace App\Http\Controllers;

use App\Admin\Models\MenuModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {

        $current = \Route::currentRouteName();
        if($current){
            ////如果存在当前路由名称 那么就去查找其
            $sub_menus = $this->getSubMenu($current);
            //dd($sub_menu);
            view()->composer('admin.layouts.tag', function ($view)use($sub_menus) {
                $view->with('sub_menus',$sub_menus);
            });
        }


    }

   /**
    * 获取一个菜单的所有同级菜单
    * @param 
    * @return  [
   ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool'],
   ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool']
   ];
    */
    public function getSubMenu($current){

        if (Cache::has('key')) {
            ///如果有目录的缓存
            dd(__LINE__);
        }
        ////没有缓存看,
        MenuModel::where('route_name',$current)->first();

      return   [
            ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool'],
            ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool']
        ];

    }



}
