<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
/**
 * 订单的学生信息表
 * @param
 * @return mixed
 */
class SellStudentModel extends BaseModel
{
    protected $table = 'ts_pc_student_info';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo('App\Models\Admin\SchoolModel', 'school_id')->select('id', 'name','EN_name');
    }

    public function grade()
    {
        return $this->belongsTo('App\Models\Admin\SchoolGradeModel', 'grade_id')->select('id', 'name','EN_name');
    }

    public function gradeClass()
    {
        return $this->belongsTo('App\Models\Admin\SchoolGradeClassModel', 'class_id')->select('id', 'name');
    }

    public function bookingOrder()
    {
        return $this->hasMany('model\shop\BookingOrderModel', 'student_info_id');
    }



    public function bookingAddress()
    {
        return $this->belongsTo('model\shop\BookingAddressModel', 'default_address_id');
    }

     public function getSexAttribute($value){
    switch ($value){
        case 1:
            return '男';
            break;
        case 2:
            return '女';
            break;
        case 3:
            return '未设置';
            break;

    }
}
    public function addressInfo()
    {
        return $this->hasMany('App\Models\Admin\BookingAddressModel', 'student_id')->select('id', 'student_id', 'province', 'city', 'area');
    }
}