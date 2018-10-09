<?php

namespace App\Service\Admin;
use Illuminate\Support\Facades\Facade;
class TestService extends Facade
{
    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return TestService::class;
    }


}


