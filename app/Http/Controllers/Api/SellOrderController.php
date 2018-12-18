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

}
