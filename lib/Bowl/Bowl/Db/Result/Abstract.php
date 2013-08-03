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
 *  @create  下午5:09
 * ----------------------------------------------------
 */
 
abstract class Bowl_Db_Result_Abstract{

    protected $stmt;
    protected $fetchStyle;

    public function __construct($stmt,$fetchStyle = null){
        $this->stmt = $this->parseStmt($stmt);
        $this->fetchStyle = $this->parseFetchStyle($fetchStyle);
    }

    abstract protected function parseStmt($stmt);
    abstract protected function parseFetchStyle($fetchStyle = null);

    public function __destruct(){
        $this->free();
    }
    abstract function free();
    abstract function fetchAll();
    abstract function fetchRow();
    abstract function fetchOne();
    abstract function fetchCol();
}