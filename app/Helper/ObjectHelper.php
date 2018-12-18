<?php

namespace App\Helper;



use Illuminate\Support\Facades\Facade;


class ObjectHelper extends Facade
{
    private static $instance = [];

    public static function getInstance($class)
    {
        if (!isset(self::$instance[$class])) {
            self::$instance[$class] = new $class;
        }
        return self::$instance[$class];
    }




    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return ObjectHelper::class;
    }




}


