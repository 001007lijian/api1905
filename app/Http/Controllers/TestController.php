<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function alipay()
    {
        //支付网关
        $ali_gateway="https://openapi.alipaydev.com/gateway.do";
        //公共请求参数
        $appid="2016093000632267";
        $method="alipay.trade.page.pay";
        $charset="utf-8";
        $sign_type="RSA2";
        $sign="";
        $timestamp=date('Y-m-d H:i:s');
        $version='1.0';
        $return_url = 'http://jianxiao.xx20.top/alipay/return'; // 支付宝同步通知
        $notify_url = "http://jianxiao.xx20.top/alipay/notify";   //支付宝异步通知地址
        $biz_content='';

        //请求参数s
        $out_trade_no=time() . rand(11111,99999);   //订单号
        $product_code='FAST_INSTANT_TRADE_PAY';
        $total_amount='0.01';
        $subject='订单测试' . $out_trade_no;

        $request_param=[
            'out_trade_no'  =>  $out_trade_no,
            'product_code'  =>  $product_code,
            '$total_amount' =>  $total_amount,
            'subject'       =>  $subject,
        ];

        $param=[
            'opp_id'        =>  $appid,
            'method'        =>  $method,
            'charset'       =>  $charset,
            'sign_type'     =>  $sign_type,
            'timestamp'     =>  $timestamp,
            'version'       =>  $version,
            'notify_url'    =>  $notify_url,
            'return_url'    =>  $return_url,
            'biz_content'   =>  json_encode($request_param)
        ];
//        echo "<pre>";   print_r($param);   echo "</pre>";
        ksort($param);  //字典序排序

        //拼接 key1=value&key2=value1...
        $str="";
        foreach($param as $k=>$v){
            $str .=$k . '=' .$v. '&';
        }
//        echo  'str: ' .$str;die;

        $str=rtrim($str,'&');
        //计算签名   https://docs.open.alipay.com/291/106118
        $key=storage_path('keys/app_priv');     //私钥
        $priKey=file_get_contents($key);    //主键
        $res=openssl_get_privatekey($priKey);
//        var_dump($res);echo "</br>";
        openssl_sign($str,$sign,$res,OPENSSL_ALGO_SHA256);
        $sign=base64_encode($sign);
        $param['sign']=$sign;

        //4 urlencode
        $param_str='?';
        foreach($param as $k => $v){
            $param_str .=$k.'=' .urlencode($v) . '&';
        }
        $param_str=rtrim($param_str,'&');
        $url=$ali_gateway . $param_str;

        //发送GET请求
//        echo $url;die;
        header("Location:" .$url);
    }
}
