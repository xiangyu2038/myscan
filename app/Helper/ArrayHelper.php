<?php

namespace App\Helper;



use Illuminate\Support\Facades\Facade;


class ArrayHelper extends Facade
{

    private $all;

    private $count;

    private $curr;


    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return ArrayHelper::class;
    }


   protected function __construct() {

        $this->count = 0;

    }

    protected function add($step) {

        $this->count++;

        $this->all[$this->count] = $step;

    }

    protected function setCurrent($step) {

        reset($this->all);

        for ($i = 1; $i <= $this->count; $i++) {

            if ($this->all[$i] == $step)

                break;

            next($this->all);

        }

        $this->curr = current($this->all);

    }

    protected function getCurrent() {

        return $this->curr;

    }

    protected function getNext() {

        self::setCurrent($this->curr);

        return next($this->all);

    }

    protected function getPrev() {

        self::setCurrent($this->curr);

        return prev($this->all);

    }
}


