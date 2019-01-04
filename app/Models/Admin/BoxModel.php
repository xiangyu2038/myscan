<?php

namespace App\Models\Admin;

use App\Helper\ObjectHelper;
use Illuminate\Database\Eloquent\Model;

class BoxModel extends BaseModel
{
    protected $table = 'ts_box';
//    public $timestamps = false;
    protected $guarded = [];

    protected $belong_to_stock=null;///所有绑定库位的箱子

    public function boxDetail(){
        return $this -> hasMany('App\Models\Admin\BoxDetailModel','box_id');
    }



    /**
     * 查询本箱子某个产品的库存
     * @param
     * @return mixed
     */
    public function queryFashionStock($fashion_info){

        foreach ($this->BoxDetail as $v){

            if($v->fashion_code.$v->fashion_size == $fashion_info['fashion_code'].$fashion_info['fashion_size']){

                return $v;
            }
        }
        return false;
    }

    public function queryFashionStockNum($fashion_info){
        $res = $this -> queryFashionStock($fashion_info);
        if($res===false){
           return 0;
        }
        return $res->fashion_num;

    }

    /**
     * 对一个库位的一个产品进行出库操作
     * @param
     * @return mixed
     */
    public function outFashion($fashion){
        $fashion_stock = $this -> queryFashionStock($fashion);

        if($fashion_stock === false){
            $fashion['box_id'] = $this->id;
            $fashion['fashion_num'] = $fashion['fashion_num']*-1;
            $res = ObjectHelper::getInstance(BoxDetailModel::class)->create($fashion);
            if(!$res){
                throw new \Exception('出库产品库位不存在的产品失败');
            }
            return ;
        }

        $fashion_num = $fashion_stock->fashion_num - $fashion['fashion_num'];//为出库
        $res = $fashion_stock->update(['fashion_num'=>$fashion_num]);
        if(!$res){
            throw new \Exception('更新库存失败');
        }
        return ;
        //////
    }

    /**
     * in  入库产品
     * @param
     * @return mixed
     */

    public function inFashion($fashion,$type){
        $fashion_stock = $this -> queryFashionStock($fashion);

        if($fashion_stock === false){
            $fashion['box_id'] = $this->id;
            $res = ObjectHelper::getInstance(BoxDetailModel::class)->create($fashion);
            if(!$res){
                throw new \Exception('入库产品库位不存在的产品失败');
            }
            return ;
        }
   if($type == '盘点'){
       $fashion_num =  $fashion['fashion_num'];
   }else{

       $fashion_num = $fashion_stock->fashion_num + $fashion['fashion_num'];
   }

        $res = $fashion_stock->update(['fashion_num'=>$fashion_num]);
        if(!$res){
            throw new \Exception('更新库存失败');
        }
        return ;
        //////
    }
    /**
     * 添加一个自己
     * @param
     * @return mixed
     */
    public function add($num){
        $build_s = [];

        for ($i=0;$i<$num;$i++){
            $build_s[] =  $this->build();
        }

        if(!$build_s){
            throw new \Exception('请输入正确的箱子数量');
        }
        $res =  BoxModel::Insert($build_s);
        return $res;
    }

    public function build(){
        $box_sn = $this -> getSn();
        $created_at = $updated_at = current_time();

        return compact('box_sn','created_at','updated_at');
    }
    /**
     * 随机制造一个箱子的编号
     * @param
     * @return mixed
     */
    protected function getSn(){

        $order_id_main = date('Ymd') . rand(10, 99);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
//echo $order_id_main;
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);




        $data =substr($order_id,2);


        return 'CKXH'.$data;
    }

    public function stockBox(){

        return $this -> belongsTo('App\Models\Admin\StockBoxModel','box_sn','box_sn');
    }
    public function getFashionNum(){
       $temp_num = 0;
        foreach ($this -> boxDetail as $v){
           $temp_num = $temp_num +$v -> fashion_num;
       }
      return $temp_num;
    }

    /**
     * 所有绑定库位的箱子
     * @param
     * @return mixed
     */
    public function  belongToStock(){

        if(!$this->belong_to_stock){
            $all_box_sn = StockBoxModel::all()->pluck('id')->toArray();
           return  array_unique($all_box_sn);
        }
       return  $this->belong_to_stock;

    }

/**
 * 所属的库位
 * @param
 * @return mixed
 */
    public function getStockSn(){
        return $this -> stockBox -> stock_sn;
    }

}
