<?php
/**
 * 抓取网址类
 * 基于CURL发起GET、POST请求
 *
 * @package Bowl_Helper
 * @version 2.1
 * @author zhaotiejia
 *
 */
class Bowl_Helper_FetchUrl{

    private $curl;

    private $url;

    private $curlOpt = array();

    private $cookie = array();

    /**
     * 构造函数
     *
     * @param $url 要抓取的网址
     */
    public function __construct($url){
        $this->url = $url;
        $this->curl = curl_init();
    }

    /**
     * 获取当前抓取的网页
     *
     * @return mixed
     */
    public function getUrl(){
    	return $this->url;
    }

    /**
     * 设置要抓取的网页
     *
     * @param $url
     */
    public function setUrl($url){
        $this->url = $url;
    }

    /**
     * 设置HTTP消息头
     * @param $httpHeader 数组形式的Http消息头
     * @param bool $overwrite 是否要覆盖已设置的消息头信息
     */
    public function setHttpHeader($httpHeader,$overwrite=false){
      	if(!is_array($httpHeader)){
      		return ;
      	}
      	foreach($httpHeader as $key=>$v){
      		if(isset($this->curlOpt[$key])){
      			if(false !== $overwrite) $this->curlOpt[$key];
      		}else{
      			$this->curlOpt[$key] = $v;
      		}
      	}
    }

    /**
     * 获取Http消息头
     * @return array
     */
    public function getHttpHeader(){
    	return $this->httpHeader;
    }


    /**
     * 获取当前请求的HTTP状态码
     * @return mixed
     */
    public function getHttpCode(){
    	return curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
    }


    /**
     * 设置HTTP COOKIE
     *
     * @param $cookie 数组形式的Cookie
     * @param bool $overwrite 是否覆盖已设置的值
     */
    public function setCookie($cookie,$overwrite=false){
        if(false === $overwrite){
            $this->cookie = array_merge($this->cookie,$cookie);
        }else{
            $this->cookie = $cookie;
        }
    }

    /**
     * 获取Cookie
     *
     * @return array
     */
    public function getCookie(){
        return $this->cookie;
    }

    /**
     * 发送POST请求
     *
     * @param array $postData 数组形式的POST数据
     * @return mixed
     */
    public function sendPostRequest($postData = array()){
    	$cookie = $this->generateCookie();
    	$this->curlOpt[CURLOPT_URL] = $this->url;
    	$this->curlOpt[CURLOPT_POST] = true;
    	if(!empty($cookie)){
    		$this->curlOpt[CURLOPT_COOKIE] = $cookie;
    	}
    	$this->curlOpt[CURLOPT_POSTFIELDS] = $postData;
    	$this->curlOpt[CURLOPT_RETURNTRANSFER] = true;
        curl_setopt_array($this->curl,$this->curlOpt);
        return curl_exec($this->curl);
    }

    private function generateCookie(){
    	$cookie = "";
    	if(!empty($this->cookie)){
    		foreach($this->cookie as $key=>$v){
    			$cookie.=$key."=".$v.";";
    		}
    	}
    	return rtrim($cookie,";");
    }

    /**
     * 发送GET请求
     *
     * @param array $getData 数组形式的GET数据
     * @return mixed
     */
    public function sendGetRequest($getData = array()){
        $urlParam = "";
        foreach($getData as $key => $get){
            $urlParam .= $key."=".urlencode($get)."&";
        }

        if(!empty($urlParam)){
            $this->url = $this->url."?".rtrim($urlParam,"&");
        }

        $cookie = $this->generateCookie();

        $this->curlOpt[CURLOPT_URL] = $this->url;
        $this->curlOpt[CURLOPT_COOKIE] = $cookie;
        $this->curlOpt[CURLOPT_HTTPGET] = true;
        $this->curlOpt[CURLOPT_RETURNTRANSFER] = true;

        curl_setopt_array($this->curl,$this->curlOpt);
        return curl_exec($this->curl);
    }
}