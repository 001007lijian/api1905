<?php

namespace App\Http\Controllers\alipay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayController extends Controller
{
    /*
     * 支付宝异步通知  https://docs.open.alipay.com/200/106120
     * 普通公钥验签
     */
    public function notify()
    {
        // 1 接收  支付宝的POST数据
//        $data=file_get_contents("php://input");
        $data2=json_encode($_POST);
        //log1=date('Y-m-d H:i:s') . '>>>' .$data2 ."\n";
        $log2=date('Y-m-d H:i:s') . '>>>' .$data2 ."\n";
//        file_put_contents('alipay.log',$log1,FILE_APPEND);
        file_put_contents('logs/alipay.log',$log2,FILE_APPEND);
        $data=$_POST;
        $sign=base64_encode($data['sign']);
        unset($data['sign_type']);
        unset($data['sign']);

//        echo "<pre>";   print_r($data);  echo "</pre>";
        $d=[];
        //2 url_decode
        foreach($data as $k => $v){
            $d[$k]=urldecode($v);
        }

//        echo "<pre>";   print_r($d);  echo "</pre>";
        ksort($d);
        $str="";
        foreach($d as $k=>$v){
            $str .= $k . '=' . $v .'&';
        }


        //带名的字符串
        $str=rtrim($str,'&');
        //读取公钥文件
        $pubKey=file_get_contents(storage_path('keys/ali_pub'));
        //转换为 openssl 格式密钥
        $res=openssl_get_publickey($pubKey);
        //验证签名
        $result=(bool)openssl_verify($str,$sign,$res,OPENSSL_ALGO_SHA256);
        //释放资源
        openssl_free_key($res);
        if($result){
            $log=date('Y-m-d H:i:s') . '>>> 验签通过 1' . "\n\n";
            file_put_contents("logs/alipay.log",$log,FILE_APPEND);
        }else{
            $log=date('Y-m-d H:i:s') . '>>> 验签失败 0' . "\n\n";
            file_put_contents("logs/alipay.log",$log,FILE_APPEND);
        }

        echo '成功';
    }

    public function return()
    {
        echo "<pre>";   print_r($_GET);  echo "</pre>";
    }

    protected function verify($data,$sign)
    {
        //读取公钥文件
        $pubKey=file_get_contents(storage_path('keys/ali_pub'));
        //转换为openssl格式密钥
        $res=openssl_get_publickey($pubKey);
        $result=(bool)openssl_verify($data,$sign,$res,OPENSSL_ALGO_SHA256);
        //释放资源
        openssl_free_key($res);
        var_dump($result);

        return $result;
    }
}
