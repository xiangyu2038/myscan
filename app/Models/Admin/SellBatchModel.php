<?php

namespace App\Models\Admin;

use App\Service\Admin\MyScanService;
use Illuminate\Database\Eloquent\Model;

class SellBatchModel extends BaseModel
{
    //
    //指定表名
    protected $table = 'ts_sell_batch';

//指定主键
    protected $primaryKey = 'id';

//是否开启时间戳
    public $timestamps = true;

    protected $guarded = [];
//设置时间戳格式为Unix
   // protected $dateFormat = 'U';


///属性转换
    //    protected $casts = [
//        'id' => 'boolean',
//    ];

/**
 * 这一个批次包含几个包裹
 * @param 
 * @return mixed
 */

public function sellBatchPrintEnd(){
    return $this->hasMany('\App\Models\Admin\SellBatchPrintEndModel','sell_batch_id');
}

/**
 * 一个批次要发货的产品 如果有的话 if
 * @param
 * @return mixed
 */
public function batchDetails(){
    return $this->hasMany('\App\Models\Admin\SellBatchDetailsModel','sell_batch_id');
}


public function scanCn(){
    return $this->hasMany('\App\Models\Admin\ScanCnModel','order_batch_id');
}

    /**
     * 获取用户的名字
     *
     * @param  string  $value
     * @return string
     */
    public function getOutStatusAttribute($value)
    {
        switch ($value){
            case 0:
                return '未出库';
            case 1:
                return '出库';
        }
    }
    public function getBatchSnAttribute($value)
    {
        return strtoupper($value);
    }


    /**
     * 某个订单每一批次下面已经发货的产品
     * @param 本实例模型
     * @param 条形码模型
     * @return mixed
     */

    protected function batchData($this_model,$sell_batch_end_model){
              $scan_cn_model = $sell_batch_end_model->scanStudent;
              $package_detail = ScanStudentModel::packageDetail($scan_cn_model);
               
              $batch_data = $this->forThis($this_model);
              
              $wu_liu = $this->wuLiu($sell_batch_end_model);

              return compact('package_detail','batch_data','wu_liu');

    }

    /**
     * 对自己进行格式化
     * @param
     * @return mixed
     */
protected function forThis($this_model){
    $temp = [];
    $temp['ps_sn'] = $this_model->batch_sn;
    $temp['out_status'] = $this_model->out_status;
    $temp['out_time'] = $this_model->out_time;
    $temp['note'] = $this_model->note;
    return $temp;
}

/**
 * 物流信息
 * @param 
 * @return mixed
 */
protected function wuLiu($sell_batch_end_model){
    $temp = [];
    $temp ['kuaidi'] =$sell_batch_end_model->kuaidi;
    $temp ['kuaidi_company'] =$sell_batch_end_model->kuaidi_company;
    return $temp;
}

/**
 * 一个批次所有的缺货的数据
 * @param
 * @return mixed
 */
protected function queData($this_model){
    $package  = $this_model->sellBatchPrintEnd;
   $temp = [];///临时变量 接收缺货的数据
    foreach ($package as $v){
        $temps =  SellBatchPrintEndModel::que($v);
        foreach ($temps as $vv){
         if($vv){
             $temp[] =$vv;
         }
        }
    }
return $temp;

}

/**
 *  新增一个批次
 * @param  本批次需要发货的数据
 * @param   发货时间
 * @param  发货备注
 * @param  订单编号结合
 * @param  数据来源
 * @return mixed
 */
protected function add($will_fa_fashion,$fa_huo_time,$note,$order_sns,$source,$order_id=null,$que_sn=null){
   return   MyScanService::addBatch($will_fa_fashion,$fa_huo_time,$note,$order_sns,$source,$order_id,$que_sn);
}
/**
 * 一个批次的格式化信息
 * @param
 * @return mixed
 */
public function formatInfo(){
    $array = [];

    $array['batch_id'] = $this->id;
    $array['batch_sn'] = $this->batch_sn;
    $array['batch_note'] = $this->note;
    $array['batch_out_status'] = $this->out_status;
    $array['batch_create_at'] = $this->created_at;
    $array['batch_source'] = $this->source;
    return $array;
}

/**
 * 一个批次的所拥有的箱子的数量
 * @param
 * @return mixed
 */
public function getBoxNum(){
    return count($this -> scanCn);
}
/**
 * 一个批次已扫描箱子数量
 * @param
 * @return mixed
 */
    public function getScanBoxNum(){

        $array=[];
        foreach ($this -> scanCn as $v){
            if($v['element']==0){
                continue;
            }
            $array[]=$v;
        }
        return count($array);

    }

    /**
     * 一个批次的所拥有的包裹的数量
     * @param
     * @return mixed
     */
    public function getPackageNum(){

        return count($this -> sellBatchPrintEnd);
    }

    /**
     * 一个批次所扫描的包裹
     * @param
     * @return mixed
     */
    public function getPackage(){
        $package = [];

        foreach ($this -> scanCn as $box){
            $package [] = $box -> getPackage();
        }

        return collapse($package);

    }



    /**
     * 一个批次已扫描包裹数量
     * @param
     * @return mixed
     */
    public function getScanPackageNum(){

        $scan_package_num = 0;

      foreach ($this -> getPackage() as $package){
          if($package -> isScan()){
              $scan_package_num =  $scan_package_num + 1;
          }
      }

   return $scan_package_num;
    }

    /**
     * 一个批次的所拥有的产品数量
     * @param
     * @return mixed
     */
    public function getFashionNum(){
return '5';
    }
    /**
     * 一个批次已扫描产品数量
     * @param
     * @return mixed
     */
    public function getScanFashionNum(){
          return count($this -> getScanFashion());
    }
    /**
     * 一个批次已经扫描的产品
     * @param
     * @return mixed
     */
    public function getScanFashion(){
        $scan_fashion = [];
        foreach ($this -> getPackage() as $package){
            $scan_fashion[] = $package -> getFashion();
        }
        return collapse($scan_fashion);
    }
    /**
     * 批次的通用信息 用于箱子的通用打印
     * @param
     * @return mixed
     */
    public function boxCommonInfo($order_xiao_shou_sn,$batch_data,$custom){
        $array=[];
        $array['order_xiao_shou_sn']=$order_xiao_shou_sn;
        $array['pei_song_sn']=$batch_data -> batch_sn;
        $array['custom_name']=$custom;
        return $array;
    }

}
