<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class FashionModel extends Model
{

    protected $table = 'ts_fashion';
    protected $guarded = [];

    //public $timestamps = false;

    /**
     * 关联操作员【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function operator()
    {
        return $this->belongsTo('model\shop\Manager', 'operator_id')->select('id', 'name');
    }

    public function fashion_type()
    {
        return $this->belongsTo('model\shop\Fashiontype', 'type_id');
    }

    public function fashion_detail()
    {
        return $this->hasOne('model\shop\FashionDetail', 'fashion_id', 'id');
    }
//    public function setSexAttribute($value){
//        switch ($value){
//            case 1:
//                return '男';
//                break;
//            case 2:
//                return '女';
//                break;
//            case 3:
//                return '同';
//                break;
//        }
//    }

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


    /**
     * 获取简单的产品库数组
     *
     * @author daijun <daijun_wb@huoyunren.com>
     * @return array
     */
    public function get_simple_fashion()
    {
        $arrFashionTmp = Fashion::where('status', 1)->get(['id', 'name', 'code'])->toArray();
        if (empty($arrFashionTmp))
            return ['state' => -1, '暂无数据'];
        $arrFashion = [];
        foreach ($arrFashionTmp as $key => $value) {
            $arrFashion[$value['id']]['id'] = $value['id'];
            if (empty($value['code']))
                $arrFashion[$value['id']]['name'] = $value['name'] . '';
            else
                $arrFashion[$value['id']]['name'] = $value['name'] . '-' . $value['code'];
        }
        return ['state' => 0, 'content' => $arrFashion];
    }

    /**
     * 获取产品列表【NEW】
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @param $arrSearch
     * @param $arrAdmin
     * @param $arrPage
     * @return array
     */
    public function get_data($arrSearch, $arrAdmin, $arrPage)
    {
        if ($arrAdmin['admin_group_id'] <= 0)
            return ['state' => 1, 'content' => '用户分组ID不能为空'];
        if ($arrAdmin['admin_id'] <= 0)
            return ['state' => 2, 'content' => '用户ID不能为空'];
        $data = Fashion::with('operator')
            //按搜索条件查询
            ->where(
                function ($query)
                use ($arrSearch) {
                    if (isset($arrSearch['key_word']) && $arrSearch['key_word']) {
                        $query->orWhere('code', 'like', '%' . $arrSearch['key_word'] . '%')->orWhere('old_code', 'like', '%' . $arrSearch['key_word'] . '%')->orWhere('name', 'like', '%' . $arrSearch['key_word'] . '%')->orWhere('EN_name', 'like', '%' . $arrSearch['key_word'] . '%')->orWhere('real_name', 'like', '%' . $arrSearch['key_word'] . '%');
                    }
                }
            )
            ->where('status', 1)
            ->select('id', 'name', 'code', 'old_code', 'sex', 'created_at', 'operator_id')
            ->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate($arrPage['per_page'], $arrPage['current_page'])->toArray();

        if ($data['total'] == 0)
            return ['state' => -1, 'content' => '暂无数据'];
        else
            return ['state' => 0, 'content' => $data];
    }
}