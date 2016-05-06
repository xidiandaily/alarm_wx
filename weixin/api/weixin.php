<?php
include_once("conf.php");
include_once("../3rd/PHP-MySQL-Class/class.DBPDO.php");
include_once("Loader.php");
class CWeiXin
{
    function GetToken()
    {
        $dbvalue=Loader::Mysql()->fetch('select token_value,expire,ts_update where token_name="access_token"');
        if(empty($dbvalue) || time()<= $dbvalue['expire']+$dbvalue['ts_update'])
        {
            $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.APPID.'&secret='.SECRET;
            $str_conten = file_get_contents($url);
            $var = json_decode($str_conten);
            if(isset($var['errcode']))
            {
                var_dump($var);
                die();
            }

            Loader::Mysql()->execute('update weixin set token_value='.$var["access_token"].',expire='.$var['expires_in'].' where token_name="access_token"');
            return $var['access_token'];
        }

        return $dbvalue['access_token'];
    }

    private function get_token_from_db()
    {
        $token;
        return $token;
    }
}

