<?php

define("BOWL_ASSISTANT_BASE_DIR",dirname(__FILE__).DS);

/**
 * 项目助手
 * 用于修复项目目录结构
 * 暂时不推荐使用，还在测试中
 *
 * @package Bowl_Assistant
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Assistant_Project{

    static $needDirectory = array(

        //APP Folder
        array(
            "path"  => "App/",
            "desc"  => "主程序目录",
        ),
        array(
            "path"  => "App/Extension/",
            "desc"  => "主程序扩展目录",
        ),
        array(
            "path"  => "App/Controller/",
            "desc"  => "主程序控制器目录",
        ),
        array(
            "path"  => "App/Model/",
            "desc"  => "主程序模型目录",
        ),
        array(
            "path"  => "App/Table/",
            "desc"  => "主程序表目录",
        ),
        array(
            "path"  => "App/View/",
            "desc"  => "主程序视图目录",
        ),
        array(
            "path"  => "App/Worker/",
            "desc"  => "主程序工作者目录，放置异步任务脚本",
        ),
        array(
            "path"  => "App/Service/",
            "desc"  => "主程序服务目录",
        ),

        //~runtime folder
        array(
            "path"  => "~runtime/",
            "desc"  => "运行时目录,放置运行时产生的临时数据",
        ),
        array(
            "path"  => "~runtime/log/",
            "desc"  => "日志",
        ),
        array(
            "path"  => "~runtime/upload/",
            "desc"  => "上传目录",
        ),
        array(
            "path"  => "~runtime/smarty_complie/",
            "desc"  => "smarty模板编译存储目录",
        ),
        array(
            "path"  => "~runtime/smarty_cache/",
            "desc"  => "smarty模板缓存目录",
        ),
        array(
            "path"  => "~runtime/session/",
            "desc"  => "Session存储目录",
        ),

        //builder
        array(
            "path"  => "builder/",
            "desc"  => "项目部署目录，包括项目初始化脚本、安装向导、建表脚本等",
        ),

        //public
        array(
            "path"  => "public/",
            "desc"  => "可以通过WebServer访问的目录",
        ),
        //public--content
        array(
            "path"  => "public/content/",
            "desc"  => "内容目录，存储静态内容",
        ),
        array(
            "path"  => "public/content/images/",
            "desc"  => "图片内容存储",
        ),
        array(
            "path"  => "public/content/docs/",
            "desc"  => "文档内容存储",
        ),
        array(
            "path"  => "public/content/videos/",
            "desc"  => "视频内容存储",
        ),
        array(
            "path"  => "public/content/texts/",
            "desc"  => "文本内容存储",
        ),
        array(
            "path"  => "public/content/audios/",
            "desc"  => "音频内容存储",
        ),
        array(
            "path"  => "public/content/jslibs/",
            "desc"  => "Javascript库",
        ),
    );

    static $phpExtensions = array(

        array(
          "name" => "mbstring",
          "module" => "mbstring",
          "required" => true,
          "desc" => "中文字符处理利器",
        ),

        array(
            "name" => "curl",
            "module" => "curl",
            "required" => true,
            "desc" => "进行http请求",
        ),

        array(
            "name" => "pdoMysql",
            "module" => "pdo_mysql",
            "required" => false,
            "desc" => "Mysql扩展，连接Mysql时必须安装",
        ),

        array(
            "name" => "pdoOcracle",
            "module" => "pdo_oci",
            "required" => false,
            "desc" => "Oracle扩展，连接Oracle数据库时必须安装",
        ),

        array(
            "name" => "pdoInformix",
            "module" => "pdo_informix",
            "required" => false,
            "desc" => "Informix扩展，连接Informix数据库时必须安装",
        ),
        array(
            "name" => "imagick",
            "module" => "imagick",
            "required" => false,
            "desc" => "图片处理利器，当使用Bowl_Media_Image模块时必须安装",
        ),

        array(
            "name" => "gd",
            "module"=>"gd",
            "required" => false,
            "desc" => "图片处理工具,目前逐步淘汰中",
        ),

    );

    /**
     * 检查目录结构
     * 查看是否缺失必要目录
     * @static
     */
    static function envCheck(){

        /***********************
         * 目录结构
         **********************/
        $directorys = array();

        foreach(self::$needDirectory as $dir){
            $dir['exists'] = is_dir(BOWL_BASE_DIR.$dir['path']);
            array_push($directorys,$dir);
        }

        /*****************************
         * 视图目录
         *****************************/

        $useTheme = Flea::getAppInf("Bowl_View_UseTheme");

        $viewConfig = Flea::getAppInf("Bowl_View_Theme_".$useTheme);
        
        $phpExtensions = self::$phpExtensions;
        require(BOWL_ASSISTANT_BASE_DIR."Project_env_view.php");
        exit(0);
    }

    /**
     * 修复项目目录
     * 用来修复缺失目录
     * @static
     */
    static function repair(){

        foreach(self::$needDirectory as $dir){
            if(!is_dir(BOWL_BASE_DIR.$dir['path'])){
                Bowl_Helper_FileSystem::mkdirs(BOWL_BASE_DIR.$dir['path']);
                echo "创建目录".BOWL_BASE_DIR.$dir['path']."<br>";
            }
        }
        $useTheme = Flea::getAppInf("Bowl_View_UseTheme");
        $viewConfig = Flea::getAppInf("Bowl_View_Theme_".$useTheme);
        if(!is_dir($viewConfig['SmartyConfig']['template_dir'])){
            Bowl_Helper_FileSystem::mkdirs($viewConfig['SmartyConfig']['template_dir']);
            echo "创建目录".$viewConfig['SmartyConfig']['template_dir']."<br>";
        }

        if(!is_dir($viewConfig['SmartyConfig']['compile_dir'])){
            Bowl_Helper_FileSystem::mkdirs($viewConfig['SmartyConfig']['compile_dir']);
            echo "创建目录".$viewConfig['SmartyConfig']['compile_dir']."<br>";
        }

        if(!is_dir($viewConfig['SmartyConfig']['cache_dir'])){
            Bowl_Helper_FileSystem::mkdirs($viewConfig['SmartyConfig']['cache_dir']);
            echo "创建目录".$viewConfig['SmartyConfig']['cache_dir']."<br>";
        }

        echo "修复完成";
        exit(0);

    }

}