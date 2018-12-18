<?php

namespace App\Helper;



use Illuminate\Support\Facades\Facade;


class XyHelper extends Facade
{

    protected $with;
    protected $current ='';
    protected  $wish =[] ;

    public function with($with){
        $this -> with = $with;
        return $this;
    }



public function wish($wish){
    $this ->wish[$wish]['add'] = [];
    $this ->wish[$wish]['only'] = [];
    $this ->wish[$wish]['except'] = [];
    $this ->current = $wish;
  return $this;
}

public function add($add){
     if(!$this -> wish){
         $this ->wish('self');
     }

    $this->wish[$this ->current]['add'] = array_merge($this->wish[$this ->current]['add'],func_get_args());
    return $this;
}
public function except(){
    if(!$this -> wish){
        $this ->wish('self');
    }
    $this->wish[$this ->current]['except'] = array_merge($this->wish[$this ->current]['except'],func_get_args());
    return $this;
}

public function only(){
    if(!$this -> wish){
        $this ->wish('self');
    }
    $this->wish[$this ->current]['only'] = array_merge($this->wish[$this ->current]['only'],func_get_args());
    return $this;
}

public function get(){
    if($this->with instanceof \Illuminate\Database\Eloquent\Model){
        $this -> self($this->with);///首先对本身进行处理

        $this -> relation($this -> with);
        return $this -> with;
    }

    foreach ($this->with as $v){
        $this -> self($v);///首先对本身进行处理
        $this -> relation($v);
   }

   return $this->with;
}

public function setAdd($model,$add){

    foreach ($add as $v){
        $model->setAttribute($v,'');
    }

}
public function setOnly($model,$only){
   if($only){
       $model->setRawAttributes([]);///清空
   }
    foreach ($only as $v){
        $model->setAttribute($v,$model->getOriginal($v));
    }

}

public function setExcept($model,$except){

    foreach ($except as $v){
       unset($model->$v);
    }
}

public function self($model){

    if(isset($this -> wish['self'])){
        $this -> setAll($model,'self');
    }

}

public function relation($model){

    foreach ( $model -> getrelations() as $key=> $v){
        if(array_key_exists($key,$this -> wish)) {

                if ($v instanceof \Illuminate\Database\Eloquent\Model) {
                    $this->setAll($v, $key);
                    $this->relation($v);
                }

            foreach ($v as $vv) {

                $this->setAll($vv, $key);
                $this->relation($vv);


            }
        }




    }
}

public function setAll($model,$wish){

    $this -> setAdd($model,$this -> wish[$wish]['add']);
    $this -> setOnly($model,$this -> wish[$wish]['only']);
    $this -> setExcept($model,$this -> wish[$wish]['except']);
}

}


