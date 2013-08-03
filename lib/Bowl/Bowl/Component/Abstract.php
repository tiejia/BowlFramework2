<?php
/*
 * ----------------------------------------------------
 *                     BowlFramework
 *
 *	BowlFramework控制器虚基类
 *
 *	扩展的控制器基类必须继承并实现该类
 *  @package BowlFramework
 *  @author	 zhaotiejia@ebupt.com
 *  @since   version 1.0
 * ----------------------------------------------------
 */
abstract class Bowl_Component_Abstract{

	  /**
	   * Bowl
	   * @access protected
	   * @var Bowl_Db_Active
	   */
      protected  $activeDb = array();

      protected  $hook    =  null;

      protected $tableDb  = null ;

      protected $model    = array();

      protected $service  = array();

      public $input = null;

      public $view = null;

      public $response = null;

      public function __construct(){

          $this->view = new Bowl_View_Smarty();
      }

      public function model($modelName){
          $modelFullName = "Model_".ucfirst($modelName);
          if(!isset($this->model[$modelFullName])){
              $this->model[$modelFullName] = Flea::getSingleton($modelFullName);
          }
          return $this->model[$modelFullName];
      }
      /**
       * 钩子
       *
       * @return void
       */
      public function hook($hookName = "BowlHook"){
          if(empty($this->hook)){
              Flea::loadClass("Bowl_Hooks");
              $this->hook = new Bowl_Hooks();
              $this->hook->setHook($hookName);
          }else{
              $this->hook->setHook($hookName);
          }
          return $this->hook;
      }

      public function activeDb($name="default",$params = array()){

          if(!isset($this->activeDb[$name])){
              if(!empty($params)){
                  $dsn = $params['dsn'];
                  $options = $params['config'];
              }else{
                  $dsn = Flea::getAppInf("Bowl_Db_Dsn");
                  $options = Flea::getAppInf("Bowl_Db_Options");
              }
              Flea::loadClass("Bowl_Db_Active");
              $this->activeDb[$name] = new Bowl_Db_Active($dsn,$options);
          }
          return $this->activeDb[$name];

      }

      public function tableDb($tableName = "default",$params = array()){
          if(is_null($this->tableDb)){
              if(!empty($params)){
                  $dsn = $params['dsn'];
                  $options = $params['config'];
              }else{
                  $dsn = Flea::getAppInf("Bowl_Db_Dsn");
                  $options = Flea::getAppInf("Bowl_Db_Options");
              }
              Flea::loadClass("Bowl_Db_Table");
              $this->tableDb = new Bowl_Db_Table($dsn,$options);
          }
          $this->tableDb->setTableName($tableName);
          return $this->tableDb;
      }

      public function service($serviceName){
          $serviceFullName = "Service_".$serviceName;
          if(!isset($this->service[$serviceFullName])){
              $this->service[$serviceFullName] = Flea::getSingleton($serviceFullName);
          }
          return $this->service[$serviceFullName];
      }

       public function extension($extensionName){
          $extensionFullName = "Extension_".ucfirst($extensionName);
          if(!isset($this->extension[$extensionFullName])){
              $this->extension[$extensionFullName] = Flea::getSingleton($extensionFullName);
          }
          return $this->extension[$extensionFullName];
      }
}