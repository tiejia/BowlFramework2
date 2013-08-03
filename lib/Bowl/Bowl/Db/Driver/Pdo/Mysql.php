<?php
/**
 * ----------------------------------------------------
 *                     BowlFramework
 *  @author zhaotiejia@ebupt.com
 *  @package BowlFramework
 *  @version 
 *  @create  下午6:27
 * ----------------------------------------------------
 */

require_once dirname(__FILE__) . DS . "Abstract.php";

class Bowl_Db_Driver_Pdo_Mysql extends Bowl_Db_Driver_Pdo_Abstract{

	protected  $dsnFormat = "mysql:dbname=%s;host=%s";
	protected  $limitQueryFormat = "%s LIMIT %s,%s";
    
    
    public function limitQuery($sql,$start = 0 ,$limit = 10){
        return sprintf($this->limitQueryFormat,$sql,$start,$limit);
    }

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

    protected function createSequence($sequenceName){

    }

    public function tableList(){

    }

    public function tableExists($tableName){

    }

    public function nextID($seqName){
        
    }

}