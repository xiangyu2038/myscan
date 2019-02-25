<?php
namespace App\Models\Admin;

class ScanCnDetailsModel extends  BaseModel{
    protected $table='ts_scan_cn_detail';

    protected $guarded=[];
    public function student(){
        return $this->hasone('\App\Models\Admin\ScanStudentModel','student_code','code');
    }

    public function batchPrintEnd(){
        return $this->hasone('\App\Models\Admin\SellBatchPrintEndModel','one_code','code');
    }

    /**
     * 是否扫描
     * @param
     * @return mixed
     */
    public function isScan(){
        if(!$this->student){
            return false;
        }
        if($this -> student -> studentDetails->count()){
            return true;
        }
        return false;
    }

    /**
     * 这个包裹所拥有的产品
     * @param
     * @return mixed
     */
    public  function getFashion(){
        if(!$this -> student){
            return [];
        }
        return collapse($this->student->studentDetails);
    }

    /**
     * 这个包裹的学生信息
     * @param
     * @return mixed
     */
    public  function getStudentInfo($source){
if(!$this -> student){
    return [];
}
        return  $this -> student -> getStudent($source);

    }

    /**
     * 本次包裹要发货的产品
     * @param
     * @return mixed
     */
    public  function getWillFaFashion(){
        if(!$this->student){
            return [];
        }
        if( $this -> student -> batchPrintEnd){
            return $this -> student -> batchPrintEnd -> sellBatchPrintFashions;
        }
       return [];
    }

    /**
     * 获取扫描的数据
     * @param
     * @return mixed
     */
    public function getScan(){
        if(!$this->student){
            return [];
        }
        if( $this -> student -> batchPrintEnd){
            return $this -> student -> batchPrintEnd -> scanStudent;
        }
        return [];
    }

}