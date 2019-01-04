<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use XiangYu2038\Wish\XY;

class FashionModel extends BaseModel
{

    protected $table = 'ts_fashion';
    protected $guarded = [];
    public function getSexAttribute($value)
    {
        switch ($value) {
            case 0:
                return '未知';
                break;
            case 1:
                return '男款';
                break;
            case 2:
                return '女款';
                break;
            case 3:
                return '同款';
                break;
        }
    }

    public function stockBoxDetail(){
        return $this->hasMany('App\Models\Admin\StockBoxDetailModel','fashion_code','code');
    }

    public function boxDetail(){
        return $this->hasMany('App\Models\Admin\BoxDetailModel','fashion_code','code');
    }

    public function stockDetail(){
        return $this->hasMany('App\Models\Admin\StockDetailModel','fashion_code','code');
    }

    public function stockZT(){
        return $this->hasMany('App\Models\Admin\StockZTModel','fashion_code','code');
    }
    public function stockDJ(){
        return $this->hasMany('App\Models\Admin\StockDJModel','fashion_code','code');
    }

    /**
     * 这个产品所拥有的库存
     * @param 
     * @return mixed
     */
    public function stock(){
        ////首先查询现货库存

         $x_h_stock =  $this->hasXHStock();
        ////查询在途库存
         $z_t_stock = $this->hasZTStock();
       ////查询冻结库存
         $d_j_stock = $this->hasDJStock();

        return compact('x_h_stock','z_t_stock','d_j_stock');

    }

    /**
     * 这个商品有多少现货库存
     * @param
     * @return mixed
     */
    public function hasXHStock(){
      /////首先查询在箱子里的库存
        $box_stock = $this->hasBoxStock();
        /////库位里的库存
        $stock = $this->hasStock();


        $res = array_merge($box_stock,$stock);

         //////合并起来
        return $res;

    }

    /**
     * 这个商品有多少现货库存
     * @param
     * @return mixed
     */
    public function hasXHStockNew(){
        /////首先查询在箱子里的库存
        $box_stock = $this->hasBoxStockNew();
        /////库位里的库存
        $stock = $this->hasStockNew();


        $res = array_merge($box_stock,$stock);
        $res =   $this -> assortFashionWithStocks($res);

        //////合并起来
        return $res;

    }

    /**
     * 查询在途库存
     * @param
     * @return mixed
     */
public function hasZTStock(){

   $temp = [];
   foreach ($this->stockZT as $v){
       $temp[] = this($v,['fashion_name','fashion_code','fashion_size','fashion_num']);
   }
   return $temp;
}

/**
 * 查询冻结库存
 * @param
 * @return mixed
 */
    public function hasDJStock(){
        $temp = [];
        foreach ($this->stockDJ as $v){
            $temp[] = this($v,['fashion_name','fashion_code','fashion_size','fashion_num']);
        }
        return $temp;
    }


    /**
     * 这个产品有多少是装在箱子里的
     * @param
     * @return mixed
     */
    public function  hasBoxStock(){
        ////首先查询这个商品
        $box_sn = array_unique(pick_up(this($this->boxDetail,['box'=>['box_sn','chen_xiang_yu_']]),'box_sn'));
         ///所有的在库位中的箱子
        $all_box_sn = StockBoxModel::all()->pluck('box_sn')->toArray();
        $all_box_sn = array_unique($all_box_sn);
        $temp_box_sn = [];////有用的箱子
        foreach ($box_sn as $v){
            if(in_array($v,$all_box_sn)){
                $temp_box_sn[] = $v;
            }
        }

        $fashion = this($this->boxDetail,['fashion_name','fashion_code','fashion_size','fashion_num','box'=>['box_sn','stock_box'=>['stock'=>['stock_sn','stock_name','floor'],'chen_xiang_yu_'],'chen_xiang_yu_']]);

        $temp = [];
        foreach ($fashion as $v){
            if(in_array($v['box_sn'],$temp_box_sn)){
                $temp[] = $v;
            }
        }
       return $temp;

//        return  this($this-> box,['fashion_name','fashion_code','fashion_size','fashion_num','box'=>['box_sn','stock'=>['location','chen_xiang_yu_'],'chen_xiang_yu_']]);
    }

