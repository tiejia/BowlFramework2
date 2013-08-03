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

class Bowl_Db_Result_Pdo extends Bowl_Db_Result_Abstract{


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

    
    public function fetchAll($fetchStyle = null){
         $fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$this->parseFetchStyle($fetchStyle);
         return $this->stmt->fetchAll($fetchStyle);
    }
    
    public function fetchRow($fetchStyle = null){
        $fetchStyle = is_null($fetchStyle)?$this->fetchStyle:$this->parseFetchStyle($fetchStyle);
        return $this->stmt->fetch($fetchStyle);
    }

    public function fetchCol($colIndex = 0){
        return $this->stmt->fetchColumn($colIndex);
    }

    public function fetchOne($colIndex = 0){
        $row = $this->stmt->fetch(PDO::FETCH_BOTH);
        if(false === $row) return $row;
        if(isset($row[$colIndex])) return $row[$colIndex];
        return false;
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }

}