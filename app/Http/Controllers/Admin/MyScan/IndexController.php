<?php

namespace App\Http\Controllers\Admin\MyScan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index(){
          sleep(3);
        return view('admin.myscan.index');
    }
}
