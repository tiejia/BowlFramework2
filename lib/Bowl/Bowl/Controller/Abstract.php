<?php
/**
 * BowlFramework 控制器抽象基类
 * 所有的控制器都必须继承该类
 * @package Bowl_Controller
 * @version 2.1
 * @since 0.1
 * @author zhaotiejia@ebupt.com
 *
 */
abstract class Bowl_Controller_Abstract extends FLEA_Controller_Action
{

    /**
     * ActicveDb对象数组
     * @access protected
     * @var array
     */
    protected $activeDb = array();

    /**
     * 钩子对象
     * 一个控制器对象中只会存在一个钩子对象
     * @access protected
     * @var null
     */
    protected $hook = null;

    /**
     * TableDb对象
     * TableDb在控制器中以单例模式存在
     * @access protected
     * @var null
     */
    protected $tableDb = null;

    /**
     * 模型对象数组
     * 每个模型对象以单例模式存在
     * @var array
     */
    protected $model = array();

    /**
     * Service对象
     * 不推荐使用，可能在后续版本中停止使用
     * @access protected
     * @var array
     */
    protected $service = array();

    /**
     * 客户端数据对象
     * Bowl_Http_Request 对象
     * @access public
     * @var \Bowl_Http_Request|null
     */
    public $input = null;

    /**
     * 视图对象
     * Bowl_View_Smarty 对象
     * @access view
     * @var \Bowl_View_Smarty|null
     */
    public $view = null;

    /**
     * 响应对象
     * Bowl_Http_Response 对象
     * @access response
     * @var \Bowl_Http_Response|null
     */
    public $response = null;

    /**
     * 构造函数
     * 构建 input、response和view对象
     */
    public function __construct()
    {
        $this->input = new Bowl_Http_Request();
        $this->response = new Bowl_Http_Response;
        $this->view = new Bowl_View_Smarty();
    }

    /**
     * 获取一个模型对象
     *
     * @param $modelName 参数为Model_后的部分，框架会做首字母大写处理
     * @return mixed
     */
    public function model($modelName)
    {
        $modelFullName = "Model_" . ucfirst($modelName);
        if (!isset($this->model[$modelFullName])) {
            $this->model[$modelFullName] = Flea::getSingleton($modelFullName);
        }
        return $this->model[$modelFullName];
    }

    /**
     * 获取钩子对象
     *
     * @param string $hookName 钩子名称
     * @return null
     */
    public function hook($hookName = "BowlHook")
    {
        if (empty($this->hook)) {
            Flea::loadClass("Bowl_Hooks");
            $this->hook = new Bowl_Hooks();
            $this->hook->setHook($hookName);
        } else {
            $this->hook->setHook($hookName);
        }
        return $this->hook;
    }

    /**
     * 获取ActiveDb对象
     *
     * ActiveDb会记录SQL状态，如果同时要处理两个SQL，必须设置ActiveDb的名称
     *
     * @param string $name ActiveDb的名称
     * @param array $params 数据库连接参数，默认会从DbConfig中获取。格式为array("dsn"=>xx,"config"=>xxx)
     * @return Bowl_Db_Active 对象
     */
    public function activeDb($name = "default", $params = array())
    {

        if (!isset($this->activeDb[$name])) {
            if (!empty($params)) {
                $dsn = $params['dsn'];
                $options = $params['config'];
            } else {
                $dsn = Flea::getAppInf("Bowl_Db_Dsn");
                $options = Flea::getAppInf("Bowl_Db_Options");
            }
            Flea::loadClass("Bowl_Db_Active");
            $this->activeDb[$name] = new Bowl_Db_Active($dsn, $options);
        }
        return $this->activeDb[$name];

    }

    /**
     * 获取TableDb对象
     * TableDb以单例模式存在
     * @param string $tableName 数据表名
     * @param array $params 默认会从DbConfig中获取
     * @return Bowl_Db_Table 对象
     */
    public function tableDb($tableName = "default", $params = array())
    {
        if (is_null($this->tableDb)) {
            if (!empty($params)) {
                $dsn = $params['dsn'];
                $options = $params['config'];
            } else {
                $dsn = Flea::getAppInf("Bowl_Db_Dsn");
                $options = Flea::getAppInf("Bowl_Db_Options");
            }
            Flea::loadClass("Bowl_Db_Table");
            $this->tableDb = new Bowl_Db_Table($dsn, $options);
        }
        $this->tableDb->setTableName($tableName);
        return $this->tableDb;
    }

    public function service($serviceName)
    {
        $serviceFullName = "Service_" . $serviceName;
        if (!isset($this->service[$serviceFullName])) {
            $this->service[$serviceFullName] = Flea::getSingleton($serviceFullName);
        }
        return $this->service[$serviceFullName];
    }

    public function extension($extensionName)
    {
        $extensionFullName = "Extension_" . ucfirst($extensionName);
        if (!isset($this->extension[$extensionFullName])) {
            $this->extension[$extensionFullName] = Flea::getSingleton($extensionFullName);
        }
        return $this->extension[$extensionFullName];
    }
}