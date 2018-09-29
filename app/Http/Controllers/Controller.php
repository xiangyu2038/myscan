<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {

        $sub_menus = $this->getSubMenu();
         //dd($sub_menu);
        view()->composer('admin.layouts.tag', function ($view)use($sub_menus) {
            $view->with('sub_menus',$sub_menus);
        });
    }

   /**
    * 获取一个菜单的所有同级菜单
    * @param 
    * @return  [
   ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool'],
   ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool']
   ];
    */
    public function getSubMenu(){
      return   [
            ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool'],
            ['name'=>'数据转换（只尺码统计）','route_name'=>'admin.tool.index.index','url'=>'tool']
        ];

    }



}
