<?php
/**
 * 数据库组件驱动工厂
 * 目前支持pdo_informix、pdo_mysql、pdo_oracle
 * @package Bowl_Db
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 */

  class Bowl_Db_Driver{

    static $loadedDrivers = array();

    /**
     * 生产驱动
     * 注意：开发人员不需要显试调用该方法
     *
     * @static
     * @param $dbhost
     * @param $dbname
     * @param $dbuser
     * @param $dbpasswd
     * @return
     *
     */
    static function factory($type,$dsn,$options){
        $keyGen = $type."_".implode("_",$dsn);
		if(isset(self::$loadedDrivers[$keyGen])) return self::$loadedDrivers[$keyGen];
		switch($type){
			case "mysql":{
				require_once dirname(__FILE__).DS."Driver".DS."Pdo".DS."Mysql.php";
				self::$loadedDrivers[$keyGen] = new Bowl_Db_Driver_Pdo_Mysql($dsn,$options);
				return self::$loadedDrivers[$keyGen];
                break;
			}
            case "oracle":{
               require_once dirname(__FILE__).DS."Driver".DS."Pdo".DS."Oracle.php";
                self::$loadedDrivers[$keyGen] = new Bowl_Db_Driver_Pdo_Oracle($dsn,$options);
				return self::$loadedDrivers[$keyGen];
                break;
            }
            case "informix":{
                require_once dirname(__FILE__).DS."Driver".DS."Pdo".DS."Informix.php";
                self::$loadedDrivers[$keyGen] = new Bowl_Db_Driver_Pdo_Informix($dsn,$options);
				return self::$loadedDrivers[$keyGen];
                break;
            }
			default :{
				die("you select driver type $type not support!");
			}
		}
    }
}