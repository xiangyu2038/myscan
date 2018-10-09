<?php

namespace App\Http\Controllers\Admin;


use App\Admin\Models\MenuModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index(){

        $menus = MenuModel::getMenu();

        return view('admin.index',compact('menus'));

    }
}
