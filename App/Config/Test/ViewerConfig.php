<?php
 /**
  * 视图配置
  * @author zhaotiejia@ebupt.com
  * @version 0.1
  * @package BowlFrame
  */ 
 return array(
    
    /********************
     * 核心配置
     *******************/
 
    //当前使用的主题 {{与View中的目录对应}}
    "Bowl_View_UseTheme"=>"martini",
     
    /******************************
     * 单主题配置
     * 规则："Bowl_View_Theme_"+{主题名称}
     ******************************/

     /***Martini主题***/
    "Bowl_View_Theme_martini"=>array(
        
        //Samrty配置{{请查看Smarty主页获取更多信息}}
	    "SmartyConfig"=>array(
			"template_dir"=>BOWL_BASE_DIR."App/View/martini/",
			"compile_dir"=>BOWL_BASE_DIR."~runtime/smarty_complie/martini/",
			"cache_dir"=>BOWL_BASE_DIR."~runtime/smarty_cache/",
			"left_delimiter" => "{%",
		    "right_delimiter"=> "%}"
	    ),
	    
	    //Css服务器URL
	    "CssServer"=>BOWL_BASE_URL."martini/css/",
	    
	    //Js服务器URL
	    "JsServer"=>BOWL_BASE_URL."martini/js/",
	    
	    //图标服务器URL
	    "IconServer"=>BOWL_BASE_URL."martini/images/",
	    
	    //Flash服务器URL
	    "FlashServer"=>BOWL_BASE_URL."martini/flashes/",
	    
	    //默认加载的Js文件
	    "LoadJsFiles"=>array(
	        "http://10.1.60.154:8001/staticServer/jquery-1.5.2.js",
	    ),
	    //默认加载的Css文件
	    "LoadCssFiles"=>array(
	    )
    )
 );
?>