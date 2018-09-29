<?php

namespace App\Http\Controllers\Admin\Tool;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index(Request $request){
         if($request->isMethod('post')){

             upload($request,'file');
         }

        return view('admin.tool.index');
    }
}
