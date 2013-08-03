<?php
/*
 * ----------------------------------------------------
 *                     BowlFramework
 *
 * 
 *
 *  @author zhaotiejia@ebupt.com
 *  @package BowlFramework
 *  @version 
 *  @create  下午5:15
 * ----------------------------------------------------
 */
require_once dirname(__FILE__).DS."Abstract.php";

class Bowl_Db_Result_PdoInformix extends Bowl_Db_Result_Abstract{


    protected $fetchStyleAlias   = array(
        "BOWL_DB_FETCH_STYLE_ASSOC"   => PDO::FETCH_ASSOC,
        "BOWL_DB_FETCH_STYLE_BOTH"    => PDO::FETCH_BOTH,
        "BOWL_DB_FETCH_STYLE_NUM"     => PDO::FETCH_NUM,
        "BOWL_DB_TETCH_STYLE_OBJ"     => PDO::FETCH_OBJ,
        "BOWL_DB_TETCH_STYLE_LOB"     => PDO::PARAM_LOB,
    );

    public function free(){

    }

    protected  function parseStmt($stmt){
        if(!$stmt instanceof PDOStatement ){
            throw new Exception("Wrong!");
        }
        return $stmt;
    }

    protected  function parseFetchStyle($fetchStyle = null){
        $fetchStyle = is_null($fetchStyle) ? "BOWL_DB_FETCH_STYLE_ASSOC" : $fetchStyle;
        return isset($this->fetchStyleAlias[$fetchStyle])?$this->fetchStyleAlias[$fetchStyle]:PDO::FETCH_ASSOC;
    }

    /**
     * 处理查询结果
     *
     * @return void
     */
    private function parseResults($results){
        if(empty($results)) return $results;
        $this->stmt->closeCursor();
        if(is_array($results)){
            $hasBlob = false;
            $blobKeyArray = array();
            //二维数组
            if(isset($results[0])){
                $resultsCount = count($results);
                //检测是否有Blob类型数据
                if($resultsCount > 0){
                    $result = $results[0];
                    foreach($result as $key=>$value){
                        if(is_resource($value)){
                            $blobKeyArray[] = $key;
                            $hasBlob = true;
                        }
                    }
                }
                if($hasBlob){
                    $this->stmt->execute();
                    for($i = 0;$i<$resultsCount;$i++){
                        foreach($blobKeyArray as $key){
                            $this->stmt->bindColumn($key, $lob, PDO::PARAM_LOB);
                            $this->stmt->fetch(PDO::FETCH_BOUND);
                            $stringBlob = stream_get_contents($lob);
                            $results[$i][$key] = $stringBlob;
                        }
                    }
                }
            }else{
                foreach($results as $key=>$value){
                    if(is_resource($value)){
                        $blobKeyArray[] = $key;
                        $hasBlob = true;
                    }
                }
                if($hasBlob){
                    $this->stmt->execute();
                    foreach($blobKeyArray as $key){
                        $this->stmt->bindColumn($key, $lob, PDO::PARAM_LOB);
                        $this->stmt->fetch(PDO::FETCH_BOUND);
                        $stringBlob = stream_get_contents($lob);
                        $results[$key] = $stringBlob;
                    }
                }
            }
            return $results;
        }else{
            return $results;
        }
    }

    public function fetchAll($fetchStyle = null){
        $fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$this->parseFetchStyle($fetchStyle);
        return $this->parseResults($this->stmt->fetchAll($fetchStyle));
    }

    public function fetchRow($fetchStyle = null){
        $fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$this->parseFetchStyle($fetchStyle);
        return $this->parseResults($this->stmt->fetch($fetchStyle));
    }

    public function fetchCol($colIndex = 0){
        $columnResults = array();
        $hasBlob = false;
        while($col = $this->stmt->fetchColumn($colIndex)){
            if(is_resource($col)){
                $hasBlob = true;
                break;
            }
            $columnResults[] = $col;
        }
        $this->stmt->closeCursor();
        if($hasBlob){
            $columnResults = array();
            $this->stmt->execute();
            $hasNext = true;
            $this->stmt->bindColumn($colIndex+1, $lob, PDO::PARAM_LOB);
            do{
                if($this->stmt->fetch(PDO::FETCH_BOUND) === false){
                    $hasNext = false;
                    break;
                }else{
                    $stringBlob = stream_get_contents($lob);
                    $columnResults[] = $stringBlob;
                }
            }while($hasNext);
        }
        return $columnResults;
    }

    public function fetchOne($colIndex = 0){
        $row = $this->stmt->fetch();
        if(isset($row[$colIndex])){
            $oneValue = $row[$colIndex];
            if(is_resource($oneValue)){
                $this->stmt->closeCursor();
                $this->stmt->execute();
                $this->stmt->bindColumn($colIndex+1, $lob, PDO::PARAM_LOB);
                $this->stmt->fetch(PDO::FETCH_BOUND);
                $stringBlob = stream_get_contents($lob);
                return $stringBlob;
            }else{
                return $oneValue;
            }
        }else{
            return null;
        }
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }

}