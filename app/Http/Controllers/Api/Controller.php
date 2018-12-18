<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin:*');
    }
}
