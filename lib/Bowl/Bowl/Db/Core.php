<?php
require_once dirname(__FILE__).DS."Driver.php";
/**
 * 数据库核心类
 * 提供针对数据库组件的通用方法
 *
 * @package Bowl_Db
 * @since 0.1
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 */
class Bowl_Db_Core{
    //insert
    const BOWL_DB_QUERY_TYPE_INSERT = 0;
    //update
    const BOWL_DB_QUERY_TYPE_UPDATE = 1;
    //delete
    const BOWL_DB_QUERY_TYPE_DELETE = 2;
    //select
    const BOWL_DB_QUERY_TYPE_SELECT = 3;

    protected $readDriver;
    protected $writeDriver;
    protected $dsn;
    protected $config;
    protected $debug = true;
    protected $fetchStyle = "BOWL_DB_FETCH_STYLE_ASSOC";

    protected $lastQuery = null;
    protected $lastBindValue = array();
    protected $lastResults = null;

    protected $dbCharset = "GBK";
    protected $clientCharset = "UTF8";

    protected $insertBindArray = array();
    protected $updateBindArray = array();

    public function __construct(array $dsn, array $config){

        log_message("class Bowl_Db_Core initing ....","systrack","BowlFramework::");
        if(!is_array($dsn)||!is_array($config)){
            throw new Bowl_Db_Exception("错误的DSN配置！");
        }
        
        if(isset($config['BOWL_DB_DEBUG'])){
            $this->debug =  is_bool($config['BOWL_DB_DEBUG'])?$config['BOWL_DB_DEBUG']:true;
        }

        $this->dsn = $dsn;
        $this->config = $config;
		$this->readDriver  =  Bowl_Db_Driver::factory($this->dsn['phptype'],$this->dsn,$this->config);
		$this->writeDriver =  Bowl_Db_Driver::factory($this->dsn['phptype'],$this->dsn,$this->config);

        if($dsn['phptype'] == "mysql"){
            $this->writeDriver->execute("SET NAMES 'utf8'");
            $this->readDriver->execute("SET NAMES 'utf8'");
            $this->dbCharset = isset($config['db_charset'])?$config['db_charset'] : "UTF8";
            $this->clientCharset = isset($config['client_charset'])?$config['client_charset'] : "UTF8";
        }else{
            $this->dbCharset = isset($config['db_charset'])?$config['db_charset'] : "UTF8";
            $this->clientCharset = isset($config['client_charset'])?$config['client_charset'] : "UTF8";
        }
        log_message("Class Bowl_Db_Core inited....","systrack","BowlFramework::");
    }

    /**
     * 关闭调试模式
     * 暂时无效
     */
    public function closeDebug(){
        $this->debug = false;
    }

    /**
     * 开启调试模式
     * 暂时无效
     */
    public function openDebug(){
        $this->debug = true;
    }

    /**
     * 设置数据库写入驱动对象
     *
     * @param $driver Bowl_Db_Driver 对象
     */
    public function setWriteDriver($driver){
        $this->writeDriver = $driver;
    }

    /**
     * 获取数据库组件写入驱动对象
     *
     * @return Bowl_Db_Driver 对象
     */
    public function getWriteDriver(){
        return $this->writeDriver;
    }

    /**
     * 设置数据库读取驱动
     *
     * @param $driver Bowl_Db_Driver 对象
     */
    public function setReadDriver($driver){
        $this->readDriver = $driver;
    }

    /**
     * 获取数据库写入驱动
     *
     * @return Bowl_Db_Driver 对象
     */
    public function getReadDriver(){
        return $this->readDriver;
    }

    /**
     * 设置读取驱动配置
     *
     * @param $property
     * @param $value
     */
    public function setReadDriverOption($property,$value){
        $this->readDriver->setOption($property,$value);
    }

    /**
     * 获取数据库读取配置
     *
     * @param $property
     * @return mixed
     */
    public function getReadDriverOption($property){
        return $this->readDriver->getOption($property);
    }

