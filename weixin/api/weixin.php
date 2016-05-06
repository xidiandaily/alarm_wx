<?php
include_once("conf.php");
include_once("../3rd/PHP-MySQL-Class/class.DBPDO.php");
include_once("Loader.php");
class CWeiXin
{
    function GetToken($update=false)
    {
        $dbvalue=Loader::Mysql()->fetch('select token_value as access_token,expire,unix_timestamp(ts_update) as ts_update from weixin_token where token_name="access_token"');
        if($update || empty($dbvalue) || time()> $dbvalue['expire']+$dbvalue['ts_update'])
        {
            $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.APPID.'&secret='.SECRET;
            $str_conten = file_get_contents($url);
            $var = json_decode($str_conten,true);
            if(array_key_exists('errcode',$var))
            {
                var_dump($var);
                die();
            }
            $sql='update weixin_token set token_value="'.$var["access_token"].'",expire='.$var['expires_in'].' where token_name="access_token"';
            Loader::Mysql()->execute($sql);
            return $var['access_token'];
        }

        return $dbvalue['access_token'];
    }

    function SendMsg($openid,$msg)
    {
        if(!isset($openid))
        {
            return;
        }
        $val=array();
        $val['touser']=$openid;
        $val['msgtype']='text';
        $val['text']["content"]=$msg;

        $post_str=json_encode($val);
        $access_token=self::GetToken();

        $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_str);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret_str = curl_exec($ch);
        curl_close($ch);
        return $curl_close;
    }
}

