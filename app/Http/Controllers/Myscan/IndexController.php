<?php

namespace App\Http\Controllers\Myscan;


use App\Myscan\TestModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index(){
        $data = TestModel::all();
        dd($data);
    }
}