    /**
     * 设置数据库写入驱动配置
     *
     * @param $property
     * @param $value
     */
    public function setWriteDriverOption($property,$value){
        $this->writeDriver->setOption($property,$value);
    }

    /**
     * 获取数据库写入驱动配置
     *
     * @param $property
     * @return mixed
     */
    public function getWriteDriverOption($property){
        return $this->writeDriver->getOption($property);
    }

    /**
     * 设置数据检索方式
     *
     * @param $fetchStyle
     */
    public function setFetchStyle($fetchStyle){
        $this->fetchStyle = $fetchStyle;
    }

    /**
     * 获取数据检索方式
     *
     * @return string
     */
    public function getFetchStyle(){
        return $this->fetchStyle;
    }

    /**
     * 获取驱动最后执行的SQL语句
     *
     * @return null
     */
    public function getLastQuery(){
        return $this->lastQuery;
    }

    /**
     * 获取驱动最后执行的SQL绑定的数据
     *
     * @return array
     */
    public function getLastBindValues(){
        return $this->lastBindValue;
    }

    /**
     * 释放绑定数据
     */
    public function freeBindArray(){
        $this->insertBindArray = array();
        $this->updateBindArray = array();
    }

    protected function convertInput($input){
        if($this->dbCharset === $this->clientCharset){
            return $input;
        }
        if(is_array($input)){
            $inputConverted = array();
            foreach($input as $key=>$value){
                $inputConverted[$key] = iconv($this->clientCharset,$this->dbCharset,$value);
            }
            return $inputConverted;
        }else{
            return iconv($this->clientCharset,$this->dbCharset,$input);
        }
    }

    protected function convertOutput($output){
         if($this->dbCharset === $this->clientCharset){
            return $output;
        }
        if(is_array($output)){
            $outputConverted = array();
            foreach($output as $key=>$value){
                if(is_array($value)){
                    foreach($value as $subKey=>$subValue)
                    $outputConverted[$key][$subKey] = iconv($this->dbCharset,$this->clientCharset,$subValue);
                }else{
                    $outputConverted[$key] = iconv($this->dbCharset,$this->clientCharset,$value);
                }
            }
            return $outputConverted;
        }else{
            return iconv($this->dbCharset,$this->clientCharset,$output);
        }
    }

    /**
     * 获取最后插入的主键值
     *
     * @return mixed
     */
    public function lastInsertId(){
        return $this->writeDriver->lastInsertId();
    }

    /**
     * 获取数据库中数据表列表
     * 目前只支持informix
     * @return mixed
     */
    public function tableList(){
        return $this->readDriver->tableList();
    }

    /**
     * 检查数据库中是否存在指定的数据表
     * 目前只支持informix
     * @param $tableName
     * @return mixed
     */
    public function tableExists($tableName){
        return $this->readDriver->tableExists($tableName);
    }

    /**
     * 根据数据库类型对数据进行转义
     *
     * @param $value
     * @param string $which
     * @return mixed
     */
    public function quote($value,$which = 'r'){

        if($which == 'w'){
             if(is_string($value)) $value = $this->readDriver->quote($value);
        }
        if($which == 'r'){
            if(is_string($value))  $value = $this->readDriver->quote($value);
        }
        return $value;
        
    }

    protected  function _selectSql($fields = null,$tables = null,$where = null ,$sort = null,$page = null){

        if(is_null($fields) or is_null($tables)){
            return false;
        }
        $sql = "SELECT {$fields} FROM {$tables}";
        if(!is_null($where)){
            $sql = "{$sql} WHERE {$where} ";
        }
        if(!is_null($sort)){
            $sql = "{$sql} ORDER BY {$sort['key']} {$sort['order']}";
        }
        if(!is_null($page)){
            $sql = $this->readDriver->limitQuery($sql,$page['start'],$page['limit']);
        }
        return $sql;
        
    }

