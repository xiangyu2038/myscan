<?php

namespace App\Service\Admin;

use App\Models\Admin\BookingAddressModel;
use App\Models\Admin\BookingOrderModel;
use App\Models\Admin\FashionModel;
use App\Models\Admin\ScanErrorModel;
use App\Models\Admin\ScanStudentDetailsModel;
use App\Models\Admin\ScanStudentModel;
use App\Models\Admin\SellBatchPrintEndModel;
use App\Models\Admin\SellStudentModel;
use App\Models\Admin\SellBatchModel;

use BarcodeBakery\Barcode\BCGcode128;
use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Common\BCGFontFile;
use Illuminate\Support\Facades\Facade;


class MyScanService extends Facade
{
    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return MyScanService::class;
    }

    protected function xiaoShouFaHuoList($search_con){
       $sell_batch = SellBatchModel::where(function ($query)use($search_con){
           if($search_con['key_word']){
               $query->where('batch_sn','like','%'.$search_con['key_word'].'%');
           }
       })->orderBy('created_at','desc')->paginate(config('app.page_size'));

        $links = $sell_batch->links('admin.links');

        $format = function ($data){

             $array = [];
             $array['batch_id'] = $data['id'];
             $array['batch_sn'] = $data['batch_sn'];
             $array['note'] = $data['note'];
             $array['out_status'] = $data['out_status'];
             $array['created_at'] = $data['created_at'];

             return $array;

       };

       $array = [];
       foreach ($sell_batch as $v){
           $array[] = $format($v);
       }

       return ['data'=>$array,'links'=>$links];
    }
    
    /**
     * 获取所有的零售要发货的数据 带分页
     * @param 
     * @return mixed
     */
    protected function getAllSellData($start,$end){
        if($start&&$end){
            $sell_data =  BookingOrderModel::where('type',2)->with('sellStudent')->get();
            $links='';
        }else{
            $sell_data =  BookingOrderModel::where('type',2)->with('sellStudent')->paginate(15);
            $links = $sell_data->links('admin.layouts.eui.links');
        }

        $sell_data =  $this->formatSellData($sell_data);

         return ['data'=>$sell_data,'links'=>$links];

    }

    /**
     * 对获取的零售订单进行格式化
     * @param
     * @return mixed
     */
     protected function formatSellData($sell_data){

         $one = function ($one_sell_data){
             $array=[];
             $array['sell_id']=$one_sell_data['id'];
             $array['order_sn']=$one_sell_data['order_sn'];
             $array['price']=$one_sell_data->price;
             $array['created_at']=$one_sell_data->created_at;
             $array['student_info']=$this->studentInfo($one_sell_data->sellStudent);
             return $array;
         };///处理其中的一个

         $array=[];
         foreach ($sell_data as $v){
         $array[]=$one($v);
         }
         return $array;
     }

     /**
      * 给一批零售订单添加批次
      * @param  [$order_sn,$order_sn]
      * @return mixed
      */
protected function addSellBatch($order_sns,$fa_huo_time,$note){
    ///首先增加一个批次
    $batch_create = function ($fa_huo_time,$note){
    $array = [];
    $array['batch_sn'] = $this->getPsSn();
    $array['note'] = $note;
    $array['out_time'] = $fa_huo_time;
    return $array;
};///创建批次的保存数组
///

    $batch_create = $batch_create($fa_huo_time,$note);///创建批次的数据保存数组

    \DB::beginTransaction();
    try{
       $res = SellBatchModel::create($batch_create);
       if(!$res){
           throw new \Exception('插入批次失败');
       }
      $sell_batch_print_end = $this->sellBatchPrintEnd($res->id,$order_sns);


       $ress =SellBatchPrintEndModel::Insert($sell_batch_print_end);
        if(!$ress){
            throw new \Exception('写入批次数据失败');
        }
        \DB::commit();
   } catch (\Exception $e){
        \DB::rollBack();
        return msg(1,$e->getMessage());

   }

    return msg(0,'ok',$res->id);

}

