<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 11-9-1
 * Time: 下午6:21
 * To change this template use File | Settings | File Templates.
 */

 class Bowl_Table_Simple extends Bowl_Table_Abstract{


     public function getAll($limit = null,$sort=null){
        return $this->find("*",null,$sort,$limit);
     }

     public function getAllCount(){
         return $this->findVar("COUNT(*)");
     }

 }
