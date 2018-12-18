<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 10:24
 */
namespace App\Admin\Models;
use Illuminate\Database\Eloquent\Model;
class StoreFashionModel extends BaseModel{
    protected $table = 'ts_fashion_stock_sh';
//    public $timestamps = false;
    protected $guarded = [];

    /**
     * 关联操作员【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function operator(){
        return $this->belongsTo('Model\Manager','operator_id')->select('id','name');
    }

    /**
     * 关联尺码【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function size(){
        return $this->belongsTo('Model\Size','fashion_size','code')->select('id','value','name','code');
    }


    /**
     * 关联产品
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fashion(){
        return $this->hasOne('Model\Fashion','code','fashion_code');
    }

    /**
     * 关联商品库存总表【上海仓】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function fashion_size_stock_simple_shanghai(){
        return $this->hasMany('Model\StoreFashion','fashion_code','fashion_code')
            ->with('size')
            ->where('status', 1)
            ->select('id','fashion_code','fashion_size','fashion_num')
            ->orderBy('fashion_code','DESC')
            ->orderBy('fashion_size','ASC');
    }

//    public function getStatusAttribute($value){
//        switch ($value){
//            case 0:
//                return '删除';
//                break;
//            case 1:
//                return '正常';
//                break;
//        }
//    }

    public function get_data_lists($arrSearch,$arrAdmin,$arrPage)
    {
        if($arrAdmin['admin_group_id'] <= 0)
            return ['state'=>1,'content'=>'用户分组ID不能为空'];
        if($arrAdmin['admin_id'] <= 0)
            return ['state'=>2,'content'=>'用户ID不能为空'];
        $data = StoreFashion::with('fashion','fashion_size_stock_simple_shanghai')
            //按搜索条件查询
            ->where(
                function($query)
                use($arrSearch) {
                    if (isset($arrSearch['key_word']) && $arrSearch['key_word']) {
                        $query->orWhereHas('fashion',
                            function ($query)
                            use($arrSearch){
                                $query->where('code', 'like', '%'.$arrSearch['key_word'].'%');
                            })
                            ->orWhereHas('fashion',
                                function ($query)
                                use($arrSearch){
                                    $query->where('old_code', 'like', '%'.$arrSearch['key_word'].'%');
                                })
                            ->orWhereHas('fashion',
                                function ($query)
                                use($arrSearch){
                                    $query->where('name', 'like', '%'.$arrSearch['key_word'].'%');
                                })
                            ->orWhereHas('fashion',
                                function ($query)
                                use($arrSearch){
                                    $query->where('EN_name', 'like', '%'.$arrSearch['key_word'].'%');
                                })
                            ->orWhereHas('fashion',
                                function ($query)
                                use($arrSearch){
                                    $query->where('style_name', 'like', '%'.$arrSearch['key_word'].'%');
                                })
                            ->orWhereHas('fashion',
                                function ($query)
                                use($arrSearch){
                                    $query->where('real_name', 'like', '%'.$arrSearch['key_word'].'%');
                                })
                            ->orWhereHas('fashion',
                                function ($query)
                                use($arrSearch){
                                    $query->where('school', 'like', '%'.$arrSearch['key_word'].'%');
                                })
                            ->orWhere('fashion_code', 'like', '%'.$arrSearch['key_word'].'%')
                        ;
                    }
                }
            )

            ->groupBy('fashion_code')
            ->orderBy('fashion_code','ASC')
            ->paginate($arrPage['per_page'],$arrPage['current_page'])
            ->toArray();

        if($data['total'] == 0)
            return ['state'=>-1,'content'=>'暂无数据'];
        else
            return ['state'=>0,'content'=>$data];
    }

    /**
     * 添加一个产品的库存数据【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @param $nFashionId
     * @return mixed
     */
    public function addOneFashionStock($nFashionId)
    {
        //查询尺码表
        $arrSize = Size::where('status',1)->orderBy('id','ASC')->get()->toArray();

        //组合要添加的数据
        $nDateTime = get_cur_datetime();
        $arrData = [];
        foreach ($arrSize as $key=>$value)
        {
            $arrData[] =
                [
                    'fashion_id'=>$nFashionId,
                    'size_id'=>$value['id'],
                    'status'=>1,
                    'created_at'=>$nDateTime,
                    'updated_at'=>$nDateTime,
                    'operator_id'=>get_operator_id()
                ];
        }

        return StoreFashion::insert($arrData);
    }

