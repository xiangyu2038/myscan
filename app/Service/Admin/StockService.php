<?php

namespace App\Service\Admin;
use App\Helper\ObjectHelper;
use App\Models\Admin\BoxModel;
use App\Models\Admin\FashionModel;
use App\Models\Admin\StockBoxModel;
use App\Models\Admin\StockDetailModel;
use App\Models\Admin\StockModel;
use App\Models\Admin\StockOutModel;
use App\Models\Admin\StockScanBoxDetailModel;
use App\Models\Admin\StockScanBoxModel;
use App\Models\Admin\StockScanStockDetailModel;
use App\Models\Admin\StockScanStockModel;
use Illuminate\Support\Facades\Facade;
class StockService
{
    /**
     * 写入一条盘点记录
     * @param
     * @param
     * @param
     * @return mixed
     */
    public function addRecord($op_model,$data,$type){
        $this -> type = $type;////操作的类型
        $this -> op_model = $op_model;///操作的单据模型
        foreach ($data as $v){
            /////对扫描的一个单位添加记录
            $this ->con_type = $this->judgeCode($v['container']);

            if($this -> type == '检针'&&$this ->con_type==1){
                throw new \Exception('这是检针操作,请勿入库');
            }

            $this -> model = $this->getModel($v);////操作的容器模型

            $this -> addOneRecord($v);

            if($type == '检针'){
                $this -> stockIn($v);///入库动作
            }elseif($type == '出库'){
                $this->stockOut($v);///出库动作
            }elseif($type == '盘点'||$type == '入库'){

                $this -> stockIn($v);///入库动作
            }

        }

    }

    /**
     * 判断一个编码的类型 三种  箱子 库位 产品编码 1库位编码 2箱子编码 3产品编码
     * @param
     * @return mixed
     */
    public function judgeCode($code){

        if(strstr($code,'_')){
           return 1 ;
        }elseif(strstr($code,'CKXH')){
          return 2;
        }else{
            return 3;
        }
    }

    public function addOneRecord($data){

        ///判断这个容器类型
        if($this ->con_type  == 1){

            $StockCountStockModel = ObjectHelper::getInstance(StockScanStockModel::class);
            $build_stock_count_stock = $StockCountStockModel->build($this->op_model->sn,$this-> model->id,$this->type);

            $res= $StockCountStockModel->create($build_stock_count_stock);

            if(!$res){
                throw new \Exception('保存库位失败'.$data['container']);
            }

            $element = $this -> scanElement($data['element']);

            /////去保存产品信息
            $this->saveStockCountStockDetail($res,$element['fashion_info']);

            ////去保存箱子
            $this -> saveStockCountBox($res,$element['box_info']);

            /////ok
        }elseif($this ->con_type  == 2){
            //////箱子


            if(!$this -> model){
                throw new \Exception('不存的本箱子');
            }

            $stock_count_box_model = ObjectHelper::getInstance(StockScanBoxModel::class );

            $build_stock_count_box = $stock_count_box_model -> build($this->op_model->sn,$this -> model->id,$this->type);

            $res= StockScanBoxModel::create($build_stock_count_box);
            if(!$res){
                throw new \Exception('保存箱子号库位失败'.$data['container']);
            }

            $element = $this -> scanElement($data['element']);
            /////去保存产品信息

            $this->saveStockCountBoxDetail($res,$this -> model->id,$element['fashion_info']);
          /////扫描的是箱子只保存里面的产品信息
        }


    }

    /**
     * 去保存产品信息
     * @param
     * @return mixed
     */
    public function saveStockCountStockDetail($stock_scan_stock_model,$fashion_info){

        foreach ($fashion_info as &$v){
            $v['stock_scan_stock_id'] = $stock_scan_stock_model -> id;
            $v['before_fashion_num'] = $this-> model -> queryFashionStockNum($v);
            $v['type'] = $this->type;
            $v['op_sn'] = $this -> op_model -> sn;
            $v['stock_id'] = $this->model->id;
            $v['created_at'] = current_time();
            $v['updated_at'] = current_time();
        }
        unset($v);

        StockScanStockDetailModel::Insert($fashion_info);

    }
    public function saveStockCountBoxDetail($stock_scan_box_model,$box_id,$fashion_info){


        foreach ($fashion_info as &$v){
            $v['stock_scan_box_id'] = $stock_scan_box_model -> id;
            $v['op_sn'] = $this->op_model->sn;
            $v['box_id'] = $box_id;
            $v['type'] = $this -> type;
            $v['before_fashion_num'] = $this -> model -> queryFashionStockNum($v);
            $v['created_at'] = current_time();
            $v['updated_at'] = current_time();
        }
        unset($v);

        StockScanBoxDetailModel::Insert($fashion_info);


    }

