<?php

require_once BOWL_BASE_DIR."lib/3rd/nusoap/lib/nusoap.php";

/**
 * Soap客户端
 *
 * 提供对Soap协议的支持，基于魔术方法提供便捷的方法操作
 * 支持调试
 *
 * 示例：
 * <code>
 *   <?php
 *       try{
 *            //创建Soap客户端
 *            $soapClient = new Bowl_Http_SoapClient("http://211.141.83.35:8080/eaa/services/IfSSO?wsdl");
 *            //调用Soap方法authen
 *            $res = $soapClient->authen($xml);
 *            //获取Debug数据，会返回整个请求过程的数据，用于调试
 *            $traceStr = $soapClient->getTraceAsString();
 *            dump($traceStr);
 *        }catch(Http_SoapClient_Exception $e){
 *            //捕获SoapClient异常
 *            echo "Error".$e->getMessage();
 *        }
 * </code>
 *
 * @version 2.1
 * @since 2.1
 * @package Bowl_Http
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Http_SoapClient{

    /**
     * SoapServer URL
     * @access private
     * @var string
     */
    private $url;

    /**
     * Soap客户端对象
     *
     * @var null|\soapclient
     */
    private $soapClient = null;

    /**
     * 构造函数
     *
     * @param $url
     */
    function __construct($url){
        $this->url = $url;
        $this->soapClient = new soapclient($this->url);
        $error = $this->soapClient->getError();
        if($error){
           throw new Http_SoapClient_Exception("初始化Soap客户端时发生错误:".$error);
        }
    }

    /**
     * 获取字符串形式的调试数据
     *
     * @return mixed
     */
    public function getTraceAsString(){
        return $this->soapClient->getDebug();
    }

    /**
     * 获取XML格式的调试数据
     *
     * @return mixed
     */
    public function getTraceAsXml(){
        return $this->soapClient->getDebugAsXMLComment();
    }

    /**
     * 获取nusoap对象
     *
     * @return null|soapclient
     */
    public function getSoapClient(){
        return $this->soapClient;
    }

    /**
     * 魔术方法，不要直接调用
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Http_SoapClient_Exception
     */
    public function __call($name,$arguments){
        $res = $this->soapClient->call($name,$arguments);
        $error = $this->soapClient->getError();
        if($error){
            throw new Http_SoapClient_Exception("调用[$name]时发生错误:".$error);
        }
        return $res;
    }
}

class Http_SoapClient_Exception extends FLEA_Exception{

}