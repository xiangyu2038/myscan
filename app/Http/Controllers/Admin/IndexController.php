<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index(){

        $datas=[
            ['key'=>0,'name'=>'现货扫描','icon'=>url('admin/webdesktop/images/i/icon31.png'),'url'=>'http://myscan.dev.com/myscan','width'=>'1200','height'=>'600'],
            ['key'=>1,'name'=>'现货扫描1','icon'=>url('admin/webdesktop/images/i/icon31.png'),'url'=>'https://www.baidu.com/?tn=sitehao123_15','width'=>'1200','height'=>'600']
        ];

        return view('admin.index',compact('datas'));
    }
}
