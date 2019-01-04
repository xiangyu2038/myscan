<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockDetailModel extends BaseModel
{
    protected $table = 'ts_stock_detail';
//    public $timestamps = false;
    protected $guarded = [];


    public function fashion(){
        return $this->hasOne('App\Models\Admin\FashionModel','code','fashion_code');
    }

    public function stock(){
        return $this->belongsTo('App\Models\Admin\StockModel','stock_id');
    }
    /**
     * 为自己
     * @param
     * @param
     * @return mixed
     */
    protected function forThis($this_model,$p=null){
        $temp = [];
        $temp['fashion_name'] = $this_model -> fashion_name;
        $temp['fashion_code'] = $this_model -> fashion_code;
        $temp['fashion_size'] = $this_model -> fashion_size;
        $temp['fashion_num'] = $this_model -> fashion_num;
        $temp['fashion_school'] = $this_model -> fashion_num;

        for_this($this_model->fashion,['school']);


        if($p){
            foreach ($p as $v){
                $f = 'has'.ucfirst(convertUnderline($v));
                dd($this -> $f($this_model));
                $temp[$v] = $this -> $f();
            }
        }

        return $temp;
    }

    
    /**
     * 学校名称
     * @param 
     * @return mixed
     */
    public function chen_xiang_yu_fashion($relation,$p){
        return  [$p[0]=>$this->$relation->school];
    }

    /**
     * 所属箱号
     * @param
     * @return mixed
     */
    public function hasBoxSn($this_model){
        return '';
    }

    /**
     * 所属仓库
     * @param
     * @return mixed
     */
    public function hasLocation($this_model){
        return $this_model->stock->type.':  '.$this_model->stock->stock_name.'('.$this_model->stock->stock_sn.')';
    }

    /**
     * 执行构建
     * @param
     * @return mixed
     */
    public function build($stock_sn,$fashion_code,$fashion_size,$fashion_num,$fashion_name=''){
        $created_at = $updated_at = current_time();
        return compact('stock_sn','fashion_code','fashion_size','fashion_num','fashion_name','created_at','updated_at');
    }
    public function getBoxSn(){
        return $this->fashion_code.$this->fashion_size;
    }
    public function getStockSn(){

       return $this->stock->stock_sn;
    }

}
