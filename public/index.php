<?php
/*
 * ----------------------------------------------------
 *                     BowlFramework
 *
 *  框架启动文件，该文件完成PHP配置文件加载，运行环境适配功能
 *  使用时只需更改BOWL_RUN_MODE，其余配置项不必变动
 *
 *  @author zhaotiejia@ebupt.com
 *  @package bootStrap.BowlFramework
 *  @version 0.1
 *  @lastupdate 2011-08-04
 * ----------------------------------------------------
 */


/**
 * -------------------------------
 * BOWL_RUN_MODE
 *
 * 开发框架运行模式
 *
 * debug   : 开发模式
 * deploy  : 部署模式
 * test    : 测试模式
 * --------------------------------
 */
define("BOWL_RUN_MODE","debug");
//projectBASEDIR
define("BOWL_BASE_DIR",dirname(dirname(__FILE__))."/");
define("BOWL_BASE_URL","http://10.1.60.154:8888/BowlFramework2/public/");

//载入FleaPHP框架
require(BOWL_BASE_DIR."/lib/FLEA/FLEA.php");

//载入Bowl库文件
require(BOWL_BASE_DIR."/lib/Bowl/Bowl.php");

//导入主程序目录
FLEA::import(BOWL_BASE_DIR."/App/");
//导入Bowl
FLEA::import(BOWL_BASE_DIR."/lib/Bowl");


$__configFileDir = BOWL_BASE_DIR."App/Config/Debug/";

switch(BOWL_RUN_MODE){
    case "debug" :
        $__configFileDir = BOWL_BASE_DIR."App/Config/Debug/";
        error_reporting(E_ALL&~E_NOTICE);
        break;
    case "deploy"   :
        $__configFileDir = BOWL_BASE_DIR."App/Config/Deploy/";
        error_reporting(0);
        break;
    case "test"    :
        $__configFileDir = BOWL_BASE_DIR."App/Config/Test/";
        error_reporting(E_ALL);
        break;
    default:
        $__configFileDir = BOWL_BASE_DIR."App/Config/Debug/";
        error_reporting(E_ALL&~E_NOTICE);
}

define("BOWL_CONFIG_DIR",$__configFileDir);

//加载配置文件
FLEA::loadAppInf($__configFileDir."AppConfig.php");
FLEA::loadAppInf($__configFileDir."DbConfig.php");
FLEA::loadAppInf($__configFileDir."ServiceConfig.php");
FLEA::loadAppInf($__configFileDir."ViewerConfig.php");
FLEA::loadAppInf($__configFileDir."ContentConfig.php");
FLEA::loadAppInf($__configFileDir."UserConfig.php");

//设置Session
session_save_path(Flea::getAppInf("sessionDir"));
session_start();

//Let's go !
FLEA::runMVC();

?>