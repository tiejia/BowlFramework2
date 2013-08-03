<?php
/**
 * ----------------------------------------------------
 *                     BowlFramework
 *  @author zhaotiejia@ebupt.com
 *  @package BowlFramework
 *  @version 
 *  @create  下午4:57
 * ----------------------------------------------------
 */

require_once dirname(dirname(__FILE__)).DS."Abstract.php";
require_once dirname(dirname(dirname(__FILE__))).DS."Result".DS."Pdo.php";

abstract class Bowl_Db_Driver_Pdo_Abstract extends Bowl_Db_Driver_Abstract{

	protected  $DBO;
    protected  $dsn;
    protected  $seqNamePrefix = 'bowl_db_seq_';
	protected  $options;
    protected  $dsnFormat = "mysql:dbname=%s;host=%s";
    protected  $debug = true;

    protected  $optionsPropertyAlias = array(
        "BOWL_DB_ATTR_CASE" =>  PDO::ATTR_CASE,
        "BOWL_DB_ATTR_ERRMODE"  => PDO::ATTR_ERRMODE,
        "BOWL_DB_ATTR_PERSISTENT"  => PDO::ATTR_PERSISTENT
    );

    protected $optionsValueAlias    = array(
        "BOWL_DB_CASE_LOWER"    =>  PDO::CASE_LOWER,
        "BOWL_DB_CASE_UPPER"     =>  PDO::CASE_UPPER,
        "BOWL_DB_CASE_NATURAL"  =>  PDO::CASE_NATURAL,
        "BOWL_DB_ERRMODE_EXCEPTION" =>  PDO::ERRMODE_EXCEPTION,
        "BOWL_DB_ERRMODE_SILENT"    => PDO::ERRMODE_SILENT,
        "BOWL_DB_ERRMODE_WARNING"   => PDO::ERRMODE_WARNING
    );

	public function __construct($dsn = array(),$options = array()){

		log_message("Initing Bowl_Db_Driver_Pdo_Abstract Class","systrack","BowlFramework::");

        if(!$this->parseDSN($dsn)){
			die("DSN ERROR");
		}
        //Set debug mode
        if(isset($config['BOWL_DB_DEBUG'])){
            $this->debug =  is_bool($config['BOWL_DB_DEBUG'])?$config['BOWL_DB_DEBUG']:true;
        }

		$this->parseOptions($options);
		$this->DBO = new PDO($this->dsn,$this->username,$this->password,$this->options);
		log_message("Inited Bowl_Db_Driver_Pdo_Abstract Class","systrack","BowlFramework::");

    }

    public function getDBO(){
        return $this->DBO;
    }

    public function setDBO($dbo){
        if($dbo instanceof PDO) $this->DBO = $dbo;
    }

    public function connect($forceNew = false){
        if($this->isConnect()){
           if($forceNew) {
               $this->DBO = new PDO($this->dsn,$this->username,$this->password,$this->options);
           }
        }else{
             $this->DBO = new PDO($this->dsn,$this->username,$this->password,$this->options);
        }
        return $this->DBO;
    }


    public function close(){
        if($this->isConnect()){
            $this->DBO = null;
        }
    }

    public function __destruct(){
        $this->close();
    }

    public function isConnect(){
        if($this->DBO instanceof PDO){
            return true;
        }
        return false;
    }
    /**
     * 解析DSN数据
     * @param array $dsnArray
     * @return bool
     */
	abstract protected  function parseDSN($dsnArray = array());
    /**
     * 过滤SQL注入
     *
     * @param $value
     * @return string
     */
    public function quote($value){
        return $this->DBO->quote($value);
    }

    public function lastInsertId(){
        return $this->DBO->lastInsertId();
    }

    /**
     * 解析配置选项
     * @param array $options
     * @return bool
     */
	private function parseOptions($options = array()){
		if(empty($options)||!is_array($options)){
			return false;
		}
		$parsedArray = array();
		foreach($options as $key=>$value){
			if(isset($this->optionsPropertyAlias[$key])){
				$parsedArray[$this->optionsPropertyAlias[$key]] = isset($this->optionsValueAlias[$value])?$this->optionsValueAlias[$value]:$value;
			}else{
				log_message("Unexpect dboption $key => $value","notice","Bowl_Db_Driver");
			}
		}
		$this->options = $parsedArray;
	}

    /**
     * 执行Select语句
     *
     * @param $sql  SQL语句
     * @param array|null $inputArr  与Sql绑定的数组
     * @param null $fetchStyle  返回结果方式
     * @return bool|Bowl_Db_Result_Pdo
     */
    public function query($sql,array $inputArr = null,$fetchStyle = null){
        $stmt = $this->DBO->prepare($sql);
        if(false === $stmt->execute($inputArr)){
            return false;
        }
        return new Bowl_Db_Result_Pdo($stmt,$fetchStyle);
    }

    /**
     * 执行Select以外的其它SQL语句
     *
     * @param $sql  Sql语句
     * @param array|null $inputArr Sql绑定的数组
     * @return bool|Bowl_Db_Result_Pdo
     */
    public function execute($sql,array $inputArr = null){
        $stmt = $this->DBO->prepare($sql);
        if(true === $stmt->execute($inputArr)){
            return new Bowl_Db_Result_Pdo($stmt);
        }else{
            return false;
        }
    }

    /**
     * 执行非预处理模式的SQL
     */
    public function execSql($sql){
        log_message($sql,"systrack","LastExcuteSQL");
        return $this->DBO->exec($sql);
    }

    /**
     * 获取所有表
     * @abstract
     * @return Array
     */
    abstract public function tableList();
    
    /**
     * 检测数据表是否存在
     * @abstract
     * @param $tableName
     * @return boolean
     */
    abstract public function tableExists($tableName);
    /**
     * 创建Sequence
     * @abstract
     * @param $sequenceName
     * @return boolean
     */
    abstract protected function createSequence($sequenceName);
    /**
     * 获取下一个自增ID值
     * @abstract
     * @param $sequeenceName 使用的Sequence名称
     * @return int
     */
    abstract public function nextID($sequeenceName);
    /**
     * 获取分页语句
     *
     * @abstract
     * @param $sql 原始SQL语句
     * @param int $start 起始点
     * @param int $limit 每页数目
     * @return string   完整的SQL语句
     */
    abstract function limitQuery($sql,$start = 0,$limit= 0);
}
