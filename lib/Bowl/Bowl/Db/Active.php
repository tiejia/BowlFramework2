<?php
require_once dirname(__FILE__).DS."Core.php";

/**
 * 数据库ActiveDb操作模式类
 *
 * 提供ActiveDb模式的数据库方法
 *
 * @package Bowl_Db
 * @version 2.1
 * @since 0.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Db_Active extends Bowl_Db_Core{

	private $whereExp = array();
    private $whereBindArray = array();

    private $query = array();
    private $queryBindArray = array();

    private $sort	= array();
	private $limit	= array();
	private $tables	= array();
	private $fields	= array();


    /**
     * 添加WHERE表达式
     *
     * @param $exp  WHERE表达式
     * @param null $bindValue   绑定的值
     * @return Bowl_Db_Active   Bowl_Db_Active对象
     */
	public function where($exp,$bindValue=null){
		$whereExpArray = array("type"=>"where","exp"=>$exp,"bind"=>$bindValue);
	 	array_push($this->whereExp,$whereExpArray);
		return $this;
	}
    /**
     * 添加WHEREAND语句
     *
     * 使用WHEREAND将与前面的WHERE子句用AND连接
     * @param $exp 表达式
     * @param null $bindValue 绑定的数值
     * @return Bowl_Db_Active   Bowl_Db_Active对象
     */
    public function whereAnd($exp,$bindValue = null){
		$whereExpArray = array("type"=>"where","exp"=>$exp,"frontunion"=>"AND","bind"=>$bindValue);
        array_push($this->whereExp,$whereExpArray);
		return $this;
    }
    /**
     * 添加WHERE 子句
     * 将用OR与前面的子句连接
     * @param $exp
     * @param null $bindValue
     * @return Bowl_Db_Active
     */
    public function whereOr($exp,$bindValue = null){
		$whereExpArray = array("type"=>"where","exp"=>$exp,"frontunion"=>"OR","bind"=>$bindValue);
	 	array_push($this->whereExp,$whereExpArray);
		return $this;
    }
    /**
     *
     * 添加WhereIn子句
     * @param $propery 属性
     * @param array $values 数值
     * @param bool $not 如果设为true，则声称Where not in () 子句
     * @return bool|Bowl_Db_Active
     *
     */
    public function whereIn($propery,$values = array(),$not = false){
		if(!is_array($values)) return false;
		$whereExpArray = array("type"=>"where_in","not"=>$not,"property"=>$propery,"bind"=>$values);
		array_push($this->whereExp,$whereExpArray);
		return $this;
    }

    /**
     * Select 子句
     *
     * @param string $fields
     * @return Bowl_Db_Active
     */
    public function select($fields = "*"){
		if(is_array($fields)){
			array_merge($this->fields,$fields);
		}else{
			array_push($this->fields,$fields);
		}
		return $this;
    }
    /**
     *
     * 使用的数据表
     * @param null $tables
     * @return Bowl_Db_Active
     */
    public function tables($tables = null){
		if(is_array($tables)){
			array_merge($this->tables,$tables);
		}else{
			array_push($this->tables,$tables);
		}
		return $this;
    }
    /**
     * 添加分页条件
     *
     * @param $start 分页起始点
     * @param $limit 每页条数
     * @return Bowl_Db_Active
     */
    public function limit($start,$limit){
		$this->limit['start'] = $start;
		$this->limit['limit']	= $limit;
		return $this;
    }
    /**
     * 添加排序条件
     * @param $key 排序字段
     * @param string $order 排序方式
     * @return Bowl_Db_Active
     */
    public function sort($key,$order = "DESC"){
		$this->sort['key'] = $key;
		$this->sort['order'] = $order;
		return $this;
    }
    /**
     * 释放绑定数组
     *
     * @return void
     */
	public function freeBindArray(){

        parent::freeBindArray();

        $this->whereBindArray = array();
		$this->whereExp = array();

        $this->query = array();
        $this->queryBindArray = array();

		$this->tables = array();

        $this->limit	= array();
		$this->sort	= array();

        $this->fields	= array();
	}

    private function _where(){
        if(!is_array($this->whereExp) or empty($this->whereExp)){
            return "";
        }
        $_whereQuery = "";
        foreach($this->whereExp as $where){
            if($where['type'] == "where"){
                if(!empty($_whereQuery)&&isset($where['frontunion'])){
                    $_whereQuery .=" {$where['frontunion']} ";
                }
                $_whereQuery .= " {$where['exp']} ";
            }
            if($where['type'] == "where_in"){
                if(!empty($_whereQuery)&&isset($where['frontunion'])){
                    $_whereQuery .=" {$where['frontunion']} ";
                }

                $_whereQuery .= $where['property'];

                if($where['not']){
                     $_whereQuery .= " NOT IN(";
                }else{
                     $_whereQuery .= " IN(";
                }
                foreach($where['bind'] as $value){
                     $_whereQuery .=" ?,";
                }
                $_whereQuery = trim($_whereQuery,",");
                $_whereQuery .= ")";
            }

            if(!is_null($where['bind'])){
                    if(is_array($where['bind'])){
                        foreach($where['bind'] as $bind){
                            $this->whereBindArray[] = $bind;
                        }
                    }else{
                        $this->whereBindArray[] = $where['bind'];
                    }
            }
        }
        if(!empty($_whereQuery)) $_whereQuery = " $_whereQuery ";

        return  $_whereQuery;
    }

    private function _tables(){
        if(!is_array($this->tables) or empty($this->tables)){
            return false;
        }
        $tables = implode(",",$this->tables);
        return $tables;
    }

    private function _sort(){
        if(!is_array($this->sort) or empty($this->sort)){
            return "";
        }

        if(is_array($this->sort['key'])){
             $sortKeys = implode(",",$this->sort['key']);
        }else{
             $sortKeys = $this->sort['key'];
        }
        return " ORDER BY {$sortKeys} {$this->sort['order']} ";
    }

    private function _fields(){
        if(!is_array($this->fields) or empty($this->fields)){
            $this->fields = array("*");
        }
        $fileds = implode(",",$this->fields);
        return $fileds;
    }

    /**
     * 执行SQL语句
     * @param $query
     * @param null $bindValue
     * @return Bowl_Db_Active
     */
    public function query($query,$bindValue = null){
        $this->query['query'] = $query;
        $this->query['bind']  = $bindValue;
        return $this;
    }
    /**
     * 执行Insert
     * @param array $insertArray key/value形式的数组
     * @param null $table 要操作的数据表
     * @return bool
     */
    public function insert($insertArray = array(),$table = null){

        if(is_null($table)){
            $table = $this->_tables();
        }

        if(empty($table)) return false;
        $sql = $this->_insertSql($table,$insertArray);
        $results = $this->_executeQuery($sql,$this->insertBindArray);

        if(false === $results){
            return false;
        }else{
            return $results->rowCount();
        }

    }
    /**
     * 执行删除语句
     * @param null $table
     * @return bool
     */
    public function delete($table = null){

        if(is_null($table)){
            $table = $this->_tables();
        }

        if(empty($table)) return false;

        $where = $this->_where();

        $sql = $this->_deleteSql($table,$where);

        $results = $this->_executeQuery($sql,$this->whereBindArray);

        if(false === $results){
            return false;
        }else{
            return $results->rowCount();
        }

    }
    /**
     * 执行update语句
     *
     * @param $updateArray
     * @param null $table
     * @return bool
     */
    public function update($updateArray,$table = null){

        if(is_null($table)){
            $table = $this->_tables();
        }

        if(empty($table)) return false;

        $where = $this->_where();

        $sql = $this->_updateSql($table,$updateArray,$where);

        $inputArray = array_merge($this->updateBindArray,$this->whereBindArray);

        $results = $this->_executeQuery($sql,$inputArray);

        if(false === $results){
            return false;
        }else{
            return $results->rowCount();
        }

    }

	private function _activeSQL(){

        $_fields = $this->_fields();
        $_tables = $this->_tables();
        $_where  = $this->_where();
        $_sort    = $this->_sort();
        $_mainSql = "SELECT {$_fields} FROM {$_tables}";

        if(!empty($_where)){
            $_mainSql .= " WHERE {$_where} ";
        }

        $_mainSql .= " {$_sort} ";

		if(!empty($this->limit)){
			$_mainSql = $this->readDriver->limitQuery($_mainSql,$this->limit['start'],$this->limit['limit']);
		}
        
		return $_mainSql;
	}

    /**
     * 检索单行数据
     * 返回数据中的第一行，为一维数组形式
     * @param null $fetchStyle
     * @return array|bool|string
     */
    public function fetchRow($fetchStyle = null){
        $sql = $this->_activeSQL();
        $bowlResults = $this->_executeQuery($sql,$this->whereBindArray,$fetchStyle);
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchRow($fetchStyle);
             return $this->convertOutput($results);
        }else{
            return false;
        }
    }
    /**
     * 检索所有
     * 返回所有数据，二维数组形式
     * @param null $fetchStyle
     * @return array|bool|string
     */
    public function fetchAll($fetchStyle = null){
        $sql = $this->_activeSQL();
        $bowlResults = $this->_executeQuery($sql,$this->whereBindArray,$fetchStyle);
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchAll($fetchStyle);
             return $this->convertOutput($results);
        }else{
            return false;
        }
    }

    /**
     * 检索当个数据
     * 返回第一行第一列的单个数据
     * @param int $colIndex
     * @return array|bool|string
     */
    public function fetchOne($colIndex = 0){
        $sql = $this->_activeSQL();
        $bowlResults = $this->_executeQuery($sql,$this->whereBindArray);

        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchOne($colIndex);
             return $this->convertOutput($results);
        }else{
            return false;
        }
    }

    /**
     * 检索一列
     * 返回一列数据，一维数组形式
     * @param int $colIndex
     * @return array|bool|string
     */
    public function fetchCol($colIndex = 0){
        $sql = $this->_activeSQL();
        $bowlResults = $this->_executeQuery($sql,$this->whereBindArray);
        if($bowlResults instanceof Bowl_Db_Result_Abstract){
             $results = $bowlResults->fetchCol($colIndex);
             return $this->convertOutput($results);
        }else{
            return false;
        }
    }

    /**
     * 执行Query子句
     * 使用query方式时，最后要通过run来获取结果
     * @param array $bindArray
     * @return array|bool|string
     */
    public function run($bindArray = array()){

        if(empty($this->query)) return false;
        
        $this->queryBindArray = is_array($this->query['bind'])?$this->query['bind']:array();

        $_mainSql = $this->query['query'];
        $_where = $this->_where();

        if(!empty($_where)){
            $_mainSql .= " WHERE {$_where} ";
        }

        $_sort = $this->_sort();

        $_mainSql .= " {$_sort} ";

        if(!empty($this->limit)){
			$_mainSql = $this->readDriver->limitQuery($_mainSql,$this->limit['start'],$this->limit['limit']);
		}

        $inputArr = array_merge($this->queryBindArray,$this->whereBindArray,$bindArray);

        $results = $this->_executeQuery($_mainSql,$inputArr);

        if ( preg_match('/^select/i', $_mainSql) ){
             $results = $results->fetchAll();
             return $this->convertOutput($results);
        }else{
             return $results->rowCount();

        }

    }
}
