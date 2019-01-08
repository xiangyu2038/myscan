<?php

namespace App\Http\Controllers\Api\PDA;
use App\Http\Controllers\Api\Controller;
use App\Models\Admin\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{

    /**
     * @apiDefine group_pda_login pda登陆模块
     */
    /**
     * @api {post} /api/pda/login pda登陆接口
     * @apiVersion 2.0.0
     * @apiName login
     * @apiGroup group_pda_login
     * @apiPermission 所有用户
     *
     * @apiParam {int} user_name 用户名称
     * @apiParam {int} password 用户密码
     * @apiDescription pda登陆接口api
     *
     * @apiSampleRequest  /api/pda/login
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     * @apiSuccess (返回值) {string} token_type 令牌类型
     * @apiSuccess (返回值) {string} expires_in 过期事时间
     * @apiSuccess (返回值) {string} access_token 令牌
     * @apiSuccess (返回值) {string} refresh_token 刷新令牌
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"token_type":"Bearer","expires_in":31536000,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIwMzQ4Y2M0MzQ3MWU0ZGRjNTY3NWQxNjM3MDc3MmFmZTk5ZmUzOWJjM2U0ODYwZGU3YjQ4NDA2YzE0MGQ0ZmY0YjJjMjM1MzNhNjA4MzkxIn0.eyJhdWQiOiIyIiwianRpIjoiMjAzNDhjYzQzNDcxZTRkZGM1Njc1ZDE2MzcwNzcyYWZlOTlmZTM5YmMzZTQ4NjBkZTdiNDg0MDZjMTQwZDRmZjRiMmMyMzUzM2E2MDgzOTEiLCJpYXQiOjE1NDY4NDE0NzgsIm5iZiI6MTU0Njg0MTQ3OCwiZXhwIjoxNTc4Mzc3NDc4LCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.PYoqwuH1-jC-T6cfjO8zQJtmt3olJ5R-xqEcxXb1TCcua-daFvAJKzMYHS2-oLKcheaEcPUNZVjQjpBRva6aQelH74ML_xNjvfbtpJ7hcDx6PO2wAZ_pwTM7LF9ODwxTqhw5GLBpv_zdXekpCrpNTX5GhesAIYIfDGWDag_bO2KtEmln7F3GuEc5qW1KCQfGN0ZqxGJNhSGy_sxQAf45b14gB17BPr89UjYfFVXsE2feHjVRBpa84oGHtNMB3wRTU58MNUzk0i4ur9zaHfOkFQqq0bcPZVRqQucicrgwDF3Th418TC-pKw3q_fOianLevvC7LwNWP7gm844fC-A8yoeAB-SbekudHMK6kNS8SGrzScRyXYLLBtijH8Ky5P0m3y5JQuIA1RVxLTOnguLGHvRiLeHgziU0XccA9n2TKhr1g71jlb3xdP5GSW8gyoZ4jLNq9du0gd6y1TtL2cpSWFqHE4aCtG_wUbGSnr1_Fv_HrcSUPjMGTj3hhxP0UTGb1RV6vstkKmxNXG6r52XXde8swugOePDTWv3LM2S4HBMXteNPYVvzmFb9RjyAjoMsvSO4H36Dyk8KUyoviB4mcgShrrkFZcPU4WIc_1XEdVp1uMqqjwjX-GVQcMExmeimWkJmG6w0UnVEe9ypEcH_7pz-VnOH0gNcCdnezNOfUVQ","refresh_token":"def50200c677edd71cbfbeefb2577fcf3aeded75295d4f9ce2964647b34b369dabb46189551f19bd65978ffdad05ace040a20dfbc3d0058dda9a61f1cd5d61d67192d4a9fc6e9a8c20a616da87be98115f26e2a3ab7365849540c4c85efb976e61ef1873b63388b9a66ffa367966e4074287ef7151ea715f44d8ae742ac6126d5d62f51c06b74a000df98529797b2511d40cd611e7f3eee7e52d928e0e5deb09b470b9d9142463fa06a93adbc4b6124614ab0194242e8296797024098353347207291003deb61d7ebceb9f8ebf6e5cceb4407426bbeb2fa0d73b89dd4d3a2d2e673f607b69d97c8446dab6be00b8953452740b1fb5c17ef87e5024019e0fb6ce7020e1a4cca14ef1d89017194456eee1a1d135d0acb96a7b47cea912e2a2e69dc66378394500d03282bd7b806d34d7f96bbaa9df0e7b1503c45a5375e22e8834eb0ac71d5ffb5cff423a8ed0b016a4d6fa74055cee4fc31015bbb1bfee87e89fe4a4"}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function login(Request $request){

        $user_name = $request -> post('user_name');
        $password = $request -> post('password');


        $request->request->add([
               'grant_type' => 'password',
               'client_id' => 2,
               'client_secret' => 'vr6TGlgIFwSF76Ad0HCdT13vVvKVpjrg75y1GIDC',
               'username' => $user_name,  // 这里传递的是邮箱
               'password' => $password, // 传递密码信息
               'scope' => '*'
           ]);

           $proxy = Request::create(
               'oauth/token',
               'POST'
           );

           $response = \Route::dispatch($proxy);
           $content = json_decode($response->getContent(),true);

           //$value = Cache::set('bb',$content,3);
           //$a = Cache::store('file')->get('bb');
           //dd($a);

           if(isset($content['error'])){
               return msg(1,$content['message']);
           }else{
               if($content){
                   return msg(0,'ok',$content);
               }else{
                   return msg(1,'失败');
               }

           }

    }


    /**
     * @api {get} /api/pda/refresh 刷新一个令牌
     * @apiVersion 2.0.0
     * @apiName refresh
     * @apiGroup group_pda_login
     * @apiPermission 登录用户
     *
     * @apiParam {int} address_id 收货地址信息
     * @apiParam {int} address_id 收货地址信息
     * @apiParam {int} address_id 收货地址信息
     * @apiDescription 刷新一个令牌api
     *
     * @apiSampleRequest  /api/pda/refresh
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     * @apiSuccess (返回值) {string} token_type 令牌类型
     * @apiSuccess (返回值) {string} expires_in 过期事时间
     * @apiSuccess (返回值) {string} access_token 令牌
     * @apiSuccess (返回值) {string} refresh_token 刷新令牌
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"ok","data":{"token_type":"Bearer","expires_in":31536000,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYzYmU1MWUyY2UzNzNhNjE3NmNhNWM2ZGZkOGU0YmMxZDJlNDI1NzFiYjdlYTMwMGQzOGY0ZGM0ZGZmMzM0OGM4ZGUzNjE3NzVhNzNiMGQ4In0.eyJhdWQiOiIyIiwianRpIjoiNjNiZTUxZTJjZTM3M2E2MTc2Y2E1YzZkZmQ4ZTRiYzFkMmU0MjU3MWJiN2VhMzAwZDM4ZjRkYzRkZmYzMzQ4YzhkZTM2MTc3NWE3M2IwZDgiLCJpYXQiOjE1NDY4NDgwMDQsIm5iZiI6MTU0Njg0ODAwNCwiZXhwIjoxNTc4Mzg0MDA0LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.AW6utXKX7Sj4eAOIEJ609HVQGPdxeSUKix2gMuekCe8BTFD1RZcpV8w8UXz8MSk5PQuFww09LMxiMmEx-XNjeYXyy3wMIPJTM8jIK27lYO02MnnUpC5Lg3MEzcUsven7SOq5r37rz4AVNQ6fLdFprqt846X0A9UjgtoUoYgZjqKcqZ3QhekiAppaUcTNMJncOhO08JKqspzpJ98ua_049ePpjnGf_pQcr4NGzuXHBeWJZ7wEJe393GEPhZ8uhJacUhmqSwB_hAwryYpM2X5Kmj-QiwWqVZtxk9CJKYaeZQc5ApCofsUY8ZAiAy2xdvGREr-fXWVT_Z5AmI7olvpPaXP0yC9hQ7uNF_fsTx5ucJJoFrSfniZuxTVKlWc_L1Rs_SOIQHVo2hRmJA90bzOZSYAiO2TKOmPrcn-2dqYifceru6jkQRqidH-U_u5lkLeCA_8fY7a2PsKKI2fWqy5Nr70GbvABVRd-Af-rN0I5n-FT_eJNOuhBw70CXMX3wukbFBWtrTdDi1mXL0ICGfqD2_wvzx28sX1A2o0gruz68fvxqTywjHaxpaQ9iBkplGl-3Zx9K54aZ5JE2y5Kut2LkdMpOSKE8tWbE1lwxOy79noR19xn0bbXti7ZwyJtHnFcoH_PD2DcAYmS7-2JW8MCnBrKOt0uw58TU_hgrOVn3e4","refresh_token":"def502009e25960be4ad4175daf11c085f4c3a798670412b06a861dd26d871b8697c74232dfd0e53e7c951dcac27643c1da2d62d2c057fff31a7815d19002d31f9914edfcd9c996fbf69e5ef09ca6820b9eff1bef37a47862ab425fa93a6e4549e5650844dc61917dbb56e260c4d7ca411b29d925e8f6ef1124c55a1242f30ef8accdcbbe721dbf441b4f2e3e9996a697a22e3b98aabdf918b301a6250f8bf31ae8b14f7aba4c05f2a20b31471670f34d166e86ccaac8b63d8d626a8c2cfde4a66c545478912287df7114eac10258cc73527ac9428c949e827cc8260fe5074ef8e9e5c17794b8c93b87d59c6bf531fd40c44d48c777f8cb81b4c291ad2e50ba26cf476b95a43c1ff655e857a41e614332a2a7c3048a8edd199484cacadb9fbaa61054ddf1234855b6bb9139ba9fcd2c8c8a2da5e43cba3d8339e90f229bdc02ea81dfac6ab7faa4256c11de0a6742ae570d8ba6eb1e7df85561fa1f35b797f"}}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
public function refresh(Request $request){
    $refresh_token = $request->get('refresh_token');
    $request->request->add([
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'client_id' => 2,
        'client_secret' => 'vr6TGlgIFwSF76Ad0HCdT13vVvKVpjrg75y1GIDC',
        'scope' => '',
    ]);

    $proxy = Request::create(
        'oauth/token',
        'POST'
    );

    $response = \Route::dispatch($proxy);
    $content = json_decode($response->getContent(),true);

    if(isset($content['error'])){
        return msg(1,$content['message']);
    }else{
        if($content){
            return msg(0,'ok',$content);
        }else{
            return msg(1,'失败');
        }
    }
}


    /**
     * @api {get} /api/pda/logout pda登出操作
     * @apiVersion 2.0.0
     * @apiName logout
     * @apiGroup group_pda_login
     * @apiPermission 登录用户
     *

     * @apiDescription pda登出操作api
     *
     * @apiSampleRequest  /api/pda/logout
     *
     * @apiSuccess (返回值) {int} code 状态码
     * @apiSuccess (返回值) {string} msg 提示信息
     *
     * @apiSuccessExample {json} 成功示例:
     * {"code":0,"msg":"\u767b\u51fa\u6210\u529f","data":null}
     *
     * @apiErrorExample (json) 错误示例:
     *     {"code":"1","msg":"失败","data":[]}
     */
    public function logout(Request $request)
    {
        ////退出登陆
        if (\Auth::guard('api')->check()) {
            \Auth::guard('api')->user()->token()->delete();
        }

        return response()->json(msg(0,'登出成功'));
    }

}