    /**
     * 依赖 ['boxDetail'=>[function($query)use($box_id){
    $query -> whereIn('box_id',$box_id)->select('box_id','fashion_code','fashion_size','fashion_num');
    },'box'=>['id','box_sn','stockBox'=>['stock_id','box_sn','stock'=>['stock_sn','id']]]]]
     * @param
     * @return mixed
     */
    public function  hasBoxStockNew(){
        ////首先查询这个商品在箱子里的产品数量

        $res = XY::with($this)->except('code','box_id','stock_id')->delete('stockDetail',true)->wish('boxDetail')->add('stock_sn')->delete('box',true)->except('box_id')->wish('box')->add('stock_sn')->wish('stockBox')->add('stock_sn')->get()->toArray();
        $temp = [];
        foreach ($res['box_detail'] as $v){

            $v['fashion_name'] = $res['real_name'];
                 $temp [] = $v;

        }

        return $temp ;
    }

    /**
     * 这个产品直接放在库位里的库存
     * @param
     * @return mixed
     */
    public function hasStock(){
        return  this($this-> stockDetail,['fashion_name','fashion_code','fashion_size','fashion_num','stock'=>['stock_sn','stock_name','floor'],'box_sn']);
    }

    public function hasStockNew(){

        $res = XY::with($this)->delete('boxDetail',true)->wish('stockDetail')->except('stock_id','id')->add('stock_sn')->delete('stock')->get()->toArray();
        $temp = [];
        foreach ($res['stock_detail'] as $v){
            $v['fashion_name'] = $res['real_name'];
            $temp [] = $v;

        }

       return $temp;
    }

    /**
     * 本产品说拥有的账面数量
     * @param
     * @return mixed
     */
    public function hasPaperNum(){
        $stock = $this->hasXHStock($this);

        $box_stock_num = isset($stock['box_stock']['fashion_num'])?$stock['box_stock']['fashion_num']:0;
        $stock_num = isset($stock['stock']['fashion_num'])?$stock['stock']['fashion_num']:0;

         return  $box_stock_num + $stock_num;
    }


    /**
     * 对扫描枪进来的编号进行格式化
     * @param
     * @param
     * @return mixed
     */
    public function fashionCode($scan_fashion,$num=1){
        $scan_fashion=trim($scan_fashion);
        $a = strripos($scan_fashion,'A');
        $array=[];
        $array['fashion_code']=substr($scan_fashion,0,$a+1);
        $array['fashion_size']=substr($scan_fashion,$a+1);
        $array['fashion_num']=$num;

        return $array;

    }

    /**
     * 对相同尺码 相同编码的数据进行去重
     * @param
     * @return mixed
     */

    public function assortFashion($all_fashions){
        $array = [];

        foreach ($all_fashions as $v){
            $array[$v['fashion_code']][$v['fashion_size']][]=$v;
        }

        $one = function ($data){
            $num = 0 ;
            foreach ($data as $v){
                $num = $num + $v['fashion_num'];
            }

            $data[0]['fashion_num'] = $num;
            return $data [0];
        };
        /////计算个数
        $new_array = [];
        foreach ($array as $v){
            foreach ($v as $vv){
                $new_array[]=$one($vv);
            }
        }
        return $new_array;


    }


    public function assortFashionWithStock($all_fashions){
        $array = [];

        foreach ($all_fashions as $v){
            $array[$v['fashion_code']][$v['fashion_size']][$v['stock']['stock_sn']][]=$v;
        }

        $one = function ($data){
            $num = 0 ;
            foreach ($data as $v){
                $num = $num + $v['fashion_num'];
            }

            $data[0]['fashion_num'] = $num;
            return $data [0];
        };
        /////计算个数
        $new_array = [];
        foreach ($array as $v){
            foreach ($v as $vv){
               foreach ($vv as $vvv)
                $new_array[]=$one($vvv);
            }
        }

        return $new_array;


    }
    public function assortFashionWithStocks($all_fashions){
        $array = [];

        foreach ($all_fashions as $v){
            $array[$v['fashion_code']][$v['fashion_size']]['stock_sn'][]=$v;
        }

        $one = function ($data){
            $num = 0 ;
            foreach ($data as $v){
                $num = $num + $v['fashion_num'];
            }

            $data[0]['fashion_num'] = $num;
            return $data [0];
        };
        /////计算个数
        $new_array = [];
        foreach ($array as $v){
            foreach ($v as $vv){
               foreach ($vv as $vvv)
                $new_array[]=$one($vvv);
            }
        }

        return $new_array;


    }
}