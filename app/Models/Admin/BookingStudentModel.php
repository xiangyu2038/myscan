<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Comparator\Book;

class BookingStudentModel extends BaseModel
{
    protected $table = 'ts_booking_student_info';
    protected $guarded = [];
    public function addressInfo(){
        return $this->hasMany('App\Models\Admin\BookingAddressModel','student_id')->select('id','student_id','province','city','area','name','phone','detail');
    }

    public function school(){
        return $this->belongsTo('App\Models\Admin\SchoolModel','school_id')->select('id','name','EN_name');
    }

    public function grade(){
        return $this->belongsTo('App\Models\Admin\SchoolGradeModel','grade_id')->select('id','name','EN_name');
    }

    public function gradeClass()
    {
        return $this->belongsTo('App\Models\Admin\SchoolGradeClassModel', 'class_id')->select('id', 'name');
    }

}
