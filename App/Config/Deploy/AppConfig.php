<?php
/**
 * 程序基本配置文件
 * ----------------------------
 * wiki:
 * ----------------------------
 * @filesource AppConfig.php
 * @author sunjiadong&zhaotiejia
 * Debug模式程序基本配置文件
 * @since bowl 1.0
 * 具体信息查看FleaPHP默认配置文件
 * @notice 请勿删除该文件内的配置项
 */

return array (

    /**************************
     * 基础配置
     **************************/

    //默认控制器 {{URL中没有指定控制器时将调用该控制器}}
    'defaultController'         => 'Default',

    //默认动作 {{URL中没有指定动作名时将调用该动作}}
    'defaultAction'             => 'index',

    //是否强制将链接地址转换为小写
    'urlLowerChar'              => true,

    /************************************
     * 语言包配置（注意：当前版本暂不支持语言包）
     ************************************/

    //是否开启多语言支持
    'multiLanguageSupport'      => false,

    //语言文件目录 {{默认为APP/Lang}} @notice 如无特殊情况请勿修改
    'languageFilesDir'          => null,

    //指定使用语言 --暂时不支持语言包
    'defaultLanguage'           => 'chinese-utf8',

    //自动载入的语言文件
    'autoLoadLanguage'          => null,


    /*****************************
     * SESSION
     ****************************/
    //Session文件 存储目录
    'sessionDir'                => BOWL_BASE_DIR."~runtime/session/",


    /*****************************
     * 日志相关配置
     ****************************/

    //是否启用日志功能
    'logEnabled'                => true,

    //日志文件保存目录  {{BowlFrame默认目录}}
    'logFileDir'                => BOWL_BASE_DIR."/~runtime/log/",

    //日志文件名称
    'logFilename'               => 'bowl.log',

    //日志文件最大空间{{超出此大小后，将会新建一个文件存储日志}}
    'logFileMaxSize'            => 4096,

    //日志级别
    'logErrorLevel'             => 'warning, error, exception, log , systrack , debug',

    //错误处理机制
    //--Boolean 返回False
    //--Exception 抛出异常
    //--Error	抛出PHP错误（程序执行将终止）
    'errorHandler'				=> "Boolean"
    // }}}
   );
?>