    /**
     * 新增一个库位下一个产品的库存【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @param $data
     * @return array|static
     */
    public function addGoodsStore($data)
    {
        //先查询是否已经添加过产品库位库存
        $nId = $data['store_fashion_id'];
        $res = StoreHouseFashionDetail::checkIfThereIsStoreHouseFashionDetail($data['store_house_id'],$nId);
        if($res['state'] == 1)
            return $res;

        //开启事务
        $oTransaction = StoreHouseFashionDetail::get_Transaction_obj();
        $oTransaction->beginTransaction();

        //添加产品库位库存
        $res = StoreHouseFashionDetail::create($data);
        if(empty($res))
        {
            $oTransaction->rollBack();
            return ['state'=>1,'content'=>'添加失败1'];
        }

        //查询产品库位库存的总和
        $res = StoreHouseFashionDetail::where('store_fashion_id',$nId)->where('status',1)->select('id','store_house_id','store_fashion_id','usable_num','freeze_num')->get();

        //同步更新产品总库存
        $res = StoreFashion::where('id',$nId)->where('status',1)->update(['huojia_usable_num'=>$res->sum('usable_num'),'huojia_freeze_num'=>$res->sum('freeze_num')]);
        if($res === false)
        {
            $oTransaction->rollBack();
            return ['state'=>1,'content'=>'总库存更新失败'];
        }

        //提交事务
        $oTransaction->commit();
        return ['state'=>0,'content'=>'添加成功'];
    }

    /**
     * 修改一个库位下一个产品的库存【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @param $data
     * @return array|static
     */
    public function editGoodsStore($nId,$data)
    {
        //开启事务
        $oTransaction = StoreHouseFashionDetail::get_Transaction_obj();
        $oTransaction->beginTransaction();

        //修改产品库位库存
        $res = StoreHouseFashionDetail::where('id',$nId)->where('status',1)->update($data);
        if($res=== false)
        {
            $oTransaction->rollBack();
            return ['state'=>1,'content'=>'修改失败'];
        }

        //查询产品库位库存的总和
        $res = StoreHouseFashionDetail::where('store_fashion_id',$data['store_fashion_id'])->where('status',1)->select('id','store_house_id','store_fashion_id','usable_num','freeze_num')->get();

        //同步更新产品总库存
        $res = StoreFashion::where('id',$data['store_fashion_id'])->where('status',1)->update(['huojia_usable_num'=>$res->sum('usable_num'),'huojia_freeze_num'=>$res->sum('freeze_num')]);
        if($res === false)
        {
            $oTransaction->rollBack();
            return ['state'=>1,'content'=>'总库存更新失败'];
        }
        //提交事务
        $oTransaction->commit();
        return ['state'=>0,'content'=>'修改成功'];
    }

