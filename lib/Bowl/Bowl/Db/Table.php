<?php
require_once dirname(__FILE__).DS."Core.php";

/**
 * 数据库组件Table操作类
 *
 * 提供Table模式的数据库操作方式
 *
 * @package Bowl_Db
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Db_Table extends Bowl_Db_Core{

    private $tables;

    public function __construct($dsn,$options,$tables = null){
        parent::__construct($dsn,$options);
        $this->tables = $tables;
    }

    /**
     * 设置要操作的数据表名
     * @access public
     * @param $tables
     */
    public function setTableName($tables){
        $this->tables = $tables;
    }

    /**
     * 获取当前操作的表名
     * @access public
     * @return null
     */
    public function getTableName(){
        return $this->tables;
    }
    /**
	 * 查询多条记录
	 * 执行Select SQL，将多个查询结果通过二维数组返回
     *
     * 示例：
     *
     * $fields 格式：array("*") "*" array("name","user_id") "name,user_id uid"用于构建Select 之后的部分<br>
     * $whereSql 格式： "a='1' AND b='2'" 可以通过 where()方法生成<br>
     * $sort  格式：array("key"=>"name","order"=>DESC||ASC) key 代表排序字段 order 代表排序方式
     * $page  分页: array("start"=>0,"limit"=>10) "start"分页起点 "limit"获取数据条数
     *
	 * @param String Array $fields
	 * @param String $whereSql
	 * @param Array $sort
	 * @param Array $page
	 * @access public
	 * @return Array
	 */
	public function find($fields="*",$whereSql=null,$sort=null,$page=null,$fetchStyle=null){
        $fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$fetchStyle;
        $sql = $this->_selectSql($fields,$this->tables,$whereSql,$sort,$page);
        $bowlResults = $this->_executeQuery($sql,null,$fetchStyle);
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchAll($fetchStyle);
             return $this->convertOutput($results);
        }else{
            return false;
        }
	}

	/**
	 * 查询单行数据
	 * 从查询结果中返回第一行数据，返回一维数组
     *
  	 * 示例：
     *
     * $fields 格式：array("*") "*" array("name","user_id") "name,user_id uid"用于构建Select 之后的部分<br>
     * $whereSql 格式： "a='1' AND b='2'" 可以通过 where()方法生成<br>
     * $sort  格式：array("key"=>"name","order"=>DESC||ASC) key 代表排序字段 order 代表排序方式
     * $page  分页: array("start"=>0,"limit"=>10) "start"分页起点 "limit"获取数据条数
	 *
     * @param String Array $fields
	 * @param String $whereSql
	 * @param Array $sort
	 * @param Array $page
	 * @access public
	 * @return Array
	 */
	public function findRow($fields="*",$whereSql=null,$sort=null,$page=null,$fetchStyle=null){
		$fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$fetchStyle;
		$sql = $this->_selectSql($fields,$this->tables,$whereSql,$sort,$page);
        $bowlResults = $this->_executeQuery($sql,null,$fetchStyle);
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchRow($fetchStyle);
             return $this->convertOutput($results);
        }else{
            return false;
        }
	}

	/**
	 * 查询单列数据
	 * 从查询结果中返回指定列数据，返回格式为一维数组
     *
  	 * 示例：
     *
     * $fields 格式：array("*") "*" array("name","user_id") "name,user_id uid"用于构建Select 之后的部分<br>
     * $colIndex 格式：数字或是列的属性名
     * $whereSql 格式： "a='1' AND b='2'" 可以通过 where()方法生成<br>
     * $sort  格式：array("key"=>"name","order"=>DESC||ASC) key 代表排序字段 order 代表排序方式
     * $page  分页: array("start"=>0,"limit"=>10) "start"分页起点 "limit"获取数据条数
     *
	 * @param String Array $fields
	 * @param String $colIndex
	 * @param String $whereSql
	 * @param Array $sort
	 * @param Array $page
	 * @access public
	 * @return Array
	 */
	public function findCol($fields="*",$colIndex=0,$whereSql=null,$sort=null,$page=null){

		$sql = $this->_selectSql($fields,$this->tables,$whereSql,$sort,$page);
        $bowlResults = $this->_executeQuery($sql);
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchCol($colIndex);
             return $this->convertOutput($results);
        }else{
            return false;
        }

	}

	/**
	 * 查询单个数据
	 * 从查询结果中返回指定列的单个数据，返回为单个数据
     *
     * 示例:
     *
  	 * $fields 格式：array("*") "*" array("name","user_id") "name,user_id uid"用于构建Select 之后的部分<br>
     * $colIndex 格式：数字或是列的属性名
     * $whereSql 格式： "a='1' AND b='2'" 可以通过 where()方法生成<br>
     * $sort  格式：array("key"=>"name","order"=>DESC||ASC) key 代表排序字段 order 代表排序方式
     * $page  分页: array("start"=>0,"limit"=>10) "start"分页起点 "limit"获取数据条数
     *
	 * @param String Array $fields
	 * @param String $colIndex
	 * @param String $whereSql
	 * @param Array $sort
	 * @param Array $page
	 * @access public
	 * @example
	 * @return Array
	 */
	public function findVar($fields,$colIndex=0,$whereSql=null,$sort=null,$page=null){
        $sql = $this->_selectSql($fields,$this->tables,$whereSql,$sort,$page);
        $bowlResults = $this->_executeQuery($sql);
        
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchOne($colIndex);
             return $this->convertOutput($results);
        }else{
            return false;
        }
	}

	/**
	 * 删除一条记录
	 * 从数据库删除一条记录
	 * 为防止误操作where语句不能为空
	 * @param String $whereSql
	 * @access public
	 * @return Boolean
	 */
	public function delete($where=null){
        $deleteSql = $this->_deleteSql($this->tables,$where);
        $results = $this->_executeQuery($deleteSql);

        if($results instanceof Bowl_Db_Result_Abstract){
            return $results->rowCount();
        }else{
            return false;
        }
	}


	/**
	 * 更新一条记录
	 * 从数据库更新一条记录
	 * 为防止误操作where语句不能为空
	 * @param String $whereSql
	 * @param Array $updateArray
	 * @return Boolean
	 */
	public function update($updateArray,$where = null){
        $updateSql = $this->_updateSql($this->tables,$updateArray,$where);
        $inputArray = $this->updateBindArray;
        $results = $this->_executeQuery($updateSql,$inputArray);

        if($results instanceof Bowl_Db_Result_Abstract){
            return $results->rowCount();
        }else{
            return false;
        }
	}

	/**
	 * 插入一条数据库记录
	 * @param Array $insertArray
     * @param Boolean $prepareMode 是否为预处理模式
	 * @return Boolean
	 */
	public function insert($insertArray,$prepareMode=true){
        //预处理模式
        if($prepareMode){
            $insertSql = $this->_insertSql($this->tables,$insertArray);
            $inputArray = $this->insertBindArray;
            $results = $this->_executeQuery($insertSql,$inputArray);
            if($results instanceof Bowl_Db_Result_Abstract){
               return $results->rowCount();
            }else{
               return false;
            }
        }else{
        //普通模式
            $insertSql = $this->_insertSql($this->tables,$insertArray,false);
            $insertSql = $this->convertInput($insertSql);
            return $this->writeDriver->execSql($insertSql);
        }
	}


	/**
	 * 生成Where语句
	 * 生成SQL中的Where语句部分，同时做SQL注入和转义处理
     *
     * 示例：
     *
     * where("name='%s' AND sex='%s'","bowl","male") ==== name='bowl' AND sex='male'
     *
	 * @param String $format
	 * @param $value
	 *
	 */
	public function where(){
		 $args = func_get_args();
	     $format = array_shift($args);
	     if(empty($args)) return false;
	     $escapedArray = array();

	     foreach($args as $arg){
            if(is_array($arg)){
                foreach($arg as $ar){
                    if(is_string($ar)){
                        $ar = $this->quote($ar);
                    }
                    array_push($escapedArray,$ar);
                }
            }else{
                if(is_string($arg)){
                    $arg = $this->quote($arg);
                }
                array_push($escapedArray,$arg);
            }
	     }
	     return vsprintf($format , $escapedArray);
	}
    /**
     * 生成WHERE IN
     *
     * @param $property
     * @param array $inArray
     * @return string
     */
    public function whereIn($property,array $inArray,$not = false){
        $inString = "";
        foreach($inArray as $value){
            if(is_string($value)) $value = $this->quote($value);
            $inString .= $value.',';
        }
        $inString = trim($inString,",");
        if($not){
             return " {$property} NOT IN({$inString})";
        }else{
             return " {$property} IN({$inString})";
        }
    }
}