    public function saveStockCountBox($stock_scan_stock_model,$box_info){
        $temp = [];
        $a = [];

        foreach ($box_info as $v){
            $temp['stock_scan_stock_id'] = $stock_scan_stock_model -> id;
            $temp['stock_id'] = $this -> model ->id;
            $temp['box_id'] = BoxModel::where('box_sn',$v)->first()->id;////需要更改
            $temp['type'] = $this->type;
            $temp['op_sn'] = $this -> op_model -> sn;
            $temp['created_at'] = $temp['updated_at'] = current_time();
            $a[] = $temp;
        }


        StockScanBoxModel::Insert($a);

    }


    /**
     * 格式化一个提交的数据作为结果返回
     * @param
     * @param
     * @return mixed
     */

    public function formatScanData($data){

        $temp = [];
        foreach ($data as $v){
            $temp[] = $this ->  formatScanDataOne($v);
        }

        return $temp;
    }

    /**
     * 对扫描的一个容器进行格式化
     * @param
     * @param
     * @return mixed
     */
    public function formatScanDataOne($data){


        $data ['element'] =  $this -> scanElement($data['element']);
        return $data;

    }

    /**
     * 对一个容器进行入库操作
     * @param
     * @return mixed
     */
    public function stockIn($one_scan_data){

            $element = $this -> scanElement($one_scan_data['element']);

        $stock_model =  $this-> model;

        if($this -> con_type == 1){
            ///首先只保存产品
            $this -> stockInWithFashion($stock_model,$element['fashion_info'] );

            ///把箱子也保存了吧
            $this -> stockInWithBox($stock_model,$element['box_info']);
        }elseif($this -> con_type == 2){
            ///仅仅是箱子的入库动作

            $this -> stockInWithFashion($stock_model,$element['fashion_info']);
        }


    }

    /**
     * 对扫描的元素进行处理
     * @param
     * @return mixed
     */

    public function scanElement($element){

        $sp_element = $this -> spScanElement($element);

        $fashion_model = ObjectHelper::getInstance(FashionModel::class);

        foreach ($sp_element['fashion_info'] as &$v){

            $v =$fashion_model -> fashionCode($v);

        }
        unset($v);


        $sp_element['fashion_info'] = $fashion_model -> assortFashion($sp_element['fashion_info']);

        return $sp_element;

    }


    /**
     * 区分一个扫描的元素为箱子或者产品
     * @param
     * @return mixed
     */
    public function spScanElement($element){
        $fashion_info = [];
        $box_info = [];

        foreach ($element as $v){
            $type = $this ->judgeCode($v);
            if($type == 2){
                $box_info[] = $v;
            }elseif($type == 3){
                $fashion_info[] = $v;
            }
        }

        return compact('fashion_info','box_info');
    }

    /**
     * 入库入产品
     * @param
     * @paramstockInWithFashion
     * @return mixed
     */
    public function stockInWithFashion($stock_model,$fashions){

        foreach ($fashions as $v){
            $stock_model->inFashion($v,$this->type);
        }
        return ;
    }

    /**
     * 出库出产品
     * @param
     * @paramstockInWithFashion
     * @return mixed
     */
    public function stockOutWithFashion($stock_model,$fashions){

        foreach ($fashions as $v){
            $stock_model->OutFashion($v);
        }
        return ;
    }

    /**
     * 出库出箱子
     * @param
     * @paramstockInWithFashion
     * @return mixed
     */
    public function stockOutWithBox($stock_model,$boxs){

        foreach ($boxs as $v){
            $stock_model->OutBox($v);
        }
        return ;
    }


