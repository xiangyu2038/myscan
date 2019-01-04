<?php

namespace App\Models\Admin;

use App\Helper\ObjectHelper;
use Illuminate\Database\Eloquent\Model;
use XiangYu2038\Wish\XY;

class StockModel extends BaseModel
{
    protected $table = 'ts_stock';
//    public $timestamps = false;
    protected $guarded = [];



    public function getTypeAttribute($value)
    {
        switch ($value){
            case 1:
                return '货架';
            case 2:
                return '托盘';
        }
    }



    public function stockBox(){
        return $this->hasMany('App\Models\Admin\StockBoxModel','stock_id');
    }


    public function stockDetails(){
       return $this->hasMany('App\Models\Admin\StockDetailModel','stock_id');
    }

    /**
     * 一个库位下面有多少东西 包含箱子产品
     * @param
     * @return mixed
     */
    public function stockDetail(){
        /////首先把箱子弄过来
        $box_stock = $this->hasStockBox();

        /////其次看看产品
        $stock = $this->hasStockFashion();

        $a = [];
        foreach ($box_stock as $v){
            foreach ($v['box_detail'] as $vv){
                $vv['box_sn'] = $v['box_sn'];
                $a[] = $vv;
            }
        }

        $b = [];
        foreach ($stock as $v){
            $v['box_sn'] = '';
            $b[] = $v;
        }
        $res = array_merge($a,$b);

        return $res;

    }

    /**
     * 一个库位下面有多少箱子
     * @param
     * @return mixed
     */
    protected function hasStockBox(){
       return this($this->stockBox,['box'=>['box_sn','box_detail'=>['fashion_name','fashion_code','fashion_size','fashion_num','fashion'=>['school','chen_xiang_yu_']],'chen_xiang_yu_']]);
    }

    /**
     * 一个库位下面有多少商品
     * @param
     * @return mixed
     */
    protected function hasStockFashion(){
        return this($this->stockDetails,['fashion_name','fashion_code','fashion_size','fashion_num','fashion'=>['school_name','chen_xiang_yu_']]);

    }

/**
 * in  入库产品
 * @param
 * @param
 * @return mixed
 */

public function inFashion($fashion,$type='入库'){
     $fashion_stock = $this -> queryFashionStock($fashion);

    if($fashion_stock === false){
        $fashion['stock_id'] = $this->id;
        $res = ObjectHelper::getInstance(StockDetailModel::class)->create($fashion);
         if(!$res){
             throw new \Exception('入库产品库位不存在的产品失败');
         }
         return ;
     }
    if($type == '盘点'){
        $fashion_num = $fashion['fashion_num'];
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
 * 查询本库位某个产品的库存
 * @param
 * @return mixed
 */
public function queryFashionStock($fashion_info){

    foreach ($this->stockDetails as $v){

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
 * 入库箱子
 * @param
 * @return mixed
 */
public function inBox($box,$type='出库'){
   $stock_box_build = ObjectHelper::getInstance(StockBoxModel::class)->build($this->id,$box);

     if($type == '盘点'){
         $res = StockBoxModel::where('stock_id',$this->id)->where('box_sn',$box)->first();
         if($res){
             return ;
         }
     }

   $res = StockBoxModel::create($stock_box_build);
    if(!$res){
        throw new \Exception('更新库存失败');
    }
    return ;
}

/**
 * 对一个库位的一个产品进行出库操作
 * @param
 * @return mixed
 */
public function outFashion($fashion){
   $fashion_stock = $this -> queryFashionStock($fashion);

    if($fashion_stock === false){
        $fashion['stock_id'] = $this->id;
        $fashion['fashion_num'] =  $fashion['fashion_num']*-1;
        $res = ObjectHelper::getInstance(StockDetailModel::class)->create($fashion);
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
     * 对一个库位的一个产品进行出库操作
     * @param
     * @return mixed
     */
    public function outBox($boxs){
      $res = StockBoxModel::where('stock_id',$this->id)->where('box_sn',$boxs)->first();
       if(!$res){
           throw new \Exception('本库位没有此箱号');
       }
        $res ->delete();///执行删除
        //////
    }
/**
 * stock 清空 一个库位的库存
 * @param
 * @return mixed
 */
public function clear(){
   StockDetailModel::where('stock_id',$this->id)->delete();
   StockBoxModel::where('stock_id',$this->id)->delete();

}
    public function getBoxSnAttribute(){
        return pick_up(this($this->stockBox,['box_sn']),'box_sn');
    }

    /**
     * 一个库位拥有的箱子以及箱子的详情
     * @param
     * @return mixed
     */
    public function hasBoxWithFashionDetail(){

        $box_info = XY::with($this -> stockBox) -> delete('box')->except('stock_id','id')->wish('boxDetail')->except('box_id')->get();

        return $box_info;

    }

    /**
     * 一个库位拥有的箱子以及箱子下面的产品数量
     * @param
     * @return mixed
     */
    public function hasBoxWithFashionNum(){

        $box_info = XY::with($this -> stockBox)->add('fashion_num')->delete('box',true)->except('stock_id')->wish('box')->add('fashion_num')->get();

        return $box_info;
    }

    /**
     * 一个库位拥有的产品及其详情
     * @param
     * @return mixed
     */
    public function hasFashion(){
        //箱号加产品条形码


      $fashion_info =  XY::with($this -> stockDetails)->add('box_sn')->except('stock_id','fashion_code','fashion_size')->get();
    return $fashion_info;
    }


}
