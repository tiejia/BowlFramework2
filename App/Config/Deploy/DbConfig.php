<?php

 /**
  * 数据库配置
  * 基于Bowl_DB
  * @author zhaotiejia@ebupt.com
  * @version 0.1
  * @package BowlFramework
  */
 return array(
    /*
     * ---------------------------------------
     * 数据库DSN
     * ---------------------------------------
     */
 	"Bowl_Db_Dsn" => array(
        //数据库类型  {{oracle=>oracle  mysql=>mysql informix=>informix}}
        'phptype'  => "mysql",
        //数据库用户名 {{}}
	    'username' => "root",
        //数据库密码  {{}}
	    'password' => "root",
        //数据库服务器主机 {{}}
	    'hostspec' => "localhost",
        //数据库端口  {{oracle默认:1521 mysql默认：3306}}
	    //'port'     => "3306",
        //database  {{ORACLE，填写SERVICE_ID}}
	    'database'  => "bowltest",
         //InformixOnly
        //'server'    => 'db154'
    ),
    /*
     * ---------------------------------------
     * 数据库配置选项
     * 设置字符集、连接方式
     * 根据项目情况设置
     * ---------------------------------------
     */
    "Bowl_Db_Options"=>array(
        /**
         * --------------------------
         * 数据库字符集编码
         * VALUES:
         * @example GBK GB2312 UTF-8
         * --------------------------
         */
        'db_charset' =>"GBK",
        /**
         * --------------------------
         * 客户端字符集编码
         * VALUES:
         * @exaple GBK GB2312 UTF-8
         * --------------------------
         */
        'client_charset'=>"UTF-8",
        /*
         * ---------------------------
         * 数据库返回结果属性大小写
         * VALUES：
         * BOWL_DB_CASE_LOWER : 小写
         * BOWL_DB_CASE_NATURAL : 与数据库一致
         * BOWL_DB_CASE_UPER    : 全大写
         * ---------------------------
         */
        'BOWL_DB_ATTR_CASE'=>'BOWL_DB_CASE_LOWER',
        /*
         * ---------------------------------
         * 数据库错误模式
         * VALUES:
         * (string)BOWL_DB_ERRMODE_EXCEPTION : 异常
         * (string)BOWL_DB_ERRMODE_SILENT    : 无错误
         * (string)BOWL_DB_ERRMODE_WARNING   : 警告
         * ---------------------------------
         */
        'BOWL_DB_ATTR_ERRMODE'=>'BOWL_DB_ERRMODE_EXCEPTION',
        /*
         * ---------------------------------
         * 数据库连接方式
         * VALUES:
         * (boolean)true    : 长连接
         * (boolean)false   : 短连接
         * ---------------------------------
         */
        'BOWL_DB_ATTR_PERSISTENT'=>false
    ),

 );
?>