    /**
     * 入库的时候保存箱子
     * @param
     * @return mixed
     */
    public function stockInWithBox($stock_model,$boxs){

        foreach ($boxs as $v){
            $stock_model->inBox($v,$this->type);
        }

        return ;
    }
    /**
     * 对一个库位进行出库操作
     * @param
     * @return mixed
     */
public function stockOut($one_scan_data){


    $element = $this -> scanElement($one_scan_data['element']);
    $stock_model =  $this-> model;
    if($this -> con_type == 1){
        $this -> stockOutWithFashion($stock_model,$element['fashion_info'] );

        $this -> stockOutWithBox($stock_model,$element['box_info'] );
    }elseif($this -> con_type == 2){
        ////仅仅对箱子进行出库操作

        $this -> stockOutWithFashion($stock_model,$element['fashion_info'] );
    }


    ////出库出箱子


}

/**
 * 获取本次操作的容器的模型
 * @param
 * @return mixed
 */
public function getModel($data){
    if($this ->con_type == 1){

        $res = StockModel::where('stock_sn',$data['container'])->with('stockDetails')->first();

        if(!$res){
            throw new \Exception('不存在的库位号');
        }
        return  $res;///当前库位模型-
        ///
    }elseif($this ->con_type  == 2){
        $res =  BoxModel::where('box_sn',$data['container'])->with('boxDetail')->first();

        if(!$res){
            throw new \Exception('不存在的箱号');
        }
        return $res;
    }

}

/**
 * 检针动作
 * @param
 * @param
 * @param
 * @return mixed
 */
public function checkNeedle($stock_in_model,$data){
    \DB::beginTransaction();
    try{

        $this -> addRecord($stock_in_model,$data,'检针');

        \DB::commit();

        $return_info = $this -> formatScanData($data);

        $box_sn = $return_info[0]['container'];
        $num = $this -> getOneScanNum($return_info[0]['element']);

        $stock_in_num = $stock_in_model ->needleInfo();

        $stock_in_info = this($stock_in_model,['id','stock_in_sn','name']);
        $stock_in_info['box_num'] =  $stock_in_num['box_num'];
        $stock_in_info['fashion_num'] =  $stock_in_num['fashion_num'];
        $stock_in_info['box_sn'] =  $box_sn;
        $stock_in_info['num'] =  $num;
        return  msg(0,'ok',compact('return_info','stock_in_info'));
    }catch (\Exception $e){

        \DB::rollBack();
        return msg(1,$e->getMessage());
    }

}

/**
 * 入库动作
 * @param
 * @return mixed
 */
public function stockIns($stock_in_model,$data){
    \DB::beginTransaction();
    try{
        $this -> addRecord($stock_in_model,$data,'入库');
        \DB::commit();
        return msg(0,'ok',$this -> formatScanData($data));
    }catch (\Exception $e){
        \DB::rollBack();
        return msg(1,$e->getMessage());
    }


}

/**
 * 获取扫描的一个容器的数量
 * @param
 * @return mixed
 */
public function getOneScanNum($data){
$num = 0;
foreach ($data['fashion_info'] as $v){
    $num = $num + $v['fashion_num'];
}
return $num;
}

/**
 * 处理盘点
 * @param
 * @return mixed
 */
public function stockCount($stock_count_model,$data){

    \DB::beginTransaction();
    try{

        $this->addRecord($stock_count_model,$data,'盘点');

        \DB::commit();
        return msg(0,'ok',$this -> formatScanData($data));
    }catch (\Exception $e){

        \DB::rollBack();
        return  msg(1,$e->getMessage());

    }
}

/**
 * 处理出库
 * @param
 * @return mixed
 */
public function stockOuts($data){
    $stock_out = ObjectHelper::getInstance(StockOutModel::class);
    \DB::beginTransaction();
    try{
        $stock_out_model = $stock_out -> add($data);///生成一个出库单

         $this -> addRecord($stock_out_model,$data,'出库');
        \DB::commit();
        return msg(0,'ok',$this -> formatScanData($data));
    }catch (\Exception $e){
        \DB::rollBack();
       return  msg(1,$e->getMessage());
    }
}

}


