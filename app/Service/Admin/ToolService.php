<?php

namespace App\Service\Admin;
use App\Admin\Models\StoreFashionModel;
use Illuminate\Support\Facades\Facade;
class ToolService extends Facade
{
    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return ToolService::class;
    }

    protected function dealData($data){
        foreach ($data as $key=> &$v){
            if($key==1){
                continue;
            }

            $v=array_combine($data[1],$v);
            $v=$this->fv($v);
            $v=$this->help1($v);
        }
        unset($v);
        unset($data[1]);



        $ku_zi=[];
        $qun_zi=[];
        $qi_ta=[];

        foreach ($data as $v){
            if(strpos($v['fashion_name'],'裤')!=false){
                $ku_zi[]=$v;
            }else if(strpos($v['fashion_name'],'裙')!=false){
                $qun_zi[]=$v;
            }else{
                $qi_ta[]=$v;
            }
        }


        $new_ku_zi=[];
        foreach ($ku_zi as $v){
            if($v['fashion_code']!=''){
                $new_ku_zi[$v['fashion_code']][$v['fashion_size']][]=$v;
            }

        }

        $new_qi_ta=[];
        foreach ($qi_ta as $v){
            if($v['fashion_code']!=''){
                $new_qi_ta[$v['fashion_code']][$v['fashion_size']][]=$v;
            }

        }

        $new_qun_zi=[];
        foreach ($qun_zi as $v){
            if($v['fashion_code']!=''){
                $new_qun_zi[$v['fashion_code']][$v['fashion_size']][]=$v;
            }

        }

//    $all=[];
//    foreach ($data as $v){
//        if($v['fashion_code']!=''){
//            $all[$v['fashion_code']][$v['fashion_size']][]=$v;
//        }
//
//    }

///////合并数据的
        $new_ku_zi=$this->help2($new_ku_zi);
        $new_qi_ta=$this->help2($new_qi_ta);
        $new_qun_zi=$this->help2($new_qun_zi);
        // $all=$this->help2($all);///所有的
///////合并数据的
//////加上库存

        // $all=$this->help3($all);
//    $new_ku_zi=$this->help3($new_qi_ta,'biao');
//    $new_new_qi_ta=$this->help3($new_qi_ta,'biao');
//    $new_new_qi_ta=$this->help3($new_qi_ta,'te');
        $a=[];
        $a[]=$new_ku_zi;
        $a[]=$new_qun_zi;
        $a[]=$new_qi_ta;///其他的数据进行处理

return $a;
    }

    /**
     * 处理导入的数据
     * @param 
     * @return mixed
     */
    protected function dealDataNew($data){

        foreach ($data as $key=> &$v){
            if($key==1){
                continue;
            }

            $v=array_combine($data[1],$v);
            $v=$this->fv($v);
            $v=$this->help4($v);
        }
        unset($v);
        unset($data[1]);

        $data=$this->findKuWei($data);

        /////去判断库存
        $data = $this->deK($data);

//$a = array_slice($data,0,30);

        $ming_xi=$this->help9($data);

        $index_array=[];
        foreach ($ming_xi as $key=> $v){
            $index_array[]=$v['fuojia'][1];
            unset($ming_xi[$key]['fuojia']);
        }

//sort($index_array);

        array_multisort($index_array,SORT_ASC,$ming_xi);//SORT_DESC为降序，SORT_ASC为升序
        $array=[];
        $array[0]=$ming_xi;
        return $array;

    }
    public function fv($data){
        $a = explode('_',$data['订单号']);
        $data['订单号']=$a[0];
        return $data;
    }

    public function help1($data){

        $array=[];
        if($data['产品规格']){
            $array['name']=$data['姓名'];
            $array['fashion_name']=$data['产品名称'];
            if($data['数量单位']!=''){
                $array['fashion_code']=$data['产品规格'].'，'.$data['数量单位'];
            }else{
                $array['fashion_code']=$data['产品规格'];
            }
            $array['fashion_size']=$data['尺码'];
            $array['fashion_num']=(int)$data['产品数量'];

        }

        return $array;
    }

    public function help2($new_ku_zi){

        $function=function ($data){
            $num=0;
            foreach ($data as $v){
                $num=$num+$v['fashion_num'];
            }
            $data[0]['fashion_num']=$num;
            return $data[0];
        };


        $array=[];
        foreach ($new_ku_zi as $v){
            foreach ($v as $vv){
                $array[]=$function($vv);
            }
        }


        return $array;
    }

    public function help3($data){
        $fuo_jia = HuoJiaModel::all()->toArray();
        foreach ($data as &$v){
            $v['fuo_jia']=$this->getHuoJia($v,$fuo_jia);
        }
        unset($v);
        return $data;
    }

    public function findKuWei($data){
        ///  $fashion_code='T1607030A';
        //strpos($v['huo_jia_name'],$fashion_code) !== false
        $fuo_jia=$this->fuoJiaNum();


        foreach ($data as $key=>$v){
            if($v){
                $data[$key]['fuojia'] = $this->getKuWei($v,$fuo_jia);
            }else{
                unset($data[$key]);
            }
        }



//$index_array=[];
//   foreach ($data as $v){
//       $index_array[]=$v['fuojia'][1];
//   }
//
////sort($index_array);
//
//    array_multisort($index_array,SORT_ASC,$data);//SORT_DESC为降序，SORT_ASC为升序
//
//
        foreach ($data as &$v){
            $v['sort']=$v['fuojia'][0];
        }
        unset($v);


        return $data;


    }


