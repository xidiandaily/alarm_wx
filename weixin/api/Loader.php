<?php
class Loader
{
    public static $config = null;
    public static $instance = null;
    public static function Mysql()
    {
        if(!isset(self::$instance["DB"]))
        {
            self::$instance["DB"] = new DBPDO();	
        }
        return self::$instance["DB"];
    }
}

