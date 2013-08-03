<?php
/**
 * Bowl 框架主文件
 *
 * 提供框架通用方法
 *
 * @package Bowl
 * @since 2.0
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 */
class Bowl{


    private static  $version = "2.1.2";

    private static $instance = null;

    private $appRunMode = "debug";

    private $allowAppRunMode = array("debug","deploy","test");

    private $loadAppName = "App";

    private $loadAppConfigFiles = array();

    private $runMode2ConfigDir = array(
        "debug" => "Debug",
        "deploy" => "Deploy",
        "test"  => "Test"
    );

    private $defaultConfigFiles = array(
        "AppConfig.php","ContentConfig.php",
        "DbConfig.php","ServiceConfig.php",
        "UserConfig.php","ViewerConfig.php"
    );

    private function __construc(){

    }


    public static function getVersion(){
        return self::$version;
    }

    /**
     * UUID静态方法
     *
     * 生成32位的UUID
     *
     * @static
     * @return string
     */
    public static function uuid(){
        return md5(uniqid(rand(),true));
    }

    /**
     * 获取框架实例
     *
     * @static
     * @param $runMode 运行模式
     * @return null
     */
    public static function getInstance($runMode){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance  = new $class();
            self::$instance->initialize($runMode);
        }
        return self::$instance;
    }

    /**
     * 初始化框架
     *
     * @param $runMode
     */
    private  function initialize($runMode){
        $this->setAppRunMode($runMode);
        //载入FleaPHP框架
        require(BOWL_BASE_DIR."/lib/FLEA/FLEA.php");
        //导入主程序目录
        FLEA::import(BOWL_BASE_DIR."/App/");
        //导入Bowl
        FLEA::import(BOWL_BASE_DIR."/lib/Bowl/");
    }

    /**
     * 设置程序运行模式
     *
     * @param $runMode
     * @throws Bowl_Exception
     */
    private function setAppRunMode($runMode){
        if(!in_array($runMode,$this->allowAppRunMode)){
            throw new Bowl_Exception("不合法的运行模式".$runMode."!");
        }
        switch(BOWL_RUN_MODE){
            case "debug" :
                error_reporting(E_ALL&~E_NOTICE&~E_DEPRECATED);
                break;
            case "deploy":
                error_reporting(0);
                break;
            case "test"  :
                error_reporting(E_ALL);
                break;
            default:
                error_reporting(E_ALL&~E_NOTICE&~E_DEPRECATED);
        }
    }

    /**
     * 加载配置文件
     *
     * @throws Bowl_Exception
     */
    private function loadConfigs(){
        //加载App目录内的配置文件
        $coreConfigFileDir = BOWL_BASE_DIR."/App/Config/".$this->runMode2ConfigDir[$this->appRunMode];
        if(!is_dir($coreConfigFileDir)){
            throw new Bowl_Exception("App配置目录".$coreConfigFileDir."不存在");
        }
        foreach($this->defaultConfigFiles as $config){
            $configFile = $coreConfigFileDir."/".$config;
            if(file_exists($configFile)){
                Flea::loadAppInf($configFile);
            }
        }
        //如果扩展应用则加载扩展应用内的配置文件
        if($this->loadAppName != "App"){
            $appConfigFileDir = BOWL_BASE_DIR.$this->loadAppName."/Config/".$this->runMode2ConfigDir[$this->appRunMode];
            if(!is_dir($appConfigFileDir)){
                return;
            }
            foreach($this->loadAppConfigFiles as $config){
                $configFile = $appConfigFileDir."/".$config;
                if(file_exists($configFile)){
                    Flea::loadAppInf($configFile);
                }
            }
        }
    }

    /**
     * 加载应用
     *
     * @param $appName
     * @param array $configs
     * @return Bowl
     */
    public function loadApp($appName="App",$configs = array()){
        $this->loadAppName = $appName;
        $this->loadAppConfigFiles = $configs;
        Flea::import(BOWL_BASE_DIR."/".$this->loadAppName);
        return $this;
    }

    public function run(){
        $this->loadConfigs();
        session_save_path(Flea::getAppInf("sessionDir"));
        session_start();
        Flea::runMvc();
    }
}

class Bowl_Exception extends Exception{}

function __autoload($class){
    Flea::loadClass($class);
}

