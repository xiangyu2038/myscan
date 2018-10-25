<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class SchoolGradeModel extends Model
{

    protected $table = 'ts_school_grade';
    protected $guarded = [];

    public function gradeClass()
    {
        return $this->hasMany('model\shop\SchoolGradeClassModel', 'grade')->where('status', 1)->select('id', 'name', 'grade');
    }

    public function gradeConfig()
    {
        return $this->hasMany('model\shop\SchoolGradeConfig', 'grade_id');
    }

//    public function school_config(){
//        return $this->hasManyThrough('model\shop\SchoolConfig','model\shop\SchoolGradeConfig','grade_id','product_id');
//    }

    public function school()
    {
        return $this->belongsTo('model\shop\School', 'school');
    }
}