/**
 * 生成一个批次编号 随机生成
 * @param
 * @return mixed
 */
    protected function getPsSn(){
        $order_id_main = date('Ymd') . rand(10, 99);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
//echo $order_id_main;
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

        $order_id=substr($order_id,3,9);

        return 'sell_'.$order_id;
    }

    /**
     * 获取一个零售的批次信息
     * @param $batch_id *批次的id
     * @return mixed
     */
    protected function sellBatchData($batch_id){

        $batch_data = SellBatchModel::find($batch_id);
         if(!$batch_data){
             msg(100,'不存在批次信息');
         }

        $batch_data = $this->formatBatchData($batch_data);
        return msg(0,'ok',$batch_data);
    }

    /**
     * 零售订单一个批次下的所有要发货的产品汇总 信息
     * @param $batch_id
     * @return mixed
     */

    protected function faHuoData($batch_id){
       $sell_batch_print_data =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('sellOrder.sellFashion.sellConfig.fashion')->get();

       $f_sell_batch_print_data = $this->formatFaHuoData($sell_batch_print_data);//所有的用户的发货的数据

        //////对这些产品按照编码 尺码进行统计
       $all_fa_fashions = $this->allFaFashions($f_sell_batch_print_data);///所有人的买的产品

        $fen_lei_with_size= $this->assortFashion($all_fa_fashions);////对所有的产品进行分类

        $need_pei_song_num = count($f_sell_batch_print_data);//本次要配送的包裹数量

        $all_fashion_num = $this->allFashionNum($all_fa_fashions);///获取所有商品的数量


        $res = ['need_pei_song_num'=>$need_pei_song_num,'all_fashion_num'=>$all_fashion_num,'fen_lei_with_size'=>$fen_lei_with_size];

        return msg(0,'ok',$res);

    }

    /**
     * 针对查询出的数据进行格式化为想要的数据
     * @param
     * @return mixed
     */

    public function formatFaHuoData($sell_batch_print_data){
        $one = function ($data){
         $array=[];
         $array['sell_id'] = $data['id'];
         $array['sell_order_sn'] = $data['sellOrder']['order_sn'];
         $array['sell_fashions'] = $this->formatSellFashion($data['sellOrder']['sellFashion']);

         return $array;
        };

        $array = [];
        foreach ($sell_batch_print_data as $v){
            $array[] = $one($v);
        }

return $array;

    }


    /**
     * 对零售的sell_fashion 表查出来的数据进行格式化
     * @param  $sell_orders  * 零售表查出来的数据
     * @return mixed
     */

    protected  function formatSellFashion($sell_orders){
        $one = function ($data){
          $array = [];
         $array['fashion_id'] = $data['sellConfig']['fashion']['id'];
         $array['fashion_name'] = $data['sellConfig']['name'];
         $array['fashion_en_name'] = $data['sellConfig']['en_name'];
         $array['fashion_code'] = $data['sellConfig']['fashion']['code'];
         $array['fashion_num'] = $data['num'];
         $array['fashion_size'] = $data['chi_ma'];
         $array['fashion_alias_code']  = $data['sellConfig']['fashion']['alias_code'];
         $array['fashion_price']  = $data['sellConfig']['price'];
         return $array;
        };

        $array = [];

        foreach ($sell_orders as $v){
            $array[]=$one($v);
        }
        $array = $this->assortFashion($array);
        return $array;

    }

    /**
     * 统计所有人的要发货的产品
     * @param  每个人所购买的产品
     * @return mixed
     */

    public function allFaFashions($f_sell_batch_print_data){
        $array = [];
        foreach ($f_sell_batch_print_data as $v){
            foreach ($v['sell_fashions'] as $vv){
                  $array[]=$vv;
            }
        }
       return $array;
    }

    /**
     * 对所有的产品进行分类
     * @param  $all_fashions
     * @return mixed
     */
    protected function assortFashion($all_fashions){
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

    /**
     * 对所有的产品进行分类
     * @param  $all_fashions
     * @return mixed
     */
    protected function assortFashions($all_fashions){
        $array = [];

        foreach ($all_fashions as $v){
            $array[$v['code']][$v['size']][]=$v;
        }

        $one = function ($data){
            $num = 0 ;
            foreach ($data as $v){
                $num = $num + $v['num'];
            }

            $data[0]['num'] = $num;
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

    /**
     * 获取所有商品的数量
     * @param 所有的商品 *包含fashion_num 字段
     * @return mixed
     */

    public function allFashionNum($all_fashions){
        $num = 0;
        foreach ($all_fashions as $v){
            $num = $num + $v['fashion_num'];
        }
        return $num;
    }
    /**
     * 格式化批次数据
     * @param $batch_data
     * @return mixed
     */

    public function formatBatchData($batch_data){
        $array = [];

        $array['batch_id'] = $batch_data['id'];
        $array['batch_sn'] = $batch_data['batch_sn'];
        $array['batch_note'] = $batch_data['note'];
        $array['batch_out_status'] = $batch_data['out_status'];
        $array['batch_create_at'] = $batch_data['created_at'];
        return $array;

    }

    /**
     * 获取快递的配置文件
     * @param
     * @return mixed
     */

    protected function readCache($file_name){
        $data =   file_get_contents($file_name);//读取缓存文件

        return unserialize($data);
    }

    /**
     * 写入一个配置文件
     * @param
     * @return mixed
     */
    public  function inWrite($array,$filename){
        $content=serialize($array);
        // file_put_contents('scan/test', $content);//写入缓存文件
        $data = file_put_contents($filename, $content);//写入缓存文件
    }

    /**
     * 设置一个快递的获取方式 配置
     * @param [$k_d_c]
     * @return mixed
     */

    protected function setKDCompany($data,$path){
        $content=serialize($data);
        // file_put_contents('scan/test', $content);//写入缓存文件
        $data = file_put_contents($path, $content);//写入缓存文件
        if($data){
            return msg(0,'ok');
        }
        return msg('1','false');
    }

    /**
     * 获取本批次所有人员的信息 发货信息 包括页码
     * @param $batch_id
     * @return mixed
     */

    protected function willPrintData($batch_id,$search_con=[]){
      $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where(function($query)use($search_con){
          if(isset($search_con['key_word'])) {
              $query->whereHas('sellOrder.sellStudent', function ($query)use($search_con) {
                  $query->where('name', 'like', '%' . $search_con['key_word'] . '%');
              });
          }
      })->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->with('scanError')->with('scanStudent.studentDetails')->paginate(config('app.page_size'));

        $f_sell_batch_print_data = $this->formatFaHuoDataDetails($sell_batch_print_data_o);//所有的用户的发货的数据  详细情况
        $sell_batch_print_data_o->appends(array('batch_id' => $batch_id));
        $links = $sell_batch_print_data_o->links('admin.layouts.eui.links');



        return ['data'=>$f_sell_batch_print_data,'links'=>$links];

    }

    /**
     * 所有用户的发货数据 详细情况 包括班级信息 条码 等
     * @param
     * @return mixed
     */

    protected function formatFaHuoDataDetails($sell_batch_print_data){


        $one = function ($data){
            $array=[];
            $array['sell_id'] = $data['id'];
            $array['sell_order_sn'] = $data['sellOrder']['order_sn'];
            $array['one_code'] = $data['one_code'];
            $array['is_print'] = $data['is_print'];
            $array['school'] = $data['sellOrder']['sellStudent']['school']['name'];
            $array['name'] = $data['sellOrder']['sellStudent']['name'];
            $array['sex'] = $data['sellOrder']['sellStudent']['sex'];
            $array['grade'] = $data['sellOrder']['sellStudent']['grade']['name'];
            $array['grade_class'] = $data['sellOrder']['sellStudent']['gradeClass']['name'];
            $array['kuaidi'] = $data['kuaidi'];
            $array['kuaidi_company'] = $data['kuaidi_company'];
            $array['sell_fashions'] = $this->formatSellFashion($data['sellOrder']['sellFashion']);
            $array['scan_student'] = $this->ScanStudent($data['scanStudent'],$array['sell_fashions']);

            $array['scan_que'] = $this->ScanQue($array['scan_student'],$array['sell_fashions']);////缺货的数据
            $array['scan_huan'] = $this->scanHuan($data['scan_error']);////换货的数据

            return $array;
        };

        $array = [];
        foreach ($sell_batch_print_data as $v){
            $array[] = $one($v);
        }

        return $array;
    }
    
    /**
     * 扫描情况 数据 整合
     * @param 
     * @param
     * @return mixed
     */

    protected function scanStudent($scan_student,$sell_fashions){

        if(!count($scan_student)){
            return [];
        }
        $fashion=[];
        foreach ($scan_student['studentDetails'] as $v){
            $fashion[]=$this->fashionCode($v['fashion_code']);
        }

////合并一下 这个产品
        $fashion=$this->assortFashion($fashion);


        $detail_fashion=$this->assortFashionWithName($fashion,$sell_fashions);////详细产品信息 带产品名称的


        //$fashion_code=$this->getIsScanFashion();

        return $detail_fashion;
    }

    /**
     *把扫描的产品分离出尺码 数量
     * @param
     * @return mixed
     */
    protected function fashionCode($scan_fashion){
        $scan_fashion=trim($scan_fashion);
        $a = strripos($scan_fashion,'A');
        $array=[];
        $array['fashion_code']=substr($scan_fashion,0,$a+1);
        $array['fashion_size']=substr($scan_fashion,$a+1,3);
        $array['fashion_num']=1;

        return $array;
    }
    
    /**
     * 给一批扫描出来的产品数据 [
     "fashion_code" => "T1801012A"
    "size" => "180"
    "num" => 1
    ]  加上产品名称
     * @param 
     * @return mixed
     */

    public function assortFashionWithName($fashions,$sell_fashions){
        foreach ($fashions as &$v){
            $v['fashion_name'] = $this->getFashionName($v['fashion_code'],$sell_fashions);
       }
       unset($v);
      return $fashions;

    }

    /**
     * 根据一个产品编码 和产品的模型集合 获取产品的名称
     * @param $fashion_code
     * @param $all_fashion
     * @return mixed
     */
    protected function getFashionName($fashion_code,$all_fashion){

        foreach ($all_fashion as $v){
            if($v['fashion_code']==$fashion_code){return $v['fashion_name'];}

        }
        return '';
    }

    /**
     * 一个人的缺货数据
     * @param $scan_student   *扫描数据
     * @param $sell_fashion   *购买的全部数据
     * @return mixed
     */

    protected function scanQue($scan_student,$sell_fashion){

        $array=[];
        foreach ($sell_fashion as $v){
            ////看看这个产品是否在扫描的里面
            $num=$this->isInScan($v['fashion_code'].$v['fashion_size'],$v['fashion_num'],$scan_student);

            if($num==='no_scan'){
                $array[]=$v;
            }elseif($num!=0){
                $v['num']=$num;
                $array[]=$v;
            }

        }


        return $array;
    }


    /**
     * 一个人的换货数据
     * @param
     * @return mixed
     */

    protected function scanHuan($scan_error){
         if($scan_error){
             return $scan_error->toArray();
         }
         return [];

    }
    
    
    /**
     * 对比扫描的数量和未扫描的数量 差值
     * @param 
     * @return mixed
     */
    public function isInScan($fashion_code,$num,$scan_student){

        foreach ($scan_student as $v){
            if(trim($v['fashion_code'].$v['fashion_size'])==trim($fashion_code)){
                ////如果扫描了 判断数量
                return $num-$v['fashion_num'];
            }
        }
        return 'no_scan';

    }
    /**
     * 获取所有需要打印的信息
     * @param 批次信息
     * @return mixed
     */

    protected function printListVip($batch_id,$search_con,$one_code=null){

        $sell_batch_print_data =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where(function ($query)use($one_code){
          if($one_code){
              $query->whereIn('one_code',$one_code);
          }
        })->whereHas('sellOrder.sellStudent',function ($query)use($search_con){
            if(isset($search_con['key_word'])){
                $query->where('name','like','%'.$search_con['key_word'].'%');
            }
        })->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->get();

        $f_sell_batch_print_data = $this->formatFaHuoDataDetailsWithVip($sell_batch_print_data);//对数据进行格式化

       return $f_sell_batch_print_data;
    }


    /**
     * 获取所有需要打印的信息 A4纸的模板
     * @param 批次信息
     * @return mixed
     */

    protected function printListVipWithA4($batch_id,$search_con,$one_code=null){

        $sell_batch_print_data =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where(function ($query)use($one_code){
            if($one_code){
                $query->whereIn('one_code',$one_code);
            }
        })->whereHas('sellOrder.sellStudent',function ($query)use($search_con){
            if(isset($search_con['key_word'])){
                $query->where('name','like','%'.$search_con['key_word'].'%');
            }
        })->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->get();

        $f_sell_batch_print_data = $this->formatFaHuoDataDetailsWithA4Vip($sell_batch_print_data);//对数据进行格式化

        $array = [];
        foreach ($f_sell_batch_print_data as  $key=>$v){
            foreach ($v as $vkey=>$vv){
                $array[$key][$vkey] = view('admin.myscan.A4_vip',compact('vv'))->render();;
            }
        }
return $array;

    }


    /**
     * 打印vip的数据进行格式化统计 vip 条码的格式化数据
     * @param
     * @return mixed
     */

    protected function formatFaHuoDataDetailsWithVip($sell_batch_print_data){
        $one = function ($data){
            $array=[];
            $array['sell_id'] = $data['id'];
            $array['sell_order_sn'] = $data['sellOrder']['order_sn'];
            $array['one_code'] = $data['one_code'];
            $array['school_name'] = $data['sellOrder']['sellStudent']['school']['name'];
            $array['name'] = $data['sellOrder']['sellStudent']['name'];
            $array['sex'] = $data['sellOrder']['sellStudent']['sex'];
            $array['grade_name'] = $data['sellOrder']['sellStudent']['grade']['name'];
            $array['class_name'] = $data['sellOrder']['sellStudent']['gradeClass']['name'];

            $array['fashion_info'] = $this->formatSellFashionWithVip($this->formatSellFashion($data['sellOrder']['sellFashion']));


            return $array;
        };

        $array = [];
        $new_array = [];
        foreach ($sell_batch_print_data as $v){
            $array[] = $one($v);
            if(count($array)>9){
                $new_array[]=$array;
                $array=[];
            }

        }
        $new_array[]=$array;
        return $new_array;
    }



    /**
     * 打印vip的数据进行格式化统计 vip 条码的格式化数据 A4纸条
     * @param
     * @return mixed
     */

    protected function formatFaHuoDataDetailsWithA4Vip($sell_batch_print_data){
        $one = function ($data){
            $array=[];
            $array['sell_id'] = $data['id'];
            $array['sell_order_sn'] = $data['sellOrder']['order_sn'];
            $array['one_code'] = $this->base64OneCode($data['one_code']);
            $array['school_name'] = $data['sellOrder']['sellStudent']['school']['name'];
            $array['name'] = $data['sellOrder']['sellStudent']['name'];
            $array['sex'] = $data['sellOrder']['sellStudent']['sex'];
            $array['grade_name'] = $data['sellOrder']['sellStudent']['grade']['name'];
            $array['class_name'] = $data['sellOrder']['sellStudent']['gradeClass']['name'];

            $array['fashion_info'] = $this->formatSellFashion($data['sellOrder']['sellFashion']);
            $array['total'] = $this->totalPrice($array['fashion_info']);


            return $array;
        };

        $array = [];
        $new_array = [];
        foreach ($sell_batch_print_data as $v){
            $array[] = $one($v);
            if(count($array)>9){
                $new_array[]=$array;
                $array=[];
            }

        }
        $new_array[]=$array;
        return $new_array;
    }
    
    /**
     * 把产品格式化为打印vip的那种格式 : 160/1{YDS05A}  160/2{YDK}
     * @param 
     * @return mixed
     */
    protected function formatSellFashionWithVip($sell_fashion){


        $array=[];
        foreach ($sell_fashion as $v){
            $array[$v['fashion_size'].'/'.$v['fashion_num']][]=$v;
        }

        asort($array);


        $str_function=function ($data){
            $str='';
            foreach ($data as $key=> $v){
                if($key==0){
                    $str=$v['fashion_alias_code'];
                }else{
                    $str=$str.','.$v['fashion_alias_code'];
                }
            }
            return $str;
        };


        $str='';
        $a=0;
        foreach ($array as  $key=>$vv){
            if($a==0){
                $str=$key.'{'.$str_function($vv).'}';
            }else{
                $str=$str.'  '.$key.'{'.$str_function($vv).'}';
            }

            $a++;
        }

        return $str;
    }

    /**
     * 获取需要打印的产品清单信息
     * @param
     * @return mixed
     */
    protected function printListFashionList($batch_id,$one_code=null){
        $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where(function ($query)use($one_code){
            if($one_code){
                $query->whereIn('one_code',$one_code);
            }
        })->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->with('scanError')->with('scanStudent.studentDetails')->get();
       $batch_data = SellBatchModel::find($batch_id);

        $f_sell_batch_print_data = $this->printFashionList($sell_batch_print_data_o,$batch_data);//所有的用户的发货的数据  详细情况
       return $f_sell_batch_print_data;

    }

    /**
     * 格式化打印出来的清单的数据
     * @param
     * @return mixed
     */
    protected function printFashionList($sell_batch_print_data_o,$batch_data){
        $one = function ($data)use($batch_data){
            $array=[];
            $array['sell_id'] = $data['id'];
            $array['sell_order_sn'] = $data['sellOrder']['order_sn'];
            $array['batch_note'] = $batch_data->note;
            $array['bei_zhu'] = '';
            $array['one_code'] = $data['one_code'];
            $array['school_name'] = $data['sellOrder']['sellStudent']['school']['name'];
            $array['name'] = $data['sellOrder']['sellStudent']['name'];
            $array['sex'] = $data['sellOrder']['sellStudent']['sex'];
            $array['grade_name'] = $data['sellOrder']['sellStudent']['grade']['name'];
            $array['class_name'] = $data['sellOrder']['sellStudent']['gradeClass']['name'];
            $array['sell_fashions'] = $this->formatSellFashion($data['sellOrder']['sellFashion']);

            $array['scan_student'] = $this->ScanStudent($data['scanStudent'],$array['sell_fashions']);
            $array['scan_que'] = $this->ScanQue($array['scan_student'],$array['sell_fashions']);////缺货的数据
            $array['scan_huan'] = $this->scanHuan($data['scan_error']);////换货的数据
            $array['total'] = $this->allFashionNum($array['sell_fashions']);////换货的数据
            $array['total'] = $this->allFashionNum($array['sell_fashions']);////换货的数据
            $array['print_lengh']=$this->getPrintLength( $array['sell_fashions'], $array['scan_huan'], $array['scan_que']);
            return $array;
        };

        $array = [];
        $new_array=[];////10个为一组进行分类
        foreach ($sell_batch_print_data_o as $v){
            $array[] = $one($v);
            if(count($array)>9){
                $new_array[]=$array;
                $array=[];
            }
        }
        $new_array[]=$array;
        return $new_array;
    }

    /**
     * 计算打印长度
     * @param
     * @return mixed
     */
    public function getPrintLength($fashion_info,$huan,$que){
        $lenght=181;
        if($fashion=count($fashion_info)){
            $lenght=$lenght+10+$fashion*13;
        }else{
            $lenght=200;

        }
        if($fashion=count($huan)){
            $lenght=$lenght+12+$fashion*13+1.5;
        }
        if($fashion=count($que)){
            $lenght=$lenght+12+$fashion*13+1.5;
        }
        return $lenght*10;

    }


    /**
     * 获取快递的打印信息
     * @param
     * @return mixed
     */
    protected function printListKD($batch_id,$one_code=null){
        $this->batch_id = $batch_id;
        $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where(function ($query)use($one_code){
            if($one_code){
                $query->whereIn('one_code',$one_code);
            }
        })->with('sellOrder.bookingAddress')->get();

        $array = [];
        foreach ($sell_batch_print_data_o as $v){
            $array[] = $this->getPrintListKD($v);///去取快递模板
            if(count($array)>9){
                $new_array[]=$array;
                $array=[];
            }
        }
        $new_array[]=$array;
        return $new_array;

    }

    /**
     * 去取快递模板  需要订单编号 地址收件人信息 等参数
     * @param
     * @return mixed
     */

    protected function getPrintListKD($data){
        set_time_limit(5);
       // $send_model=$this->readCache(config('app.user_config').'send_model');
        $k_d_res =  $this->getKDRes($data);////取快递返回结果

        $this->saveKDInfoWithBatch($data['one_code'],$k_d_res);///按照批次保存快递信息

        if($k_d_res->Success){
            return $k_d_res->PrintTemplate;
        }else{
            return  '姓名'.$data['sellOrder']['bookingAddress']['name'].' 订单编号:'.$data['order_sn'].'  无法获取快递单'.PHP_EOL.'原因:'.$k_d_res->Reason;

        }

    }

    /**
     * 获取快递返回的模板
     * @param 需要订单编号 *地址收件人信息 等参数
     * @return mixed
     */

    protected function getKDRes($data){
        $parameter = $this->getKDInfo($data);///快递所需的参数

        $res= $this->submitEOrder($parameter);

        $res=json_decode($res);


        return $res;
    }


    /**
     * 构造获取快递所需的参数 必须包含快递编号 收货人地址信息
     * @param
     * @return mixed
     */
    protected function getKDInfo($data){

        $k_d_c=$this->readCache(config('app.user_config').'k_d_c');
        // $k_d_c[0]='ZTO';
        if($k_d_c[0]=='YTO'){
            $eorder["ShipperCode"] = "YTO";
            $eorder["CustomerName"] = "K51016103";///ddd
            $eorder["CustomerPwd"] = "16u0eUP4";
            $eorder["MonthCode"] = "Yrs0y8FE";///dddd

            $eorder["PayType"] = 3;
            $eorder["ExpType"] = 1;

        }elseif($k_d_c[0]=='ZTO'){
            $eorder = [];
            $eorder["ShipperCode"] = "ZTO";
            $eorder["CustomerName"] = "ZTO531533188313868";///ddd
            $eorder["CustomerPwd"] = "F2MIV03Z";
            //$eorder["MonthCode"] = "Yrs0y8FE";///dddd

            $eorder["PayType"] = 3;
            $eorder["ExpType"] = 1;
        }
        $send_model=$this->readCache(config('app.user_config').'send_model');

        // $send_model[0]='old';
        if($send_model[0]=='old'){

            $eorder["OrderCode"] =substr( $data['order_sn'],4,20).'---'.$this->batch_id.'2';

        }else{
            $eorder["OrderCode"] = $data['order_sn'].'---'.'new'.round(0,100);
        }
        //  $eorder["OrderCode"] = $k_d_one['order_sn'].'---'.'new'.round(0,100);

        $sender = [];
        $sender["Name"] = "哈芙琳";
        $sender["Mobile"] = "400-880-1949";////特殊模式 注释掉
        $sender["ProvinceName"] = "江苏省";
        $sender["CityName"] = "无锡市";
        $sender["ExpAreaName"] = "新吴区";
        $sender["Address"] = "梅村街道群兴路28号3栋4楼";


        $receiver = [];
        $receiver["Name"] =$data['sellOrder']['bookingAddress']['name'];
        //$receiver["Name"] =$k_d_one['re_name'];
        $receiver["Mobile"] = $data['sellOrder']['bookingAddress']['phone'];
        $receiver["ProvinceName"] = $data['sellOrder']['bookingAddress']['province'];
        $receiver["CityName"] = $data['sellOrder']['bookingAddress']['city'];
        $receiver["ExpAreaName"] =$data['sellOrder']['bookingAddress']['area'];
        $receiver["Address"] = $data['sellOrder']['bookingAddress']['detail'];

        $commodityOne = [];
        $commodityOne["GoodsName"] = '哈芙琳服装发货';////特殊模式 注释掉
        $commodity = [];
        $commodity[] = $commodityOne;

        $eorder["Sender"] = $sender;
        $eorder["Receiver"] = $receiver;
        $eorder["Commodity"] = $commodity;
        //$eorder["CallBack"] = 'dada';
        $eorder["IsReturnPrintTemplate"] = 1;
        $jsonParam = json_encode($eorder, JSON_UNESCAPED_UNICODE);

        return $jsonParam;
    }


    /**
     * 提交数据
     * @param
     * @return mixed
     */
    public function submitEOrder($requestData){

        //电商ID
        defined('EBusinessID') or define('EBusinessID', '1308162');
//电商加密私钥，快递鸟提供，注意保管，不要泄漏
        defined('AppKey') or define('AppKey', '4edad7f3-ebe0-4ecb-af99-f9e3f4bb98ea');
//请求url，正式环境地址：http://api.kdniao.cc/api/Eorderservice    测试环境地址：http://testapi.kdniao.cc:8081/api/EOrderService
        defined('ReqURL') or define('ReqURL', 'http://api.kdniao.cc/api/EOrderService');

        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $appkey=AppKey;
        $datas['DataSign'] = $this->encrypt($requestData, $appkey);

        //  $result=   $this->https_request(ReqURL,$datas);
        //  dd($result);
        $result=$this->sendPost(ReqURL, $datas);
//dd($result);
        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey) {

        return urlencode(base64_encode(md5($data.$appkey)));
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }



    /**
     * 按照批次保存快递信息
     * @param 条码 * 快递返回的结果
     * @return mixed
     */

    public function saveKDInfoWithBatch($one_code,$k_d_res){
        $kuai_di=$k_d_res->Order->LogisticCode;
        $k_d_c=$this->readCache(config('app.user_config').'k_d_c');
        $kuai_di_company=$k_d_c[0];

        SellBatchPrintEndModel::where('one_code',$one_code)->update(['kuaidi_is_print'=>'1','kuaidi'=>$kuai_di,'kuaidi_company'=>$kuai_di_company,'kuaidi_time'=>date('Y-m-d',time())]);

    }


    /**
     * 扫描数据统计
     * @param
     * @return mixed
     */
protected function scaningCollection($batch_id){
    $sell_batch_print_data =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('sellOrder.sellFashion.sellConfig.fashion')->with('scanStudent.studentDetails')->get();

    $package_num = count($sell_batch_print_data);  ///包裹数量
    $fashion_num = $this->fashionNum($sell_batch_print_data);

    $has_scan_package = $this -> hasScanPackAge($sell_batch_print_data);
    $has_scan_fashion = $this -> hasScanfashion($sell_batch_print_data);

    ///获取需要扫描的产品条码 以及条码下产品
    $will_print_package = json_encode($this -> willPrintPackage($sell_batch_print_data));
return compact('package_num','fashion_num','has_scan_fashion','has_scan_package','will_print_package');

}

/**
 *原始查出来的数据计算总共的产品数量
 * @param
 * @return mixed
 */

protected function fashionNum($sell_batch_print_data){
   $one_num = function ($data){

       $num = 0 ;
       foreach ($data['sellOrder']['sellFashion'] as $v){
                   $num = $num + $v['num'];
       }
      return $num ;

   };

   $num = 0;
    foreach ($sell_batch_print_data as $v){
        $num = $num+$one_num($v);
    }
    return $num ;
}

/**
 * 计算已经扫描的包裹数量
 * @param
 * @return mixed
 */
protected  function  hasScanPackAge($sell_batch_print_data){
    ////获取已经扫描的包裹
    $array=[];

    foreach ($sell_batch_print_data as  $v){
        if($v['scan_student']){
            $array[]=$v;
        }
    }

    return count($array);
}

/**
 * 获取已经扫描的产品数量
 * @param
 * @return mixed
 */

protected function hasScanFashion($sell_batch_print_data){
$one =function($data){
    if($data->scanStudent){
      return count($data->scanStudent->studentDetails);
  }

};
$num = 0;
foreach ($sell_batch_print_data as $v){
    $num = $num+$one($v);
}
return $num;

}


/**
 * AJAX  扫描页面扫描包裹列表
 * @param
 * @return mixed
 */
    protected function scanList($batch_id){

        $sell_batch_print_data =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent')->with('scanStudent.studentDetails')->get();
        $all_list = $this->allList($sell_batch_print_data);////所有的列表

        $is_scan_list = $this->isScanList($sell_batch_print_data);////全部扫描列表

        $no_all_scan_list = $this->noAllScanList($sell_batch_print_data);////没有全部扫描

        $no_scan = $this->noScan($sell_batch_print_data);



        return ['no_scan'=>$no_scan,'scan'=>$is_scan_list,'que_scan'=>$no_all_scan_list,'all'=>$all_list];


    }


    /**
     * 所有的扫描列表
     * @param
     * @return mixed
     */

    protected function allList($sell_batch_print_data){

        $one  = function ($data){
            $array = [];
            $array['name'] = $data['sellOrder']['sellStudent']['name'];
            $array['one_code'] = $data['one_code'];
            $array['num'] = $this->oneNUm($data['sellOrder']['sellFashion']);
            $array['print_pro'] = $this->printPro($data['sellOrder']['sellFashion']);///要扫描的产品
            ///
            return $array;

        };

        $array = [];

        foreach ($sell_batch_print_data as $v){
                 $array[] = $one($v);
        }

return $array;

    }

    /**
     * 一个人的发货数量
     * @param
     * @return mixed
     */

    protected function oneNum($data){
        $num = 0;
        foreach ($data as $v){
            $num = $num +$v['num'];
        }
        return $num ;
    }


    /**
     * 一个人购买的产品
     * @param
     * @return mixed
     */
protected function printPro($data){


    $one = function ($data){
        $array = [];
        $array ['name'] = $data['sellConfig']['fashion']['name'];
        $array ['code'] = $data['sellConfig']['fashion']['code'];
        $array ['alias_code'] = $data['sellConfig']['fashion']['alias_code'];
        $array ['size'] = $data['chi_ma'];
        $array ['num'] = $data['num'];
        return $array;
    };

    $array = [];
      foreach ($data as $v){
            $array[] = $one($v);
      }

     $array = $this->assortFashions($array);
    return $array;


}

/**
 * 已扫描的列表
 * @param
 * @return mixed
 */

public function isScanList($sell_batch_print_data){
    $one  = function ($data){
        $array = [];
        $array['name'] = $data['sellOrder']['sellStudent']['name'];
        $array['one_code'] = $data['one_code'];
        $array['num'] = $this->oneNUm($data['sellOrder']['sellFashion']);
        $array['print_pro'] = $this->printPro($data['sellOrder']['sellFashion']);///要扫描的产品
        ///
        return $array;

    };

    $array = [];

    foreach ($sell_batch_print_data as $v){
       $num = $this->oneNum($v['sellOrder']['sellFashion']);
          if(!count($v['scanStudent'])){
              continue;
          }

         ///包裹没扫描
         if($num == count($v['scanStudent']['studentDetails'])){
             ///如果购买的产品和扫描的产品数量一致 那么就算是已扫描
             ///
             $array[] = $one($v);
         }


    }

    return $array;

}

/**
 * 没有全部扫描
 * @param
 * @return mixed
 */

protected function noAllScanList($sell_batch_print_data){
    $one  = function ($data){
        $array = [];
        $array['name'] = $data['sellOrder']['sellStudent']['name'];
        $array['one_code'] = $data['one_code'];
        $array['num'] = $this->oneNUm($data['sellOrder']['sellFashion']);
        $array['print_pro'] = $this->printPro($data['sellOrder']['sellFashion']);///要扫描的产品
        ///
        return $array;

    };

    $array = [];

    foreach ($sell_batch_print_data as $v){
        if(!count($v['scanStudent'])){
            continue;
        }
        if(count($v['scanStudent']['studentDetails'])){
            $num = $this->oneNum($v['sellOrder']['sellFashion']);
            if($num != count($v['scanStudent']['studentDetails'])){
                $array[] = $one($v);
            }
        }
    }

    return $array;
}

protected function noScan($sell_batch_print_data){
    $one  = function ($data){
        $array = [];
        $array['name'] = $data['sellOrder']['sellStudent']['name'];
        $array['one_code'] = $data['one_code'];
        $array['num'] = $this->oneNUm($data['sellOrder']['sellFashion']);
        $array['print_pro'] = $this->printPro($data['sellOrder']['sellFashion']);///要扫描的产品
        ///
        return $array;

    };

    $array = [];

    foreach ($sell_batch_print_data as $v){

        if(!$v['scanStudent']||!count($v['scanStudent']['studentDetails'])){
            $array[] = $one($v);
         }
    }

    return $array;
}

/**
 * 获取扫描的条码 以及条码下的东西
 * @param
 * @return mixed
 */
protected  function willPrintPackage($sell_batch_print_data){
    $one = function ($data){
        $array = [];
        $array['one_code'] = $data['one_code'];
        $array['will_scan_data'] = $this->willScanData($data);
        return $array;
    };

    $array = [];
    foreach ($sell_batch_print_data as $v){
        $array[]=$one($v);
    }

return $array;
}

protected function willScanData($data){
   $one = function ($data){
       $array = [];
       $array ['name'] = $data['sellConfig']['fashion']['name'];
       $array ['code'] = $data['sellConfig']['fashion']['code'];
       $array ['alias_code'] = $data['sellConfig']['fashion']['alias_code'];
       $array ['size'] = $data['chi_ma'];
       $array ['num'] = $data['num'];
       return $array;
   };

   $array = [];
   foreach ($data['sellOrder']['sellFashion'] as $v){
      $array[] = $one($v);
   }
   $array = $this->assortFashions($array);
   return $array;

}

/**
 * 一个包裹的详情
 * @param  批次的id  *包裹的条码
 * @return mixed
 */

protected function packageDetail($batch_id,$one_code){
    $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where('one_code',$one_code)->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->with('scanError')->with('scanStudent.studentDetails')->get();

    $f_sell_batch_print_data = $this->formatFaHuoDataDetails($sell_batch_print_data_o);//所有的用户的发货的数据  详细情况


    return $f_sell_batch_print_data;
}

/**
 * 清空一个已经扫描的包裹
 * @param  *包裹所在的批次 $batch_id   *包裹的条码$one_code
 * @return mixed
 */

protected function clearPackage($batch_id,$one_code){

    /////删除缓存
    $scan_data= $this->readCache('scanpackage/'.$batch_id);
    if(isset($scan_data[$one_code])){
        unset($scan_data[$one_code]);
        $this->inWrite($scan_data,'scanpackage/'.$batch_id);
    }
    /////删除缓存

    $scan_student = ScanStudentModel::where('student_code',$one_code)->first();
    if(!$scan_student){
        return msg(1,'还没有扫描此包裹');
    }

    $scan_student_id=$scan_student->id;
    $res = ScanStudentDetailsModel::where('scan_student_id',$scan_student_id);//删除扫描信息

    if($res){
        $res->delete();
    }


    $ress=ScanErrorModel::where('scan_sn',$one_code);//删除换货信息

    if($ress){
        $ress->delete();
    }
    return msg(0,'ok');



}

/**
 * 打印 清单 实时的 扫描数据
 * @param $batch_id
 * @param $one_code
 * @return mixed
 */

protected function printListScan($batch_id,$one_code){

    $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->whereIn('one_code',$one_code)->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->with('scanError')->with('scanStudent.studentDetails')->first();
    $ss_scan_data= $this->getScanDataSS($one_code[0],$batch_id);

    $batch_data = SellBatchModel::find($batch_id);

    

    $f_sell_batch_print_data = $this->printListScanF($sell_batch_print_data_o,$batch_data,$ss_scan_data);//所有的用户的发货的数据  详细情况

    return $f_sell_batch_print_data;
}

/**
 * 整理数据 实时扫描的数据
 * @param 
 * @return mixed
 */

protected function printListScanF($sell_batch_print_data_o,$batch_data,$ss_scan_data){
    $one = function ($data)use($batch_data,$ss_scan_data){
        $array=[];
        $array['sell_id'] = $data['id'];
        $array['sell_order_sn'] = $data['sellOrder']['order_sn'];
        $array['batch_note'] = $batch_data->note;
        $array['bei_zhu'] = '';
        $array['one_code'] = $data['one_code'];
        $array['school_name'] = $data['sellOrder']['sellStudent']['school']['name'];
        $array['name'] = $data['sellOrder']['sellStudent']['name'];
        $array['sex'] = $data['sellOrder']['sellStudent']['sex'];
        $array['grade_name'] = $data['sellOrder']['sellStudent']['grade']['name'];
        $array['class_name'] = $data['sellOrder']['sellStudent']['gradeClass']['name'];
        $array['sell_fashions'] = $this->formatSellFashion($data['sellOrder']['sellFashion']);

        $array['scan_student'] = $ss_scan_data;
        $array['scan_que'] = $this->ScanQue($array['scan_student'],$array['sell_fashions']);////缺货的数据
        $array['scan_huan'] = $this->scanHuan($data['scan_error']);////换货的数据
        $array['total'] = $this->allFashionNum($array['sell_fashions']);////换货的数据
        $array['total'] = $this->allFashionNum($array['sell_fashions']);////换货的数据
        $array['print_lengh']=$this->getPrintLength( $array['sell_fashions'], $array['scan_huan'], $array['scan_que']);
        return $array;
    };


    return  $one($sell_batch_print_data_o);

}


/**
 * 获取扫描的实时数据
 * @param
 * @param
 * @return mixed
 */
protected function getScanDataSS($vip,$batch_id){

    $scan_data= $this->readCache('scanpackage/'.$batch_id);

    // $vip='XX141AECC36A';
    if(isset($scan_data[$vip])){
        $scan_data= $scan_data[$vip];
    }else{
        $scan_data=[];
    }

    $fashion=[];
    foreach ($scan_data as $v){
        $fashion[]=$this->fashionCode($v);
    }


////合并一下 这个产品
    $fashion=$this->assortFashion($fashion);

    return $fashion;

    //$detail_fashion=$this->getDetailFashion($fashion);////详细产品信息 带产品名称的

   // return $detail_fashion;
}

/**
 * 扫描结束时的处理页面
 * @param
 * @return mixed
 */
protected function endScan($batch_id){
    $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('scanStudent.studentDetails')->with('sellOrder.sellFashion.sellConfig.fashion')->get();

    $scan_data = $this->formatChuKuData($sell_batch_print_data_o);//所有的用户的发货的数据

    $all_fashion_num = $this->countNum($scan_data['scan_fashion']);

    $all_package_num = $this->isScanPackageNum($sell_batch_print_data_o);

    ///整理为需要的数据
    $need_data = $this->needOutData($scan_data);///根据已经扫描的产品 和要发货的产品 计算出库的数量和未出库的数量做对比


    return compact('all_fashion_num','all_package_num','need_data');

}

/**
 * 出库数据汇总
 * @param
 * @return mixed
 */
protected function formatChuKuData($sell_batch_print_data){

    $one = function ($data){
         $array = [];
         $array['fashions'] = $this->formatScanFashion($data);
         return $array;
    };

    $two = function ($data){
        $array = [];
        $array['fashions'] = $this->formatNeedFashion($data);
        return $array;
    };

    $array = [];
    foreach ($sell_batch_print_data as $v){
        $array['scan_fashion'][] = $one($v['scanStudent']['studentDetails']);
        $array['need_fashion'][] = $two($v['sellOrder']['sellFashion']);
    }

    ///格式发scan fashion
    $scan_fashions =[];
    foreach ($array['scan_fashion'] as $v){

        foreach ($v['fashions'] as $vv){
            $scan_fashions[] = $this->fashionCode($vv);
        }
    }
    $array['scan_fashion'] = $this->assortFashion($scan_fashions);
    ///格式发scan fashion
    ///
    ///
    /// 格式发需要发货的数据
    $need_fashions =[];
    foreach ($array['need_fashion'] as $v){

        foreach ($v['fashions'] as $vv){
            $need_fashions[] =$vv;
        }
    }
    $array['scan_fashion'] = $this->assortFashion($scan_fashions);
    $array['need_fashion'] = $need_fashions;


    return $array;
}

/**
 * 整理下发货的数据
 * @param 
 * @return mixed
 */

public function formatScanFashion($data){
if($data){
    $array = [];
    foreach ($data as $v){
        $array[]=$v['fashion_code'];
    }

    return $array;
}
   return [];

}

/**
 * 计算
 * @param
 * @return mixed
 */
public function countNum($data){
    $num = 0;
    foreach ($data as $v){
          $num = $num + $v['fashion_num'];
    }
    return $num;
}

/**
 * 已扫描包裹
 * @param
 * @return mixed
 */
protected function isScanPackageNum($f_sell_batch_print_data){
    $array = [];
    foreach ($f_sell_batch_print_data as $v){
        if(count($v['scanStudent']['studentDetails'])){
            $array[] = $v;
        }
    }

    return count($array);
}
/**
 * 需要的发货的数据
 * @param 
 * @return mixed
 */

protected function formatNeedFashion($data){
    $one = function ($data){
           $array = [];
       $array['fashion_num'] = $data->num;
       $array['fashion_size'] = $data->chi_ma;
       $array['fashion_code'] = $data->sellConfig->fashion->code;
       $array['fashion_name'] = $data->sellConfig->fashion->real_name;
       return $array;
   };

       $array = [];
       foreach ($data as $v){
                 $array[] = $one($v);
       }

       return  $this->assortFashion($array);

}

/**
 * 对比出库的数量 和未出库的数量
 * @param 出库的产品 *所有需要发货的产品
 * @return mixed
 */
protected function needOutData($scan_data){
    $need_num = function ($fashion_code)use($scan_data){

        foreach ($scan_data['need_fashion'] as $v){

            if($fashion_code == $v['fashion_code']){
                return $v['fashion_num'];
            }
        }

        return 0;
    };


    foreach ($scan_data['scan_fashion'] as &$v){
              $v['need_num'] = $need_num($v['fashion_code']);
    }
    unset($v);

    return $scan_data['scan_fashion'];

}

/**
 * 生成学生条码
 * @param
 * @return mixed
 */

    public function getOnecode(){
        $str=    uniqid();
        $str=substr($str,3,12);
        $str='XX'.$str;
        $str= strtoupper($str);
        return $str;

    }

    /**
     * 格式发学生数据
     * @param
     * @return mixed
     */
    protected function studentInfo($data){
       if($data){
           $array = [];
           $array['name'] = $data['name'];
           $array['sex'] = $data['sex'];
           return $array;
       }else{
           $array = [];
           $array['name'] = '';
           $array['sex'] = '';
           return $array;
       }

    }
    /**
     * 导出理货单数据
     * @param 批次的id
     * @return mixed
     */
    protected function exportLiHuo($batch_id){
        $sell_batch_print_data =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('sellOrder.sellFashion.sellConfig.fashion')->get();

        $f_sell_batch_print_data = $this->formatFaHuoData($sell_batch_print_data);//所有的用户的发货的数据
        //////对这些产品按照编码 尺码进行统计
        $all_fa_fashions = $this->allFaFashions($f_sell_batch_print_data);///所有人的买的产品

        $fen_lei_with_size= $this->assortFashion($all_fa_fashions);////对所有的产品进行分类

        ///////把发货的数据转换为可以导出的格式


        $one = function ($data){
            $array = [];
            $array ['fashion_name'] = $data['fashion_name'];
            $array ['fashion_code'] = $data['fashion_code'];
            $array ['fashion_alias_code'] = $data['fashion_alias_code'];
            $array ['fashion_size'] = $data['fashion_size'];
            $array ['fashion_num'] = $data['fashion_num'];
            return $array;
        };
        $array = [];
        foreach ($fen_lei_with_size as $v){
            $array[]=$one($v);
        }

        $new_array = [];
        $new_array[]=$array;///构造导出结构

         return $new_array;

    }

    /**
     * 导出快递信息 数据: 姓名 年级 班级 性别 条形码 快递号码 快递公司
     * @param
     * @return mixed
     */
    protected function exportKuaiDiData($batch_id){
        $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->with('scanError')->with('scanStudent.studentDetails')->get();

        $f_sell_batch_print_data = $this->formatFaHuoDataDetails($sell_batch_print_data_o);//所有的用户的发货的数据  详细情况
        $export_kuqi_di = $this->getKuaiDi($f_sell_batch_print_data);///只包含有快递的信息


       $array = [];
        foreach ($export_kuqi_di as $key=> $v){
           $array[] = $this->formatExportDataWithKuaiDi($v);
        }


        return $array;
    }

    /**
     * 获取缺货信息的数据 数据:姓名 年级 班级 条形码   缺货产品
     * @param
     * @return mixed
     */

    protected function exportProInfo($batch_id){
        $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('sellOrder.sellFashion.sellConfig.fashion')->with('sellOrder.sellStudent.school')->with('sellOrder.sellStudent.grade')->with('sellOrder.sellStudent.gradeClass')->with('scanError')->with('scanStudent.studentDetails')->get();

        $f_sell_batch_print_data = $this->formatFaHuoDataDetails($sell_batch_print_data_o);//所有的用户的发货的数据  详细情况
        ////整理下数据为导出格式

        $f_sell_batch_print_data_s = $this->getExportProData($f_sell_batch_print_data);///只包含缺货信息的数据


        $array = [];
        foreach ($f_sell_batch_print_data_s as $v){
             $array []=$this->formatExportData($v);
         }


       return $array;

    }


    /**
     * 整理下数据为导出格式
     * @param
     * @return mixed
     */

    protected function formatExportData($data){
       $one = function ($data){
           $array['name']=$data['name'];
           $array['school_name']=$data['school'];
           $array['grade_name']=$data['grade'];
           $array['class_name']=$data['grade'];
           $array['sex']=$data['sex'];
           $array['one_code']=$data['one_code'];
           $array['export_fa']=$this->formatFashionWithExport($data['sell_fashions']);///本批次要发的产品
           $array['export_que']=$this->formatFashionWithExport($data['scan_que']);///本批次缺的产品
           $array['export_huan']=$this->formatFashionWithExport($data['scan_huan']);///本批次换的产品
           return $array;
       };


        $array = [];
        foreach ($data as $v){
            $array[]=$one($v);
        }

        return $array;
    }

    /**
     * 整理下数据为导出格式
     * @param
     * @return mixed
     */

    protected function formatExportDataWithKuaiDi($data){
        $one = function ($data){
            $array['name']=$data['name'];
            $array['kuaidi']=$data['kuaidi'];
            $array['kuaidi_company']=$data['kuaidi_company'];
            $array['school_name']=$data['school'];
            $array['grade_name']=$data['grade'];
            $array['class_name']=$data['grade'];
            $array['sex']=$data['sex'];
            $array['one_code']=$data['one_code'];
            $array['export_fa']=$this->formatFashionWithExport($data['sell_fashions']);///本批次要发的产品
            $array['export_que']=$this->formatFashionWithExport($data['scan_que']);///本批次缺的产品
            $array['export_huan']=$this->formatFashionWithExport($data['scan_huan']);///本批次换的产品
            return $array;
        };


        $array = [];
        foreach ($data as $v){
            $array[]=$one($v);
        }

        return $array;
    }

    /**
     *格式化产品为导出的格式
     * @param
     * @return mixed
     */
    protected function formatFashionWithExport($fashions){
        $str='';
        foreach ($fashions as  $key=>$v){
            if($key==0){
                $str=$v['fashion_name'].'   '.$v['fashion_code'].'  '.$v['fashion_size']. '  '. $v['fashion_num'];
            }else{
                $str=$str.PHP_EOL.$v['fashion_name'].'   '.$v['fashion_code'].'  '.$v['fashion_size']. '  '. $v['fashion_num'];
            }


        }

        return $str;
    }

    /**
     * 只获取缺货的信息
     * @param
     * @return mixed
     */
    public function getExportProData($data){

        $normal = [];///正常的
        $que = [];///缺的
        $huan = [];///换
        foreach ($data as $v){
            if($v['scan_que']){
                $que[]=$v;
            }else if($v['scan_que']){
                $huan[]=$v;
            }else{

            }
        }
        $all = $data;

        return ['all'=>$all,'normal'=>$normal,'que'=>$que,'huan'=>$huan];
    }
    
    /**
     * 获取只包含有快递信息的导出数据
     * @param 
     * @return mixed
     */
protected function getKuaiDi($data){
    $has = [];//有快递信息
    $no = [];//无快递信息
    foreach ($data as $v){
              if($v['kuaidi']){
                  $has[]=$v;
              }else{
                  $no[]=$v;
              }
    }
    return ['has'=>$has,'no'=>$no];
}

    /**
     * 导出出库产品清单 数据:产品名称 产品编码 尺码 数量
     * @param
     * @return mixed
     */

    protected function exportOutList($batch_id){
        $sell_batch_print_data_o =  SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->with('scanStudent.studentDetails')->with('sellOrder.sellFashion.sellConfig.fashion')->get();

        $scan_data = $this->scanData($sell_batch_print_data_o);

        return $scan_data;
    }
    
    
    /**
     * 一个批次扫描的数据
     * @param 
     * @return mixed
     */

    protected function scanData($sell_batch_print_data){
        $one = function ($data){
            $array = [];
            $array['fashions'] = $this->formatScanFashion($data);
            return $array;
        };

        $array = [];
        foreach ($sell_batch_print_data as $v){
            $array['scan_fashion'][] = $one($v['scanStudent']['studentDetails']);
        }

///格式发scan fashion
        $scan_fashions =[];
        foreach ($array['scan_fashion'] as $v){
            foreach ($v['fashions'] as $vv){
                $scan_fashions[] = $this->fashionCode($vv);
            }
        }

        $new_array[] = $this->assortFashion($scan_fashions);
        ///格式发scan fashion

        return $new_array;
    }


    /**
     *根据批次的id 和学生的条码 获取 学生的收货地址信息
     * @param $batch_id   * $one_code
     * @param $batch_id   * $one_code
     * @return mixed
     */

    protected function getAddressInfo($batch_id,$one_code){
       $order_sn  = SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->where('one_code',$one_code)->first()->sell_order_sn;///订单的编号

        $data = BookingOrderModel::where('order_sn',$order_sn)->with('bookingAddress')->first();

       return $data->bookingAddress;

    }

    /**
     * 编辑收货地址 地址的id 和要编辑的信息
     * @param
     * @return mixed
     */

    protected function editAddress($address_id,$name,$tel,$province,$city,$area,$detail){
        $address = BookingAddressModel::where('id',$address_id)->update(['name'=>$name,'phone'=>$tel,'province'=>$province,'city'=>$city,'area'=>$area,'detail'=>$detail]);
        if($address){
            return msg(0,'ok');
        }
        return msg(1,'false');
    }

    /**
     * 总价
     * @param
     * @return mixed
     */
    public function totalPrice($data){
        $price = 0;
        $num = 0;
        foreach ($data as $v){
            $price =$price + $v['fashion_num']*$v['fashion_price'];
            $num = $num + $v['fashion_num'];
        }
        return ['total_price'=>$price,'total_num'=>$num];
    }

    /**
     * 对编码进行64 编码
     * @param
     * @return mixed
     */

    protected function base64OneCode($one_code){
        include_once base_path().'/lib/barcode/example/vendor/autoload.php';
        $font = new BCGFontFile(base_path().'/lib/barcode/example' . '/font/Arial.ttf', 18);
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);

// Barcode Part
        $code = new BCGcode128();
        $code->setScale(2);
        $code->setThickness(30);
        $code->setForegroundColor($colorFront);
        $code->setBackgroundColor($colorBack);
        $code->setFont($font);
        $code->setStart(null);
        $code->setTilde(true);
        $code->parse($one_code);

// Drawing Part
        $drawing = new BCGDrawing('one_code/'.$one_code.'.png', $colorBack);
        $drawing->setBarcode($code);

        $drawing->draw();

       // header('Content-Type: image/png');

        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
      $img =$this-> base64EncodeImage('one_code/'.$one_code);
return $img;
    }

    /**
     * 图片转换为base64
     * @param
     * @return mixed
     */

    protected function base64EncodeImage ($image_file){
        $base64_image = '';
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;

    }
    /**
     *修改产品编码 为空 针对领带领结等无尺码的产品
     * @param
     * @param
     * @param
     * @return mixed
     */
    protected function editFashionSize($batch_id,$fashion_code,$fashion_size){
        ///首先查出这个批次的所有的订单编号

        $all_order_sn = SellBatchPrintEndModel::where('sell_batch_id',$batch_id)->get()->pluck('sell_order_sn');

    }

    /**
     * 构造sell_batch_print_end 保存的格式
     * @param
     * @return mixed
     */

    public function sellBatchPrintEnd($batch_id,$order_sns){
        $one =function ($order_sn)use($batch_id){

            $array = [];
            $array['sell_batch_id'] = $batch_id;
            $array['sell_order_sn'] = $order_sn;
            $array['one_code'] = $this->getOneCode();
            $array['data_type'] = '零售';
            $array['created_at'] = current_time();
            $array['updated_at'] = current_time();
            return $array;
        };
        $array = [];
        foreach ($order_sns as  $v){
            $array[] = $one($v);
        }
     ;
        return $array;
    }


    /**
     * 构造sell_batch_print_fashion 保存的格式
     * @param
     * @return mixed
     */

    public function sellBatchPrintFashion($batch_id,$order_sns){
        $one =function ($order_sn)use($batch_id){

            $array = [];
            $array['sell_batch_id'] = $batch_id;
            $array['sell_order_sn'] = $order_sn;
            $array['one_code'] = $this->getOneCode();
            $array['data_type'] = '零售';
            $array['created_at'] = current_time();
            $array['updated_at'] = current_time();
            return $array;
        };
        $array = [];
        foreach ($order_sns as  $v){
            $array[] = $one($v);
        }
        ;
        return $array;
    }

}