///返回一个货架的东西
    public function getKuWei($data,$fuo_jia){
        $a = strrpos($data['fashion_name'],'{');
        if($a){
            $fuo_jia_v = substr($data['fashion_name'],$a+1);
        }else{
            $fuo_jia_v='未知货架';
        }

        foreach ($fuo_jia as $vv){
            if($fuo_jia_v==$vv[0]){
                return $vv;
            }
        }

        $fuo_jia_v='未知货架';
        foreach ($fuo_jia as $vv){
            if($fuo_jia_v==$vv[0]){
                return $vv;
            }
        }

    }




////货架码
    public function fuoJiaNum(){
        $a =[['A1',1],['A2',2],['A3',3],['A4',4],['A5',5],['A6',6],['A7',7],['A8',8],['A9',9],['A10',10],['A11',11],['A12',12],['A13',13],['A14',14],['A15',15],['A17',17],['A18',18],['A19',19],['A20',20],['A21',21],['A22',22],['A23',23],['A24',24],['A25',25],['A26',26],['A27',27],['A28',28]
            ,['A29',29],['A30',30],['B1',31],['B2',32],['B3',33],['B4',34],['B5',35],['B6',36],['B7',37],['B8',38],['B9',39],['B10',40],['B11',41],['B12',42],['B13',43],['B14',44],['B15',45],['B16',46],['B17',47],['B18',48],['B19',49],['B20',50],['B21',51],['B22',52],['B23',53],['B24',54],['B26',56],
            ['B27',57],['B28',58],['B29',59],['B30',30],['C1',61],['C2',62],['C3',63],['C4',64],['C5',65],['C6',66],['C8',68],['C9',69],['C10',70],['C11',71],['C12',72],['C13',73],['C14',74],['C15',75],['C16',76],['未知货架','78']];
        return $a;
    }
    public function getFashionCode($data){

        $fashion=explode('，',$data['fashion_code']);

        foreach ($fashion as $v){
            $a[]=$v;
            $a[]=$data['size'];

        }
    }

    public function help4($data){
        $array=[];
        if($data['产品规格']){
            $array['name']=$data['姓名'];
            $array['fashion_name']=$data['产品名称'];

            $array['fashion_code']=$data['产品规格'];
            $array['fashion_code_s']=$data['数量单位'];

            $array['fashion_size']=(int)$data['尺码'];
            $array['fashion_num']=(int)$data['产品数量'];

        }

        return $array;
    }

    public function deK($data){

        $all_fashion=[];//所有的商品
        foreach ($data as $v){
            $all_fashion[]= explode('，',$v['fashion_code_s']);
        }

        $all_fashions=[];
        foreach ($all_fashion as $v){
            foreach ($v as $vv){
                if($vv!=''){
                    $all_fashions[]=trim($vv);
                }

            }
        }
/////所有的符合的库存数据
        $all_stock = StoreFashionModel::whereIn('fashion_code',$all_fashions)->get()->toArray();

        foreach ($data as &$v){
            $v['need'] = $this->help7($v,$all_stock);///根据这个编码区寻找库存
        }
        unset($v);

        return $data;


    }

    public function help6($vv,$size,$all_stock){
        foreach ($all_stock as $v){
            if($v['fashion_code'].$v['fashion_size']==$vv.$size){
                return $v['fashion_num'];
            }
        }
        return 0;
    }

    public function help7($data,$all_stock){
        //$fashion=[];
        $need_num=$data['fashion_num'];
        $need_size=$data['fashion_size'];

        if($data['fashion_code_s']!=''){
            $fashion=explode('，',$data['fashion_code_s']);
        }else{
            ////为空 说明直接是标准品
            return [$data['fashion_code'].$need_size.'_'.'标'=>$need_num];
        }

        //$fashion[]=$data['fashion_code'];

        $array=[];////需要的特殊品的数量
        foreach ($fashion as $v){
            $a=$this->help8($v,$need_num,$need_size,$all_stock);
            if($a['num']){
                $array[$v.$need_size]=$a['num'];
            }

            if($a['status']=='不够'){
                //继续
                continue;
            }else{
                ///不要在找了
                break;
            }

        }

        $num=0;///标准品数量
        foreach ($array as $v){
            $num=$num+$v;
        }

        if($num<$data['fashion_num']){
            ////如果数量还小于需要的 拿标准品来对比
            $biao_num=$data['fashion_num']-$num;
            return [$data['fashion_code'].$need_size.'_'.'标'=>$biao_num];
        }


        return $array;
    }

    public function help8($fashion_code,&$need_num,$need_size,$all_stock){
        $num = $this->help6($fashion_code,$need_size,$all_stock);

        //$num=1;
        if($need_num>=$num){
            $need_num=$need_num-$num;
            $status='不够';
            $array=[];
            $array['num']=$num;
            $array['status']=$status;
            return $array;
        }else{
            $status='够';
            $array=[];
            $array['num']=$need_num;
            $array['status']=$status;
            return $array;
        }


    }

    public function help9($data){

        foreach ($data as  &$v){
            $v['need']=$this->help10($v['need']);
            // unset($v['fuojia']);
        }
        unset($v);
        return $data;

    }

    public function help10($data){
        $str='';
        foreach ($data as  $key=>$v){
            $str=$str.$key.'    '.'X'.$v;
        }
        return $str;
    }

    public function kuCun(){
        $data =  FashionStockWuxi::with('fashion')->get()->toArray();

        $data = $this->deal($data);

    }

    public function deal($data){

        $function = function($data){
            $array=[];
            $array['fashion_name']=$data['fashion_name'];
            $array['fashion_code']=$data['fashion_code'];
            $array['fashion_size']=$data['fashion_size'];
            $array['fashion_num']=$data['fashion_num'];
            $array['school_name']=$data['fashion']['school'];
            return $array;
        };////
        $array=[];
        foreach ($data as $v){
            $array[]=$function($v);

        }
        $new_array=[];
        $new_array[]=$array;
        $new_array[]=$array;


        $headArr=['产品名称','产品编码','产品尺寸','产品数量','学校名称'];
        $sheet=['第一个','第二个'];
        $this->exports($new_array,$headArr,$sheet);


    }







}


