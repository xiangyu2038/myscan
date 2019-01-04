<?php

namespace App\Models\Admin;
class StockMoveDetailModel extends BaseModel
{
    protected $table = 'ts_stock_move_detail';

    protected $guarded = [];
/**
 * 构建
 * @param 
 * @return mixed
 */
    public static function build($or_stock_sn,$ta_stock_sn,$data,$stock_move_sn){
        $data  = json_encode($data);

        return compact('or_stock_model','ta_stock_model','data','stock_move_sn');

}

/**
 * 格式化data
 * @param
 * @return mixed
 */
public function getValue(){

    return json_decode($this->data,true);
}


}
