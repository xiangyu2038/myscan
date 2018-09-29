<?php

namespace App\Http\Controllers\Admin;

use App\Myscan\MenuModel;
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
