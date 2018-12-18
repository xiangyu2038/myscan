<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class OfflineCollectModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_offline_collect';

    protected $guarded = [];

    /**
     * 构造保存数据
     * @param
     * @return mixed
     */

    protected function offlineCollect($data){

    }

    /**
     * 构造保存数据 批量的
     * @param
     * @return mixed
     */

    protected function offlineCollectBatch($data){
            $fashions = [];
            foreach ($data as &$v){
                $fashions[] = $v['fashions'];
                unset($v['fashions']);
            }
            unset($v);
        $offline_collect = [];
        foreach ($fashions as $v){
               foreach ($v as $vv){
                   $offline_collect[] =  $vv;
               }
           }

           $offline_user =$data;
        return compact('offline_collect','offline_user');
    }

    public function fashion(){
        return $this->hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }




}
