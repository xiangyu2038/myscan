<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class StockBoxModel extends BaseModel
{

    protected $table = 'ts_stock_box';
    protected $guarded = [];


public function stockBoxDetail(){
    return $this->hasMany('App\Models\Admin\StockBoxDetailModel','box_id');
}
public function stock(){
    return $this->belongsTo('App\Models\Admin\StockModel','stock_id');
}

public function box(){
    return $this -> hasOne('App\Models\Admin\BoxModel','box_sn','box_sn');
}

    //////////////////////////////////////////////////////
    /// 北回归线
    /// //////////////////////////////////////////////////

    /**
  * 添加箱子
  * @param
  * @return mixed
  */
 protected function add($num){
     $build_s = [];

     for ($i=0;$i<$num;$i++){
         $build_s[] =  $this->build();
     }
     if(!$build_s){
         throw new \Exception('请输入正确的箱子数量');
     }
     $res =  StockBoxModel::Insert($build_s);
     return $res;
 }



 /**
  * build
  * @param
  * @return mixed
  */
 public function build($stock_id,$box_sn){
     $created_at = $updated_at = current_time();

     return compact('stock_id','box_sn','created_at','updated_at');
 }

 /**
  * 随机制造一个箱子的编号
  * @param
  * @return mixed
  */
 protected function getSn(){
     $code=uniqid();
     $code=substr($code,-4,4);
     return 'B_'.$code;
 }

 /**
  * 一个箱子拥有的产品详细
  * @param
  * @return mixed
  */
 public function hasStockBoxDetail($relation,$temp){
     return this($this->$relation,$temp);
 }

// public function chen_xiang_yu_Stock($relation,$temp){
//     return [$temp[0] =>  $this->$relation->floor.'层楼:  '.$this->$relation->stock_name.'('.$this->$relation->stock_sn.')'];
// }
public function getFashionNum(){

    return $this ->box ->fashion_num;
}

    public function getStockSn(){
        return $this -> stock -> stock_sn;
    }

}

