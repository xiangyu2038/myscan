<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\BookingOrderModel;
use Illuminate\Http\Request;

class SellOrderController extends Controller
{
    public function wuLIu(Request $request){
        header('Access-Control-Allow-Origin:*');
        $order_sn = $request -> get('order_sn');
        $booking_order = BookingOrderModel::where('order_sn',$order_sn)->with('bookingAddress')->first();

        try{
            $res =  BookingOrderModel::outDetail($booking_order);
            $address = BookingOrderModel::address($booking_order);
            return response()->json(['code'=>0,'msg'=>'ok','pei_info'=>$address,'fa_info'=>$res]);
        }catch (\Exception $e){
            return response()->json(['code'=>1,'msg'=>$e->getMessage()]);
        }


    }
    public function kuaiDi(Request $request){
        header('Access-Control-Allow-Origin:*');
        $ShipperCode=$request -> get('kuai_di_company');
        $LogisticCode=$request -> get('kuai_di');

        switch ($ShipperCode){
            case 'ZTO':
                $type='zhongtong';
                break;
            case 'YTO':
                $type='yuantong';
                break;
        }
        //$sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串

       // http://www.kuaidi100.com/query?type=zhongtong&postid=73108363614500&temp=0.5488788851402543&phone=
        //dd('http://www.kuaidi100.com/query?type='.$type.'&postid='.$LogisticCode);
       $data = $this-> http_get('http://www.kuaidi100.com/query?type='.$type.'&postid='.$LogisticCode);

        //$data = file_get_contents('http://www.kuaidi100.com/query?type=zhongtong&postid=73108363614500&temp=0.5488788851402543&phone=');

    echo $data;die;

    }
    public function http_get($url)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象

    }

}
