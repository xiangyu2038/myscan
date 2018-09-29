<?php
if (! function_exists('dds')) {
    /**
     * 调试 不会终止脚本
     *
     * @param  mixed  $args
     * @return void
     */
    function dds(...$args)
    {
        http_response_code(500);

        foreach ($args as $x) {
            (new \Illuminate\Support\Debug\Dumper)->dump($x);
        }

        //die(1);
    }
}


if (! function_exists('upload')) {
    /**
     * 文件上传
     * @param $request $name 文件提交的表单字段名称
     * @return mixed 路径的绝对地址
     */
    function upload($request,$name)
    {

        //判断文件夹是否已存在
        if (!Storage::disk('public')->exists($folder =  '/admin/uploads/'.date('Ymd'))) {
         //   dd('上传文件夹不存在');
            Storage::makeDirectory($folder);
        }

         $file =  $request->file($name) ;

        if(!$file){
            ////不存在上传信息
          return  error_msg('dw','不存在上传信息');
        }
        //判断文件是否有效
        if ($request->hasFile($name) && $file->isValid()) {

           $or_file_name = $file->getClientOriginalName();///原始表格名称

            $newFileName =$or_file_name.'-'.md5(microtime()) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($folder . '/' . $newFileName, file_get_contents($file));

            return config('filesystems.disks.public.root'). $folder . "/" . $newFileName;
        }

        dd('上传失败');

    }



}


if (! function_exists('error_msg')) {
    /**
     * 构造错误信息   系统之间报错
     * @param $code *错误码
     * @param  $msg *错误说明
     * @return array
     */
   function error_msg($code,$msg){
       return ['code'=>$code,'msg'=>$msg];
   }
}
?>