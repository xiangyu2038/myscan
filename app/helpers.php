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

if (! function_exists('error_msg')) {
    /**
     * 构造错误信息   系统之间报错
     * @param $code *错误码
     * @param  $msg *错误说明
     * @param  $data *数据接口
     * @return array
     */
   function error_msg($code,$msg,$data=null){
       return ['code'=>$code,'msg'=>$msg,'data'=>$data];
   }
}
?>