    /**
     * 拣一个产品一种类型的货 货架冻结或者托盘冻结
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @param $nFashionId
     * @param $nType
     * @param $data
     * @return array
     */
    public function pickingOneFashion($nFashionId,$nType,$data)
    {
        $nDateTime = get_cur_datetime();
        switch ($nType)
        {
            case 3:
                $sField = 'tuopan_freeze_num';
                break;
            case 4:
                $sField = 'huojia_freeze_num';
                break;
        }

        $arrData = [];
        foreach ($data as $value)
        {
            $arrData[] =
                [
                    'order_po_fashion_size_detail_id'=>$value['id'],
                    'type'=>$nType,
                    'num'=>$value['num'],
                    'operator_id'=>get_operator_id(),
                    'created_at'=>$nDateTime,
                    'updated_at'=>$nDateTime,
                ];

            ////托盘冻结库存转到临时库存区
            //减去托盘冻结库存
            $resDecrement = StoreFashion::where('status',1)->where('fashion_id',$nFashionId)->where('size_id',$value['size_id'])->where($sField,'>=',$value['num'])->decrement($sField,$value['num']);
            if($resDecrement === false)
            {
                return ['state'=>1,'content'=>'产品库存转移失败1'];
            }

            //增加产品临时库存
            $resIncrement = StoreFashion::where('status',1)->where('fashion_id',$nFashionId)->where('size_id',$value['size_id'])->increment('temp_num',$value['num']);
            if($resIncrement === false)
            {
                return ['state'=>1,'content'=>'产品库存转移失败2'];
            }

        }

        //记录货架拣货数据
        if(OrderPoFashionSizeDetailIn::insert($arrData) === false)
        {
            return ['state'=>1,'content'=>'拣货确认失败1'];
        }

    }

//    /**
//     * 获取产品库存列表【Old】
//     *
//     * @author wumengmeng <wu_mengmeng@foxmail.com>
//     * @param $arrSearch
//     * @param $arrAdmin
//     * @param $arrPage
//     * @return array
//     */
//    public function getFashionStock($arrSearch,$arrAdmin,$arrPage)
//    {
//        if($arrAdmin['admin_group_id'] <= 0)
//            return ['state'=>1,'content'=>'用户分组ID不能为空'];
//        if($arrAdmin['admin_id'] <= 0)
//            return ['state'=>2,'content'=>'用户ID不能为空'];
//        $data = StoreFashion::with('operator','fashion','size')
//
//            //按搜索条件查询
////            ->where(
////                function($query)
////                use($arrSearch) {
////                    if (isset($arrSearch['key_word']) && $arrSearch['key_word']) {
////                        $query
////                            ->orWhere('code', 'like', '%'.$arrSearch['key_word'].'%')
////                            ->orWhere('name', 'like', '%'.$arrSearch['key_word'].'%');
////                    }
////                }
////            )
//            ->where(
//                function($query)
//                use($arrSearch) {
//                    if (isset($arrSearch['key_word']) && $arrSearch['key_word']) {
//                        $query->orWhereHas('fashion',
//                            function ($query)
//                            use($arrSearch){
//                                $query->where('code', 'like', '%'.$arrSearch['key_word'].'%');
//                            });
//                    }
//                }
//            )
//            ->where('status', 1)
////            ->select('id','name','code','old_code','sex','created_at','operator_id')
//            ->orderBy('fashion_id','DESC')
//            ->orderBy('size_id','ASC')
//            ->paginate($arrPage['per_page'],$arrPage['current_page'])
//            ->toArray();
//
//        if($data['total'] == 0)
//            return ['state'=>-1,'content'=>'暂无数据'];
//        else
//            return ['state'=>0,'content'=>$data];
//    }


//    /**
//     * 获取库存列表【Old】
//     *
//     * @author wumengmeng <wu_mengmeng@foxmail.com>
//     * @param $arrSearch
//     * @param $arrAdmin
//     * @param $arrPage
//     * @return array
//     */
//    public function getTotalFashionStock($arrSearch,$arrAdmin,$arrPage)
//    {
//        if($arrAdmin['admin_group_id'] <= 0)
//            return ['state'=>1,'content'=>'用户分组ID不能为空'];
//        if($arrAdmin['admin_id'] <= 0)
//            return ['state'=>2,'content'=>'用户ID不能为空'];
//        $data = StoreFashion::with('operator','fashion','store_fashion.size','store_fashion.store_fashion_detail')
//
//            //按搜索条件查询
////            ->where(
////                function($query)
////                use($arrSearch) {
////                    if (isset($arrSearch['key_word']) && $arrSearch['key_word']) {
////                        $query
////                            ->orWhere('code', 'like', '%'.$arrSearch['key_word'].'%')
////                            ->orWhere('name', 'like', '%'.$arrSearch['key_word'].'%');
////                    }
////                }
////            )
//            ->where(
//                function($query)
//                use($arrSearch) {
//                    if (isset($arrSearch['key_word']) && $arrSearch['key_word']) {
//                        $query->orWhereHas('fashion',
//                            function ($query)
//                            use($arrSearch){
//                                $query->where('code', 'like', '%'.$arrSearch['key_word'].'%');
//                            });
//                    }
//                }
//            )
//            ->where('status', 1)
////            ->select('id','name','code','old_code','sex','created_at','operator_id')
//            ->groupBy('fashion_id')
//            ->orderBy('fashion_id','DESC')
//            ->orderBy('size_id','ASC')
//            ->paginate($arrPage['per_page'],$arrPage['current_page'])
//            ->toArray();
//
//        if($data['total'] == 0)
//            return ['state'=>-1,'content'=>'暂无数据'];
//        else
//            return ['state'=>0,'content'=>$data];
//    }

//    /**
//     * 处理查询的总库存数据【NEW】
//     *
//     * @author wumengmeng <wu_mengmeng@foxmail.com>
//     * @param $data
//     * @return mixed
//     */
//    public function dealWithTotalFashionStock($data)
//    {
//        if($data['state'] != 0)
//        return $data;
//        else
//        {
//            if($data['content']['total'] == 0)
//                return $data;
//            else
//            {
//                //尺码->托盘货架在途
////                foreach ($data['content']['data'] as $key=>$value)
////                {
////                    //重新整理数组 将数组的key全部修改成所需要的格式
////                    $value['store_fashion'] = change_all_key_of_array($value['store_fashion'],'size_id');
////
////                    foreach ($value['store_fashion'] as $k=>$v)
////                    {
////                        $v['store_fashion_detail'] = change_all_key_of_array($v['store_fashion_detail'],'type');
////                        $value['store_fashion'][$k] = $v;
////                    }
////                    $data['content']['data'][$key] = $value;
////                }
//
//                //托盘货架在途->尺码
//                foreach ($data['content']['data'] as $key=>$value)
//                {
//                    $arrData = ['tuopan'=>[],'huojia'=>[],'zaitu'=>[]];
//                    foreach ($value['store_fashion'] as $k=>$v)
//                    {
//                        foreach ($v['store_fashion_detail'] as $ka=>$va)
//                        {
//                            $va['size_id'] = $v['size_id'];
//                            $va['fashion_id'] = $v['fashion_id'];
//                            switch ($va['type'])
//                            {
//                                case 1:
//                                    $arrData['tuopan'][$va['size_id']] = $va;
//                                    break;
//                                case 2:
//                                    $arrData['huojia'][$va['size_id']] = $va;
//                                    break;
//                                case 3:
//                                    $arrData['zaitu'][$va['size_id']] = $va;
//                                    break;
//                                case 4:
//                                    $arrData['lingshou'][$va['size_id']] = $va;
//                                    break;
//                                case 5:
//                                    $arrData['daifenpei'][$va['size_id']] = $va;
//                                    break;
//                            }
//                        }
//                        $value['store_fashion'] = $arrData;
//                    }
//                    $data['content']['data'][$key] = $value;
//                }
//
//
//            }
//            return $data;
//        }
//    }

//    /**
//     * 组合要批量添加的产品库存数组
//     *
//     * @author wumengmeng <wu_mengmeng@foxmail.com>
//     * @param $nFashionId
//     * @return array
//     */
//    public function getStoreFashionInsertData($nFashionId)
//    {
//        //查询尺码表
//        $arrSize = Size::where('status',1)->orderBy('id','ASC')->get()->toArray();
//
//        //组合要添加的数据
//        $arrData = [];
//        foreach ($arrSize as $key=>$value)
//        {
//            $arrData[] =
//                [
//                    'fashion_id'=>$nFashionId,
//                    'size_id'=>$value['id'],
//                    'status'=>1,
//                    'operator_id'=>get_operator_id()
//                ];
//        }
//
//        return $arrData;
//    }

