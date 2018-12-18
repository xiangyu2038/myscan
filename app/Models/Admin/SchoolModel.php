<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class SchoolModel extends BaseModel
{

    protected $table = 'ts_school';
    public $timestamps = true;
    protected $guarded;


    /**
     * 关联归属用户
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function belongList()
    {
        return $this->belongsTo('model\shop\Manager', 'u_id')->select('id', 'name');
    }

    /**
     * 线上支付 预售订单
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function onLineSizeOrder()
    {
        return $this->hasMany('model\shop\SizeOrder', 'school_id')->where('sstype', 1)->where('ispay', 2)->select('order_id', 'name', 'school_id');
    }

    /**
     * 关联操作用户
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return mixed
     */
    public function operatorList()
    {
        return $this->belongsTo('model\shop\Manager', 'operator_id')->select('id', 'name');
    }

    public function manager()
    {
        return $this->belongsTo('model\shop\Manager', 'operator_id')->select('id', 'name');
    }

    public function grade()
    {
        return $this->hasMany('model\shop\SchoolGradeModel', 'school')->select('id', 'name', 'school')->where('status', 1);
    }

    public function gradeClass()
    {
        return $this->hasManyThrough('model\shop\SchoolGradeClassModel', 'model\shop\SchoolGrade', 'school', 'grade');
    }

    public function gradeConfig()
    {
        return $this->hasManyThrough('model\shop\SchoolGradeConfig', 'model\shop\SchoolGrade', 'school', 'grade_id');
    }

    /**
     * 客户（学校）产品关联
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function config()
    {
        return $this->hasMany('model\shop\SchoolConfig', 'school_id');
    }

    /**
     * 客户（学校）产品单件关联
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function configFashion()
    {
        return $this->hasManyThrough('model\shop\SchoolConfigFashion', 'model\shop\SchoolConfig', 'school_id', 'school_config_id');
    }

    public function orderContract()
    {
        return $this->hasMany('model\shop\OrderContract', 'school_id')->select('school_id', 'contract_type');
    }

    public function fashionBatch()
    {
        return $this->hasMany('model\shop\Fashionbatch', 'school_id');
    }

    public function getTypeAttribute($value)
    {
        switch ($value) {
            case 1:
                return '线上客户';
                break;
            case 2:
                return '线下客户';
                break;
        }
    }

    /**
     * 关联预售信息
     * @param
     * @return mixed
     */
    public function sizeOrder()
    {
        return $this->hasMany('model\shop\SizeOrder', 'school_id');
    }

}