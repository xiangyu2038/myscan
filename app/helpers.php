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

if (! function_exists('api_msg')) {
    /**
     * 构造错误信息   系统之间报错
     * @param $code *错误码
     * @param  $msg *错误说明
     * @param  $data *数据接口
     * @return array
     */
   function api_msg($code,$msg,$data=null){
       return ['state'=>$code,'msg'=>$msg,'data'=>$data];
   }
}

if (! function_exists('msg')) {
    /**
     * 构造错误信息   系统之间报错
     * @param $code *错误码
     * @param  $msg *错误说明
     * @param  $data *数据接口
     * @return array
     */
    function msg($code,$msg,$data=null){
        return ['code'=>$code,'msg'=>$msg,'data'=>$data];
    }
}

function current_time(){
    return date('Y-m-d H:i:s');
}

function deal($data){
    if($data['code']){
        abort(404,$data['msg']);
    }
    return $data['data'];
}

function exception($msg,$line){
    throw new \Exception($msg,'错误码:'.$line);
}

function asssort($all_fashions){
    $array = [];

    foreach ($all_fashions as $v){
        $array[$v['fashion_code']][$v['fashion_size']][]=$v;
    }

    $one = function ($data){
        $num = 0 ;
        foreach ($data as $v){
            $num = $num + $v['fashion_num'];
        }

        $data[0]['fashion_num'] = $num;
        return $data [0];
    };
    /////计算个数
    $new_array = [];
    foreach ($array as $v){
        foreach ($v as $vv){
            $new_array[]=$one($vv);
        }
    }
    return $new_array;
}


function humpToLine($str){
    $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
        return '_'.strtolower($matches[0]);
    },$str);
    return $str;
}


 function convertUnderline($str)
{
    $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
        return strtoupper($matches[2]);
    },$str);
    return $str;
}

function side($temp,$o,$p){

     foreach ($p as $v){
         $f = 'has'.ucfirst(convertUnderline($v));
         dd($o -> $f('da'));
    }
    dd(__LINE__);
    return $temp;
}

/**
 * 为自己
 * @param 原本的
 * @param 外来的
 * @return mixed
 */
function for_this($this_model,$o=[]){
    $temp = [];
    $parameter = parse($o);

    foreach ($parameter['self'] as $v){
        $temp[$v] = $this_model->$v;
   }

    foreach ($parameter['relation'] as $key => $v){
        $relation = convertUnderline($key);
        $fouder = $this_model->fouder();///fouder
        $h = $fouder.$relation;
        if(in_array($fouder,$v)){
            array_pop($v);
            $temp=array_merge($temp,$this_model->$h($relation,$v));
        }else{
            $temp[$key] = $this_model->$h($relation,$v);
        }
    }

    return $temp;

}

/**
 * 陈翔宇
 * @param
 * @param
 * @param
 * @return mixed
 */
function this($this_model_s,$o=[]){
    if(!$this_model_s){
      /////这段代码很重要 解决方法无字段问题
        $temp = [];
        foreach ($o as $v){
            if(!is_array($v)){
              $temp[$v] = '';
          }
        }
        return $temp;
    }
    if($this_model_s instanceof \Illuminate\Database\Eloquent\Model){
        return for_this($this_model_s,$o);
  }else{
        $temp= [];
        foreach ($this_model_s as $v){
            $temp[] = for_this($v,$o);
        }
        return $temp;
    }
}


/**
 * paginate
 * @param
 * @param
 * @return mixed
 */
function paginate($this_model,$data){
    $temp = [];
    $temp['current_page'] = $this_model->currentPage();
    $temp['prev_page_url'] = $this_model->previousPageUrl();
    $temp['next_page_url'] = $this_model->nextPageUrl();
    $temp['total'] = $this_model->total();
    $temp['data'] = $data;

    return $temp;
}

/**
 * 解析参数 词法解析
 * @param 
 * @return mixed
 */
function parse($parameter){

    $self = []; ///自身
    $relation = []; ///关联
    foreach ($parameter as $key => $v){
       if(is_array($v)){
           $relation[$key] = $v;
       }else{
           $self[] = $v;
       }
   }
   return compact('relation','self');
}


/**
 * paginate_all
 * @param
 * @param
 * @return mixed
 */
function paginate_all($_model_1,$_model_2,$all){
    if($_model_1->total()>=$_model_2->total()){
        return paginate($_model_1,$all);
    }
    return paginate($_model_2,$all);
}
/**
 * pick_up
 * @param
 * @return mixed
 */
function pick_up($array,$need){
    $temp = [];
    foreach ($array as $v){
       if(isset($v[$need])){
           $temp[] = $v[$need];
       }else{
           pick_up($v,$need);
       }
    }
    return $temp;
}

/**
 * 提取元素
 * @param 
 * @return mixed
 */
function collapse($array){
    $temp = [];
    foreach ($array as $v){
        if(is_array($v)){

            $temp = array_merge($temp,collapse($v));
        }else{
            $temp[] = $v;
        }
    }
    return $temp;
}