    /**
     * 改变单件产品 单个尺码的库存
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @param $nType
     * @param $s_fashion_code
     * @param $s_fashion_size
     * @param $n_num
     * @return array
     */
    public function change_stock($nType,$s_fashion_code,$s_fashion_size,$n_num)
    {
        $res = StoreFashion::where('fashion_code',$s_fashion_code)->where('fashion_size',$s_fashion_size)->first();
        $s_msg = '产品编码：'.$s_fashion_code.'-规格：'.$s_fashion_size;

        switch ($nType)
        {
//            case 10:
//              return  StoreFashion::where('fashion_id',$n_fashion_id)->where('size_id',$n_size_id)->increment('tuopan_usable_num',$n_num);
//              break;
//            case 11:
//                return  StoreFashion::where('fashion_id',$n_fashion_id)->where('size_id',$n_size_id)->increment('huojia_usable_num',$n_num);
//                break;
//            case 20:
//                return  StoreFashion::where('tuopan_usable_num','>=',$n_num)->where('fashion_id',$n_fashion_id)->where('size_id',$n_size_id)->decrement('tuopan_usable_num',$n_num);
//                break;
//            case 21:
//                return  StoreFashion::where('huojia_usable_num','>=',$n_num)->where('fashion_id',$n_fashion_id)->where('size_id',$n_size_id)->decrement('huojia_usable_num',$n_num);
//                break;
            case 30:

                //如果没有数据 新增一条数据 否则直接进行加减库存操作
                if(empty($res))
                {
                    $arr_data =
                        [
                            'fashion_code'=>$s_fashion_code,
                            'fashion_size'=>$s_fashion_size,
                            'fashion_num'=>$n_num,
                            'status'=>1,
                            'operator_id'=>get_operator_id()
                        ];
                    $res = StoreFashion::create($arr_data)->toArray();
                    if(empty($res))
                    {
                        return ['state'=>1,'content'=>$s_msg.'入库失败(code:100001)'];
                    }
                    else
                    {
                        return  ['state'=>0,'content'=>'入库成功'];
                    }
                }
                else
                {
                    $res = StoreFashion::where('fashion_code',$s_fashion_code)->where('fashion_size',$s_fashion_size)->increment('fashion_num',$n_num);
                    if(!$res)
                    {
                        return ['state'=>1,'content'=>$s_msg.'入库失败(code:100002)'];
                    }
                    else
                    {
                        return  ['state'=>0,'content'=>'入库成功'];
                    }
                }

                break;
            case 31:
                if(empty($res))
                {
                    return ['state'=>1,'content'=>$s_msg.'没有库存数据(code:100003)'];
                }

                $res =  StoreFashion::where('fashion_num','>=',$n_num)->where('fashion_code',$s_fashion_code)->where('fashion_size',$s_fashion_size)->decrement('fashion_num',$n_num);
                if(!$res)
                {
                    return ['state'=>1,'content'=>$s_msg.'库存不足(code:100004)'];
                }
                else
                {
                    return  ['state'=>0,'content'=>'出库成功'];
                }
                break;
            case 32:
                if(empty($res))
                {
                    return ['state'=>1,'content'=>$s_msg.'没有库存数据(code:100005)'];
                }

                $res =  StoreFashion::where('fashion_num','>=',$n_num)->where('fashion_code',$s_fashion_code)->where('fashion_size',$s_fashion_size)->decrement('fashion_num',$n_num);
                if(!$res)
                {
                    return ['state'=>1,'content'=>$s_msg.'库存不足(code:100006)'];
                }
                else
                {
                    return  ['state'=>0,'content'=>'出库成功'];
                }
                break;
            default:
                return ['state'=>1,'content'=>'出入库类型错误(code:100007)'];


        }

    }



}