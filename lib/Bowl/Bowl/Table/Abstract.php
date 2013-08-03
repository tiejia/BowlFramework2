<?php
/**
 * BowlFramework Table基类
 * 扩展Bowl_Db_Table，提供Bowl_Db_Table的所有方法
 * @package Bowl_Table
 * @author zhaotiejia@ebupt.com
 * @version 2.1
 *
 */
class Bowl_Table_Abstract extends Bowl_Db_Table{

	protected  $tableName;
    protected  $colPrefix = "";
    protected  $dsn;
    protected  $options;
    protected  $activeDb = array();
    protected  $acDb ;

    function __construct($tableName = null){
        if(!is_null($tableName)) $this->tableName = $tableName;
        $this->dsn = Flea::getAppInf("Bowl_Db_Dsn");
    	$this->options = Flea::getAppInf("Bowl_Db_Options");
        parent::__construct($this->dsn,$this->options);
        $this->setTableName($this->tableName);
    }

    /**
     * 设置字段前缀
     * 用于魔术方法
     * @param $colPrefix
     */
    public function setColPrefix($colPrefix){
        $this->colPrefix = $colPrefix;
    }

    /**
     * 获取属性前缀
     *
     * @return string
     */
    public function getColPrefix(){
        return $this->colPrefix;
    }

    /**
     * getBy魔术方法
     *
     * @param $method
     * @param $params
     * @return Array|bool
     */
    function __call($method,$params){
        $methodType = substr($method,0,5);
        //updateBy
        if(preg_match("/^updateby/i",$method)){
            $updateArray    = isset($params[1])?$params[1]:null;
            $getByProperty = substr($method,8,strlen($method));
            $getByProperty = $this->colPrefix.$getByProperty;
            $getByProperty = strtolower($getByProperty);
            $whereSql = $this->where("{$getByProperty} = %s ",$params[0]);
            return $this->update($updateArray,$whereSql);
        }

        //deleteBy
        if(preg_match("/^deleteby/i",$method)){
            $getByProperty = substr($method,8,strlen($method));
            $getByProperty = $this->colPrefix.$getByProperty;
            $getByProperty = strtolower($getByProperty);
            $whereSql = $this->where("{$getByProperty} = %s ",$params[0]);
            return $this->delete($whereSql);
        }

        //getAll
        if(preg_match("/^getby/i",$method)){
            $fields  = isset($params[1])?$params[1]:"*";
            $sort    = isset($params[2])?$params[2]:null;
            $page    = isset($params[3])?$params[3]:null;

            $getByProperty = substr($method,5,strlen($method));
            $getByProperty = $this->colPrefix.$getByProperty;

            $getByProperty = strtolower($getByProperty);
            $whereSql = $this->where("{$getByProperty} = %s ",$params[0]);
            return $this->find($fields,$whereSql,$sort,$page);
        }
        //getRow
        if(preg_match("/^getrowby/i",$method)){
            $fields  = isset($params[1])?$params[1]:"*";
            $sort    = isset($params[2])?$params[2]:null;
            $page    = isset($params[3])?$params[3]:null;
            $getByProperty = substr($method,8,strlen($method));
            $getByProperty = $this->colPrefix.$getByProperty;

            $getByProperty = strtolower($getByProperty);
            $whereSql = $this->where("{$getByProperty} = %s ",$params[0]);
            return $this->findRow($fields,$whereSql,$sort,$page);
        }
        //getCol
        if(preg_match("/^getcolby/i",$method)){
            $fields  = isset($params[1])?$params[1]:"*";
            $colIndex = isset($params[2])?$params[2]:0;
            $sort    = isset($params[3])?$params[3]:null;
            $page    = isset($params[4])?$params[4]:null;
            $getByProperty = substr($method,8,strlen($method));
            $getByProperty = $this->colPrefix.$getByProperty;

            $getByProperty = strtolower($getByProperty);
            $whereSql = $this->where("{$getByProperty} = %s ",$params[0]);
            return $this->findCol($fields,$colIndex,$whereSql,$sort,$page);
        }
        
        //getOne
        if(preg_match("/^getoneby/i",$method)){
            $fields  = isset($params[1])?$params[1]:"*";
            $colIndex = isset($params[2])?$params[2]:0;
            $sort    = isset($params[3])?$params[3]:null;
            $page    = isset($params[4])?$params[4]:null;
            $getByProperty = substr($method,8,strlen($method));
            $getByProperty = $this->colPrefix.$getByProperty;

            $getByProperty = strtolower($getByProperty);
            $whereSql = $this->where("{$getByProperty} = %s ",$params[0]);
            return $this->findVar($fields,$colIndex,$whereSql,$sort,$page);
        }
    }

    /**
     * 获取ActiveDb对象
     * 与Controller中的ActiveDb对象一样
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function activeDb($name="default",$params = array()){
          if(!isset($this->activeDb[$name])){
              if(!empty($params)){
                  $dsn = $params['dsn'];
                  $options = $params['config'];
              }else{
                  $dsn = Flea::getAppInf("Bowl_Db_Dsn");
                  $options = Flea::getAppInf("Bowl_Db_Options");
              }
              Flea::loadClass("Bowl_Db_Active");
              $this->activeDb[$name] = new Bowl_Db_Active($dsn,$options);
          }
          return $this->activeDb[$name];
    }
}