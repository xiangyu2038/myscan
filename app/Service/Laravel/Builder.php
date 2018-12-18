<?php
namespace App\Service\Laravel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as Builders;
class Builder extends Builders
{
    /**
     * 设置关联关系
     *
     * @param  mixed  $relations
     * @return $this
     */
    public function withxy($relations)
    {

        $eagerLoad = $this->parseWithRelationXy($relations);

        $this->eagerLoad = array_merge($this->eagerLoad, $eagerLoad);

        return $this;
    }

    public function parseWithRelationXy($relations)
    {
       return $this -> parseWithRelationOk($relations);

    }

    public function parseWithRelationOk($relations){

        $temp = [];
        foreach ($relations as $key=> $v){
            $temp [] =  $this->parseWithRelationOkOne($key,$v);
        }

        $a = [];
        foreach ($temp as $v){
            foreach ($v as $key=> $vv){
                $a[$key] = $vv;
            }
        }

        return $a;
    }

    public function parseWithRelationOkOne($or_relation,$relations){

        $temp = [];
        $select = [];
        $son_relation = [];
        $closure = [];

        if(is_array($relations)){
            foreach ($relations as $key => $v) {
               if($v instanceof \Closure){
                   $closure =$v;
               }
                if(is_string($v)){
                    $select[] = $v;
                }
                if(is_array($v)){
                    $son_relation[$or_relation.'.'.$key] = $v;
                }

            }
        }else{
            $closure =$relations;
        }

        if($closure){
            $temp[$or_relation] = $closure;
        }else{
           if(!$select){
               $select = '*';
           }
            $temp[$or_relation] = function ($query)use($select){
                $query->select($select);
            };
        }

        return  array_merge($temp,$this->parseWithRelationOk($son_relation));
    }
}
