<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class StockInModel extends BaseModel
{
    protected $table = 'ts_stock_in';

    protected $guarded = [];

    public function getStatusAttribute($value){
        switch ($value){
            case 0:
                return '待审核';
            case 1:
                return '已审核';
        }
    }

    /**
     * 检针箱数
     * @param
     * @return mixed
     */
    public function getJZBoxNumAttribute(){
     return   $this -> stockScanBox ->count();
    }

    /**
     * 入库箱子数
     * @param
     * @return mixed
     */
    public function getRKBoxNumAttribute(){
        return   $this -> stockScanBoxRK ->count();
    }
    /**
     * 待搬箱箱数
     * @param
     * @return mixed
     */
    public function getWaitBanBoxNumAttribute(){
        return $this -> getJZBoxNumAttribute() - $this -> getRKBoxNumAttribute();
    }

    /**
     * 已经搬箱箱数
     * @param
     * @return mixed
     */
    public function getHasBoxNumAttribute(){

        return $this -> stockScanBox -> count();
    }

    /**
     * 一个入库单的详细信息
     * @param
     * @return mixed
     */
    public function getDetailAttribute(){

        return  pick_up(this($this -> stockScanStock,['stock'=>['stock_sn','box_sn']]),'stock');
    }

    /**
     * 入库编码
     * @param
     * @return mixed
     */
    public function getStockSnSAttribute(){
       return  pick_up(this($this -> stockScanStock,['stock'=>['stock_sn','chen_xiang_yu_']]),'stock_sn');
    }

    public function stockScanStock(){
        return $this -> hasMany('App\Models\Admin\StockScanStockModel','op_sn','stock_in_sn');
    }


    public function stockScanBox(){
        return $this -> hasMany('App\Models\Admin\StockScanBoxModel','op_sn','stock_in_sn')->where('type','检针');
    }
    public function stockScanBoxRK(){
        return $this -> hasMany('App\Models\Admin\StockScanBoxModel','op_sn','stock_in_sn')->where('type','入库');
    }

    public function stockScanBoxDetail(){
        return $this -> hasMany('App\Models\Admin\StockScanBoxDetailModel','op_sn','stock_in_sn')->where('type','检针');
    }

    /**
     * 添加一个入库单
     * @param
     * @return mixed
     */
    public function add($name,$operate,$j_h_time,$type){
       $build_this = $this -> build($name,$operate,$j_h_time,$type);
       $res = $this -> create($build_this);
        return $res;
    }

    /**
     * build
     * @param
     * @return mixed
     */
    public  function build($name,$operate,$j_h_time,$type){
        $stock_in_sn = $this -> stockInSn();

        return compact('name','operate','j_h_time','type','stock_in_sn');
    }

    /**
     * 构建一个编号
     * @param
     * @return mixed
     */
    public function stockInSn(){
        $order_id_main = date('Ymd') . rand(10, 99);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
//echo $order_id_main;
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

        $order_id=substr($order_id,3,9);
        return 'CKIN_'.$order_id;
    }
    /**
     * 一个入库单的检针箱数 以及 检针产品数
     * @param 
     * @return mixed
     */
    public function needleInfo(){

        $box_num = $this -> stockScanBox ->count();
        $fashion_num = $this -> needleFashionNum();
        return compact('box_num','fashion_num');
    }

    /**
     * 本次盘点扫描的产品数量
     * @param
     * @return mixed
     */
    public function needleFashionNum(){
       $num = 0;
        foreach ($this -> stockScanBoxDetail as $v){
           $num = $num + $v->fashion_num;
       }
       return $num;
    }

    /**
     * 获取编码
     * @param
     * @return mixed
     */
    public function getSnAttribute(){
        return $this -> stock_in_sn;
    }



    





}
