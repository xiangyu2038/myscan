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




}