    protected  function _updateSql($table,$updateArray = array(),$where = null,$prepareMode = true){

        if(!is_array($updateArray)){
            return false;
        }
        $set = "";

        foreach($updateArray as $key=>$value){
            if($prepareMode){
                 $set .= "{$key}=?,";
                 $this->updateBindArray[] = $value;
            }else{
                 $set .= "{$key}=".$this->quote($value,"w").",";
            }
        }

        $set = trim($set,",");
        $updateSql = "UPDATE {$table} SET {$set}";
        
        if(!is_null($where)){
            $updateSql .= " WHERE {$where} ";
        }
        return $updateSql;
    }

    protected  function _deleteSql($table,$where = null){

        if(!is_string($table)){
            return false;
        }
        $sql = "DELETE FROM {$table}";

        if(!is_null($where)){
            $sql = "{$sql} WHERE {$where}";
        }
        return $sql;

    }

    /**
     * 从Sequence获取自增ID
     * @param $sequeensName Sequence名称
     * @return void
     */
    public function nextID($sequeensName){
        return $this->writeDriver->nextID($sequeensName);
    }

    /**
     * 生成32位UUID
     * 请使用Bowl::uuid()生成，该方法将弃用
     * @return string
     */
    public function uid(){
        return md5(uniqid(rand(),true));
    }

    protected  function _insertSql($table,$insertArray = null,$prepareMode = true){
        if(!is_array($insertArray)){
            return false;
        }
        $propertys = "";
        $values = "";
        foreach($insertArray as $key=>$value){
            $propertys .= $key.",";
            if($prepareMode){
                 $values    .= "?,";
                 $this->insertBindArray[] = $value;
            }else{
                 $values    .= $this->quote($value,"w").",";
            }
        }
        $propertys = trim($propertys,",");
        $values = trim($values,",");
        return  "INSERT INTO {$table}({$propertys})VALUES($values)";
    }

    protected  function _executeQuery($sql,$inputArr = null,$fetchStyle = null,$autoFree = true){
    	try{
        $this->lastQuery = $sql;
        $this->lastBindValue = $inputArr;
        $fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$fetchStyle;

        log_message($this->lastQuery,"debug","BowlFramework::DB>>LastQuery");
        log_message($this->lastBindValue,"debug","BowlFramework::DB>>LastBindValue");

        if(!is_null($inputArr)){
            $inputArr = $this->convertInput($inputArr);
        }

        $sql = $this->convertInput($sql);

        if ( preg_match('/^(select)/i', $sql) ){
		     $results = $this->readDriver->query($sql,$inputArr,$fetchStyle);
		}else{
             $results = $this->writeDriver->execute($sql,$inputArr);
		}

        if($autoFree) $this->freeBindArray();
        return $results;
        }catch(PDOException $e){
            log_message($e->getMessage(),"error","DBExcepiton");
            if(BOWL_RUN_MODE == "debug"){
                die("操作数据库时发生错误：".$e->getMessage()."<br>详情请查看日志文件");
            }else{
                die("系统繁忙，请稍后重试！");
            }
        }
    }

    /**
     * 执行SQL语句
     *
     * @param $sql SQL语句
     * @param null $inputArr 绑定的数据，使用prepare模式时使用
     * @param null $fetchStyle 返回数据格式
     * @return array|bool|string 对于update、delete和insert 返回生效的行数，select返回数组
     *
     */
    public function execQuery($sql,$inputArr=null,$fetchStyle = null){
        $results = $this->_executeQuery($sql,$inputArr,$fetchStyle);

        if(!$results instanceof Bowl_Db_Result_Abstract){
            return false;
        }
        if ( preg_match('/^select/i', $sql) ){
             $results = $results->fetchAll();
             return $this->convertOutput($results);
        }else{
             return $results->rowCount();
        }
    }
}