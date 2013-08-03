<?php
/**
 * Oracle驱动类
 * User: zhaotiejia@ebupt.com
 * Date: 11-8-31
 * Time: 上午11:27
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__) . DS . "Abstract.php";

class Bowl_Db_Driver_Pdo_Oracle extends Bowl_Db_Driver_Pdo_Abstract{

    protected  $dsnFormat =  "oci:dbname=%s;host=%s";

    private $tableListSql = "SELECT tabname FROM systables WHERE tabid > 99 AND tabtype='T'";
    private $limitSql =   "SELECT * FROM( SELECT A.*, ROWNUM RN FROM (%s) A WHERE ROWNUM <= %s ) WHERE RN >= %s";

    protected function parseDSN($dsnArray = array()){
        if(empty($dsnArray)||!is_array($dsnArray)){
			return false;
		}
		if(!isset($dsnArray['hostspec'])) return false;
		if(!isset($dsnArray['database'])) return false;
		if(!isset($dsnArray['username'])) return false;
		if(!isset($dsnArray['password'])) return false;
		foreach($dsnArray as $key=>$value){
			$this->{$key} = $value;
		}
		$this->dsn = sprintf($this->dsnFormat,$this->database,$this->hostspec);
		return true;
    }


    /**
     *
     * 创建Sequence
     *
     * @param $seqName
     * @return bool
     */
    protected  function createSequence($seqName){

    }


    public function nextID($seqName){

    }

    public function tableList(){

    }

    public function tableExists($tableName){

    }


    public function limitQuery($sql,$start = 0 ,$limit = 10){
        $end = $start+$limit;
        $start = $start+1;
        return sprintf($this->limitSql,$sql,$end,$start);
    }

}