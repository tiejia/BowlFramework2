<?php
require_once(BOWL_BASE_DIR . "/lib/3rd/Smarty/Smarty.class.php");
/**
 *
 * BowlFramework的Smarty视图类
 * 扩展自Smarty的视图类
 *
 * @package Bowl_View
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_View_Smarty extends Smarty
{
    /**
     * 当前使用的主题
     * @access private
     * @var string
     */
    private $useTheme;
    /**
     * Widgets对象
     * 暂不启用
     * @access private
     * @var string
     */
    private $widgets;
    /**
     * 需要加载的Js文件
     * @access private
     * @var array
     */
    private $loadJsFiles = array();
    /**
     * 需要加载的Css文件
     * @access private
     * @var array
     */
    private $loadCssFiles = array();
    /**
     * 需要在前台使用的变量
     * @access private
     * @var array
     */
    private $shareVars = array();
    /**
     * Css服务URL
     * HTML中所有*.css文件均以此为加载地址
     * @access private
     * @var string
     */
    private $cssServer;
    /**
     * Js服务URL
     * HTML中所有*.js文件均已此URL为加载地址
     * @access private
     * @var string
     */
    private $jsServer;
    /**
     * 图标服务URL
     * 页面中所有的图标、背景等静态文件均以此为加载地址
     * @access private
     * @var string
     */
    private $iconServer;
    /**
     * Flash服务URL
     * @access private
     * @var string
     */
    private $flashServer;
    /**
     * 网站根地址
     * @access private
     * @var string
     */
    private $baseUrl;
    /**
     * 主题配置项前缀
     * @access private
     * @var string
     */
    private $themeConfigPrefix = "Bowl_View_Theme_";
    /**
     * 主题配置
     * @access private
     * @var array
     */
    private $themeConfig;
    /**
     * Smarty配置
     * @access private
     * @var array
     */
    private $smartyConfig = array();

    /**
     * 网页编码
     * @access private
     * @var string
     */
    private $contentCharset = "UTF-8";

    /**
     * 网页标题
     * @access public
     * @var string
     */
    public $pageTitle = "BowlFramework";


    public function __construct()
    {
        parent::Smarty();
        //加入FleaPHP的Smarty助手
        FLEA::loadClass('FLEA_View_SmartyHelper');
        new FLEA_View_SmartyHelper($this);
        $this->init();
    }

    /**
     * 视图初始化
     *
     * @param null $theme 使用的主题
     */
    public function init($theme = null)
    {
        if (is_null($theme)) {
            $this->useTheme = Flea::getAppInf("Bowl_View_UseTheme");
        }
        //加载主题
        $this->loadTheme($theme);
    }

    /**
     * 添加Js文件
     * @param $jsFiles array
     * @return unknown_type
     */
    public function addJsFiles($jsFiles)
    {
        if (is_string($jsFiles)) {
            $this->loadJsFiles[] = $jsFiles;
        } else if (is_array($jsFiles)) {
            foreach ($jsFiles as $js) {
                array_push($this->loadJsFiles, $js);
            }
        } else {
            return false;
        }
    }

    /**
     * 获取将要加载的Js文件
     * @return array
     */
    public function getLoadedJsFiles()
    {
        return $this->loadJsFiles;
    }

    /**
     * 添加Css文件
     * @param $cssFiles Array||String
     * @return unknown_type
     */
    public function addCssFiles($cssFiles)
    {
        if (is_string($cssFiles)) {
            $this->loadCssFiles[] = $cssFiles;
        } else if (is_array($cssFiles)) {
            foreach ($cssFiles as $css) {
                array_push($this->loadCssFiles, $css);
            }
        } else {
            return false;
        }
    }

    /**
     * 获取将要加载的Css文件
     * @return array
     */
    public function getLoadedCssFiles()
    {
        return $this->loadCssFiles;
    }

    /**
     * 添加js变量
     * 方法暂时停用
     * @param $shareVars Array||String
     * @param $shareVars String
     * @return unknown_type
     */
    public function addShareVar($shareVars, $value = '')
    {
        if (is_string($shareVars)) {
            $this->shareVars[$shareVars] = $value;
            return true;
        }
        if (is_array($shareVars)) {
            $this->shareVars = $this->shareVars + $shareVars;
            return true;
        }
        return false;
    }

    /**
     * 获取将要加载的变量
     * 方法暂时停用
     * @return ARRAY
     */
    public function getShareVar()
    {
        return $this->shareVars;
    }

    /**
     * 渲染HTML页面
     * 与Smarty的display一样
     * @param $tplFile 模板名称
     * @return unknown_type
     */
    public function render($tplFile, $exit = true)
    {
        $this->bootSamrty();
        $this->display($tplFile);
        if ($exit) exit(0);
    }

    /**
     * 返回页面的HTML代码
     * 与Smarty Fetch 一样
     * @param $tplFile 模板名称
     * @return unknown_type
     */
    public function asHtml($tplFile)
    {
        $this->bootSamrty();
        return $this->fetch($tplFile);
    }

    /**
     * 启动Smarty引擎
     * @access private
     * @return unknown_type
     */
    private function bootSamrty()
    {
        //加载Smarty配置
        foreach ($this->smartyConfig as $key => $value) {
            $this->$key = $value;
        }
        //设置内容编码
        $this->assign("_contentCharset", $this->contentCharset);
        //设置Css服务器
        $this->assign("_contentserver", Flea::getAppInf("ContentServer"));
        //设置Css服务器
        $this->assign("_cssserver", $this->cssServer);
        //设置Js服务器
        $this->assign("_jsserver", $this->jsServer);
        //设置图标服务器
        $this->assign("_iconserver", $this->iconServer);
        //设置Flash服务器
        $this->assign("_flashserver", $this->flashServer);
        //设置网页标题
        $this->assign("_pageTitle", $this->pageTitle);
        //设置BaseURL
        $this->assign("_baseurl", $this->baseUrl);

        $__loadJsFiles = array();
        $__loadCssFiles = array();

        foreach ($this->loadJsFiles as $jsFile) {
            if (eregi('^(http|https):\/\/(.)*$', $jsFile)) {
                array_push($__loadJsFiles, $jsFile);
            } else {
                array_push($__loadJsFiles, $this->jsServer . $jsFile);
            }
        }

        foreach ($this->loadCssFiles as $cssFile) {
            if (eregi('^(http|https):\/\/(.)*$', $cssFile)) {
                array_push($__loadCssFiles, $cssFile);
            } else {
                array_push($__loadCssFiles, $this->cssServer . $cssFile);
            }
        }

        //设置要加载的Css文件
        $this->assign("_loadcssfiles", $__loadCssFiles);
        //设置要加载的Js文件
        $this->assign("_loadjsfiles", $__loadJsFiles);
        //设置需要加载的js变量
        $this->assign("_shareVars", $this->shareVars);
    }

    /**
     * 打印Smarty当前使用的配置
     * 用于调试时使用
     *
     */
    public function printConfig()
    {
        $config['useTheme'] = $this->useTheme;
        $config['themeConfig'] = $this->themeConfig;
        $config['contentCharset'] = $this->contentCharset;
        $config['smartyConfig'] = $this->smartyConfig;
        $config['baseUrl'] = $this->baseUrl;
        $config['cssServer'] = $this->cssServer;
        $config['jsServer'] = $this->jsServer;
        $config['iconServer'] = $this->iconServer;
        $config['flashServer'] = $this->flashServer;
        $config['loadJsFiles'] = $this->loadJsFiles;
        $config['loadCssFiles'] = $this->loadCssFiles;
        require(dirname(__FILE__) . "/Smarty_Config_Show.php");
    }

    /**
     * 加载主题
     * @param $theme 主题名称
     * @return unknown_type
     */
    public function loadTheme($theme = null)
    {
        if (!is_null($theme)) {
            $this->useTheme = $theme;
        }
        $themeConfig = Flea::getAppInf($this->themeConfigPrefix . $this->useTheme);
        if (empty($themeConfig)) return false;
        $this->themeConfig = $themeConfig;
        $this->baseUrl = BOWL_BASE_URL;
        $this->smartyConfig = $this->themeConfig["SmartyConfig"];
        $this->cssServer = $this->themeConfig["CssServer"];
        $this->jsServer = $this->themeConfig["JsServer"];
        $this->flashServer = $this->themeConfig['FlashServer'];
        $this->iconServer = $this->themeConfig["IconServer"];
        $this->loadJsFiles = $this->themeConfig["LoadJsFiles"];
        $this->loadCssFiles = $this->themeConfig["LoadCssFiles"];
        $this->contentCharset = isset($this->themeConfig['ContentCharset']) ? $this->themeConfig['ContentCharset'] : $this->contentCharset;
    }
}

?>