<?php
/**
 * BowlFramework模型抽象基类
 * 所有模型均需继承该类
 *
 * @package Bowl_Model
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Model_Abstract
{


    private $table = array();

    private $activeDb = array();

    private $tableDb = null;

    private $service = array();

    private $extension = array();

    public function __construct()
    {

    }

    /**
     * 获取ActiveDb对象
     * 与Controller中的一样
     * @param string $name
     * @param array $params
     * @return mixed
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
     * 获取Table对象
     * 取得Table层中的对应单例
     *
     * @param $tableName 类名，Table_后的部分，会对首字母大写
     * @return mixed
     */
    public function table($tableName)
    {
        $tableFullName = "Table_" . ucfirst($tableName);
        if (!isset($this->table[$tableFullName])) {
            $this->table[$tableFullName] = Flea::getSingleton($tableFullName);
        }
        return $this->table[$tableFullName];
    }

    /**
     * 获取TableDb对象
     * 与Controller中的TableDb方法一样
     * @param string $tableName
     * @param array $params
     * @return null
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
        $serviceFullName = "Service_" . ucfirst($serviceName);
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
