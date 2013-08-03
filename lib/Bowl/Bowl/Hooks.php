<?php
/**
 * BowlFramework 钩子类
 * 通过钩子来提供对程序的动态调整
 * 钩子目前还在测试中，不建议使用
 * @package Bowl
 * @author zhaotiejia@ebupt.com
 * @version 2.1
 *
 */
class Bowl_Hooks{

    private $hookParams = array();
    private $hookName;
    private $ignoreException = false;

    public function __construct(){

    }

    /**
     * 设置是否忽略异常
     * @param $ignoreException
     * @return Bowl_Hooks
     */
    public function setIgnoreException($ignoreException){
        $this->ignoreException = $ignoreException;
        return $this;
    }

    /**
     * 设置钩子
     *
     * @param $hookName
     */
    public function setHook($hookName){
        $this->hookName = $hookName;
    }

    /**
     * 调用钩子
     *
     * @param array $params
     * @param null $hookParams
     * @return bool
     */
    public function callHook($params = array(),$hookParams = null){

        if(empty($hookParams)){
            if(empty($this->hookName)){
                log_message("Hookname is empty","error","Bowl_Hooks");
                if($this->ignoreException) return true;
                return false;
            }
            $this->hookParams = Flea::getAppInf("Hook_{$this->hookName}");

            if(empty($this->hookParams)){
                log_message("Hook params is empty","error","Bowl_Hooks");
                if($this->ignoreException) return true;
                return false;
            }
        }else{
            $this->hookParams = $hookParams;
        }
        //多重钩子
        if(isset($this->hookParams[0])&&is_array($this->hookParams[0])){
             $returnVal = true;
             foreach($this->hookParams as $hookParams){
                 $returnVal = $this->_run($hookParams,$params);
             }
             return $returnVal;
        }else{
            return $this->_run($this->hookParams,$params);
        }
    }

    private function _run($hookParams,$params){
        $hookType = isset($hookParams['type'])?$hookParams['type']:"Bowl";
        $hookPath = isset($hookParams['file'])?$hookParams['file']:false;
        $hookClass = isset($hookParams['class'])?$hookParams['class']:false;
        $hookFunction = isset($hookParams['function'])?$hookParams['function']:false;

        if(false === $hookClass AND false === $hookFunction){
            log_message("Hook function [{$hookFunction}] and class [{$hookClass}] not defined!","error","Bowl_Hook");
            if($this->ignoreException) return true;
            return false;
        }

        switch($hookType){
            case "Bowl":
                //过程式钩子
                if(false === $hookClass){
                    if(!function_exists($hookFunction)){
                        log_message("Hook function [{$hookFunction}] can't be call!","error","Bowl_Hook");
                        if($this->ignoreException) return true;
                        return false;
                    }else{
                        return $hookFunction($params);
                    }
                }else{
                    Flea::loadClass($hookClass);
                    $hookObj = new $hookClass;
                    if(!method_exists($hookObj,$hookFunction)){
                        log_message("Hook method [{$hookFunction}] in class[{$hookClass}] can't be call!","error","Bowl_Hook");
                        if($this->ignoreException) return true;
                        return false;
                    }else{
                        return $hookObj->$hookFunction($params);
                    }
                }
                break;
            case "PHP" :
                if(false === $hookPath){
                    log_message("Hook filepath undefined","error","Bowl_Hook");
                    if($this->ignoreException) return true;
                    return false;
                }

                if(file_exists($hookPath)){
                    log_message("Hook file [{$hookPath}] isn't exists！","error","Bowl_Hook");
                    if($this->ignoreException) return true;
                    return false;
                }
                require_once($hookPath);
                //面向对象钩子
                if(false === $hookClass){
                    if(!function_exists($hookFunction)){
                        log_message("Hook function [{$hookFunction}] can't be call!","error","Bowl_Hook");
                        if($this->ignoreException) return true;
                        return false;
                    }else{
                        return $hookFunction($params);
                    }
                }else{
                    $hookObj = new $hookClass;
                    if(!method_exists($hookObj,$hookClass)){
                        log_message("Hook method [{$hookFunction}] in class[{$hookClass}] can't be call!","error","Bowl_Hook");
                        if($this->ignoreException) return true;
                        return false;
                    }else{
                        return $hookObj->$hookFunction($params);
                    }
                }
                break;
        }
    }
}