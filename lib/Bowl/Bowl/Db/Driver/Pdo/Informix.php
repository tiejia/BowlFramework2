<?php
/**
 * Informix驱动类
 * User: zhaotiejia@ebupt.com
 * Date: 11-8-31
 * Time: 上午11:27
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__) . DS . "Abstract.php";

class Bowl_Db_Driver_Pdo_Informix extends Bowl_Db_Driver_Pdo_Abstract{

    protected  $dsnFormat =  "informix:host=%s;database=%s;server=%s;protocol=onsoctcp;";

    private $tableListSql = "SELECT tabname FROM systables WHERE tabid > 99 AND tabtype='T'";
    private $tableExistsSql = "SELECT COUNT(*) FROM systables WHERE tabid > 99 AND tabtype='T' AND tabname = ? ";
    private $sequenceCreateSql = "CREATE SEQUENCE %s INCREMENT BY %s START WITH %s MAXVALUE %s MINVALUE %s";
    private $nextValSql = "select %s.nextval from dual";

    protected function parseDSN($dsnArray = array()){
        if(empty($dsnArray)||!is_array($dsnArray)){
			return false;
		}
		if(!isset($dsnArray['hostspec'])) return false;
		if(!isset($dsnArray['database'])) return false;
		if(!isset($dsnArray['username'])) return false;
		if(!isset($dsnArray['password'])) return false;
        if(!isset($dsnArray['server'])) return false;
		foreach($dsnArray as $key=>$value){
			$this->{$key} = $value;
		}
		$this->dsn = sprintf($this->dsnFormat,$this->hostspec,$this->database,$this->server);
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
        $createSeqSql = sprintf($this->sequenceCreateSql,$seqName,1,1,999999,1);
        $results = $this->DBO->exec($createSeqSql);
        if(false === $results){
            return false;
        }
        $insertSql = "insert into dual (col1,col2)values({$seqName}.NEXTVAL,{$seqName}.CURRVAL)";
        $statement = $this->DBO->exec($insertSql);
        if(false === $statement){
            return false;
        }else{
            return true;
        }
    }


    public function nextID($seqName){
        return 0;
        /**
        $fullSeqName = strtolower($this->seqNamePrefix.$seqName);
        $nextValueSql = sprintf($this->nextValSql,$fullSeqName);
        try{
            $queryResults = $this->DBO->query($nextValueSql);
            if(false === $queryResults){
                return false;
            }
            $results = $queryResults->fetch(PDO::FETCH_BOTH);
            if(isset($results[0])){
                return $results[0];
            }else{
                return false;
            }
        }catch(Exception $e){
            //echo $e->getMessage();
            $this->createSequence($fullSeqName);
        }
        **/
    }

    public function tableList(){
        $statment = $this->DBO->query($this->tableListSql);
        if(false === $statment) return false;
        return $statment->fetchAll();
    }

    public function tableExists($tableName){
        $statment = $this->DBO->prepare($this->tableExistsSql);
        if(false === $statment) return false;
        $statment->execute(array($tableName));
        if(false === $statment) return false;
        $count = $statment->fetch(PDO::FETCH_NUM);
        if($count[0]>0){
            return true;
        }else{
            return false;
        }
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
        return new Bowl_Db_Result_PdoInformix($stmt,$fetchStyle);
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
            return new Bowl_Db_Result_PdoInformix($stmt);
        }else{
            return false;
        }
    }

    public function limitQuery($sql,$start = 0 ,$limit = 10){
        return preg_replace('/^select/i',"SELECT SKIP {$start} FIRST {$limit}",$sql);
    }

}