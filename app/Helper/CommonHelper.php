<?php

namespace App\Helper;



use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;

class CommonHelper extends Facade
{
    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return CommonHelper::class;
    }
    /**
     * 文件上传
     * @param $request $name 文件提交的表单字段名称
     * @return mixed 路径的绝对地址
     */
    protected function upload($request,$name)
    {
        //判断文件夹是否已存在
        if (!Storage::disk('public')->exists($folder =  '/admin/uploads/'.date('Ymd'))) {
            //   dd('上传文件夹不存在');
            Storage::makeDirectory($folder);
        }

        $file =  $request->file($name) ;

        if(!$file){
            ////不存在上传信息
            return  msg(1,'不存在上传信息');
        }
        //判断文件是否有效
        if ($request->hasFile($name) && $file->isValid()) {

            $or_file_name = $file->getClientOriginalName();///原始表格名称

            $newFileName =$or_file_name.'-'.md5(microtime()) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($folder . '/' . $newFileName, file_get_contents($file));

            $data=['url'=>config('filesystems.disks.public.root'). $folder . "/" . $newFileName,'ext'=>strtolower($file->getClientOriginalExtension()),'or_file_name'=>$or_file_name];

            return msg(0,'处理成功',$data);
        }

        return  msg(1,'上传失败');

    }

}


