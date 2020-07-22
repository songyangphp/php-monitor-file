<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-20
 * Time: 10:55
 */
namespace wslibs\msg;


class SendMsg
{

    const DOSEND_URL = "http://sendmsg.wszx.cc/?app=sendmsg@dosend";

    const CKECK_CODE_URL = "http://sendmsg.wszx.cc/?app=sendmsg@checkcode";

    protected static $_sign;

    public static function setSign($sign)
    {
        self::$_sign = $sign;
    }

    /**
     * 发送验证码
     */
    public static function sendCodeMsg($phone, $tem_id = '')
    {
        return self::doSend($phone,['type' => 'code'],$tem_id);
    }

    /**
     * 校验验证码
     */
    public static function checkCode($phone , $code)
    {
        $content = ['code' => $code , 'phone' => $phone];
        $request = json_decode(self::curlRequest(self::CKECK_CODE_URL,false,'post',$content),true);
        if($request['code'] == '100'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 发送变量短信
     */
    public static function sendTemMsg($phone, $tem_id ,array $content)
    {
        return self::doSend($phone,['msg' => json_encode($content)],$tem_id);
    }

    /**
     * 发送任意短信
     */
    public static function sendOtherMsg($phone , $content)
    {
        return self::doSend($phone,['type' => 'other','msg' => $content]);
    }


    protected static function doSend($phone, $content = [], $tem_id = '')
    {
        $content = array_merge($content,$base_content = [
            'sign' => self::$_sign,
            'phone' => $phone,
            'tem_id' => $tem_id,
        ]);

        /*var_dump($content);die;*/
        $request = json_decode(self::curlRequest(self::DOSEND_URL,false,'post',$content),true);
        if(!empty($_REQUEST['debug'])){
            var_dump($request);exit;
        }
        if($request['code'] == '100'){
            return true;
        }else{
            return false;
        }
    }

    protected static function curlRequest($url, $https = true, $method = "get", $data = null, $json = false)
    {
        $headers = array(
            "Content-type: application/json;charset='utf-8'",
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https === true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($method === 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($json) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}