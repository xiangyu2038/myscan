<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class OfflineUserModel extends Model
{
    //
    //指定表名
    protected $table = 'ts_offline_user';

    protected $guarded = [];


    public function offlineCollect(){
        return $this->hasMany('App\Models\Admin\OfflineCollectModel','order_sn','order_sn');
    }


    /**
     * 构造本模型导入的数据 单条
     * @param
     * @param
     * @return mixed
     */
    protected function offlineUser($a,$b,$offline_excel,$excel_type){

        $array = [];
        $array['excel_uid'] = $offline_excel['uid'];

        $array['name'] = $b['name'];
        $array['sex'] = $b['sex'];
        if($excel_type == 'zidingyi'){
            $array['order_sn'] = $this->orderSn();
            $array['export_order_sn'] = $a['order_sn'];
            $array['school'] = $a['school_name'];
            $array['grade'] = '';
            $array['class'] = $a['class_name'];
        }elseif($excel_type == 'taobao'){
              ///淘宝的稍微不一样
            $array['order_sn'] = $b['order_sn'];
            $array['export_order_sn'] = $b['export_order_sn'];
            $array['school'] = $b['school'];
            $array['grade'] = $b['grade'];
            $array['class'] = $b['class'];
        }


        $array['shou_huo_ren'] = $b['shou_huo'];
        $array['phone'] = $b['phone'];
        $array['province'] = $b['province'];
        $array['city'] = $b['city'];
        $array['area'] = $b['area'];
        $array['detail'] = $b['detail'];
        $array['created_at'] = current_time();
        $array['updated_at'] = current_time();

        $array['fashions'] = $this->addOrderSn( $array['order_sn'],$b['fashions']);////给产品加上订单编号

        return $array;

    }

    /**
     * 构造模型导入的数据 批量
     * @param
     * @return mixed
     */

    protected function offlineUserBatch($data,$offline_excel,$excel_type){

        $array = [];
        foreach ($data as $v){
            foreach ($v['excel_data'] as $vv){
                $array[] = $this->offlineUser($v,$vv,$offline_excel,$excel_type);
            }
        }
        return $array;

    }
    
    /**
     * 生成唯一的订单编号
     * @param 
     * @return mixed
     */

public function orderSn(){

    $order_id_main = date('YmdHis') . rand(10000000,99999999);

//订单号码主体长度

    $order_id_len = strlen($order_id_main);

    $order_id_sum = 0;

    for($i=0; $i<$order_id_len; $i++){

        $order_id_sum += (int)(substr($order_id_main,$i,1));

    }

//唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）

    $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
    return $order_id;

}


protected function addOrderSn($order_sn,$data){

    foreach ($data as $key=>&$v){
        if(empty($v['fashion_size']||empty($v['fashion_code']))){
            unset($data[$key]);
            continue;
        }
        $v['order_sn'] = $order_sn;
        $v['created_at'] = current_time();
        $v['updated_at'] = current_time();
    }
    unset($v);
    return $data;
}

/**
 * 根据条件获取导入的用户数据
 * @param 
 * @return mixed
 */
protected  function formatGet($uid,$search_con){

       $data =  $this->where('excel_uid',$uid)->where(function ($query)use($search_con){
           if($key_word = $search_con['key_word']){
               $query->where('order_sn','like','%'.$key_word.'%')->orwhere('name','like','%'.$key_word.'%');
           }
       })->with('offlineCollect')->paginate(config('app.page_size'));
       $links = $data->links('admin.links');

       return compact('data','links');
}

/**
 * 整理下数据格式化一下
 * @param 
 * @return mixed
 */

}
