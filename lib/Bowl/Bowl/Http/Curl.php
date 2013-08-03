<?php
/**
 * BowlFramework CURL
 *
 * 提供基于CURL的网页抓取，服务请求等
 * 提供便捷的GET、POST能力
 *
 * 示例：
 * <code>
 *    <?php
 *       //----------------------------------------------------
 *       // 简单示例
 *       // 使用HttpCurl时必须捕获Http_Curl_Exception异常
 *       // 在最简单情况下，只需创建对象，设置参数，发起请求
 *       //-----------------------------------------------------
 *       try{
 *
 *          //创建一个Curl对象
 *          $httpCurl = new Bowl_Http_Curl("http://www.163.com");
 *
 *          //设置请求来源，不是必须设置
 *          $httpCurl->setReferer("http://www.baidu.com");
 *
 *          //设置请求COOKIE
 *          $httpCurl->setCookie(array("PHPSESSID"=>"123456"));
 *
 *          //设置Http方法，如果不设置则默认为GET
 *          $httpCurl->setMethod("POST");
 *
 *          //上传文件,上传文件必须使用POST方法，注意在文件路径前加@符号
 *          $httpCurl->setParams("uploadfile"=>"@/tmp/1.txt");
 *
 *          //设置请求参数
 *          $httpCurl->setParams(array("name"=>"name"));
 *
 *          //发起请求，获取页面结果
 *          $response = $httpCurl->request();
 *
 *          //获取HTTP响应状态码，可以判断请求是否成功
 *          $httpCode = $httpCurl->getHttpCode();
 *
 *          //获取请求的详细信息，包括耗时，返回状态等。一般用于调试
 *          $requestInfo = $httpCurl->getRequestInfo();
 *
 *          echo $response;
 *      }catch(Http_Curl_Exception $e){
 *
 *          //如果请求发生错误，在这里捕获异常，并获取错误信息
 *          echo "HttpCurl error:".$e->getMessage();
 *
 *      }
 *
 *    ?>
 * </code>
 * @package Bowl_Http
 * @author zhaotiejia@ebupt.com
 * @since 2.1
 * @version 2.1
 *
 */
class Bowl_Http_Curl{

    /**
     * POST方法
     * @var string
     */
    const HTTP_METHOD_POST = "POST";
    /**
     * GET方法
     * @var string
     */
    const HTTP_METHOD_GET = "GET";
    /**
     * PUT方法
     * 注意：暂不支持
     * @var string
     */
    const HTTP_METHOD_PUT = "PUT";
    /**
     * DELETE方法
     * 注意：暂不支持
     */
    const HTTP_METHOD_DELETE = "DELETE";

    /**
     * 请求的链接地址
     * @access private
     * @var string
     */
    private $url = "";

    /**
     * CURL对象
     * @access private
     * @var #Fcurl_init|?
     */
    private $curl;

    /**
     * COOKIE
     * @access private
     * @var array
     */
    private $cookie = array();
    /**
     * HTTP方法
     * @access private
     * @var string
     */
    private $method = self::HTTP_METHOD_GET;
    /**
     * 请求参数
     * @access private
     * @var array
     */
    private $params = array();
    /**
     * HTTP请求头
     * @access private
     * @var array
     */
    private $header = array();
    /**
     * UserAgent
     * @access private
     * @var string
     */
    private $userAgent = "";

    /**
     * Referer 请求来源
     * @access private
     * @var string
     */
    private $referer = "";

    /**
     * 基于Http的BasicAuth
     * @access private
     * @var string
     */
    private $basicAuth = "";

    /**
     * 构造函数
     * @param null $url 要请求的URL
     */
    public function __construct($url = null){
        log_message("Http_Curl Class Initing ...","systrack","Bowl_Http_Curl");
        if(!extension_loaded("curl")){
            log_message(__FILE__.":".__LINE__.":Can not load curl extension","error","Bowl_Http_Curl");
            throw new Http_Curl_Exception("你没有安装curl扩展，无法使用Http_Curl!");
        }
        $this->setUrl($url);
        $this->curl = curl_init();
        log_message("Http_Curl Class Inited,url is :".$this->url,"systrack","Bowl_Http_Curl");
    }

    /**
     * 获取CURL对象
     *
     * @return resource
     */
    public function getCurl(){
        return $this->curl;
    }

    /**
     * 关闭CURL连接
     */
    public function close(){
        if(is_resource($this->curl))curl_close($this->curl);
    }

    /**
     * 析构函数
     */
    public function __destruct(){
        $this->close();
    }

    /**
     * 设置当前请求连接地址
     *
     * @param $url
     * @throws Http_Curl_Exception
     */
    public function setUrl($url){
        if(empty($url)) throw new Http_Curl_Exception("你的URL为空，请使用正确的URL地址");
        $this->url = $url;
    }

    /**
     * 获取当前请求的链接地址
     *
     * @return string
     */
    public function getUrl(){
        return $this->url;
    }

    /**
     * 设置BasicAuth参数
     *
     * @param $user 用户名
     * @param $password 密码
     */
    public function setBasicAuth($user,$password){

    }

    /**
     * 获取请求信息
     * 返回一个数组，包含与请求相关的信息
     *
     * @return mixed
     */
    public function getRequestInfo(){
        return curl_getinfo($this->curl);
    }

    /**
     * 获取Http状态码
     *
     * @return mixed
     */
    public function getHttpCode(){
        return curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
    }

    /**
     * 设置请求来源
     *
     * @param $referer
     */
    public function setReferer($referer){
        $this->referer = $referer;
    }

    /**
     * 获取请求来源
     *
     * @return string
     */
    public function getReferer(){
        return $this->referer;
    }

    /**
     * 处理请求来源
     * @access private
     * @return mixed
     */
    private function parseReferer(){
        if(empty($this->referer))return;
        curl_setopt($this->curl,CURLOPT_REFERER,$this->referer);
    }

    /**
     * 设置UserAgent
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.162 Safari/535.19"){
        $this->userAgent = $userAgent;
    }

    /**
     * 获取UserAgent
     *
     * @return string
     */
    public function getUserAgent(){
        return $this->userAgent;
    }

    /**
     * 处理UserAgent
     * @access private
     * @return mixed
     */
    private function parseUserAgent(){
        if(empty($this->userAgent)) return;
        curl_setopt($this->curl,CURLOPT_USERAGENT,$this->userAgent);
    }

    /**
     * 处理Cookie数据
     * @access private
     * @return mixed
     */
    private function parseCookie(){
        if(empty($this->cookie)) return;
        $cookie = "";
        if(!empty($this->cookie)){
            foreach($this->cookie as $key=>$v){
                $cookie.=$key."=".$v.";";
            }
        }
        $cookie = rtrim($cookie,";");
        curl_setopt($this->curl,CURLOPT_COOKIE,$cookie);
    }

    /**
     * 设置COOKIE
     *
     * @param array $cookie
     */
    public function setCookie(array $cookie){
        $this->cookie = array_merge($this->cookie,$cookie);
    }

    /**
     * 获取COOKIE
     *
     * @return array
     */
    public function getCookie(){
        return $this->cookie;
    }

    /**
     * 处理HTTP请求方法
     *
     * @return mixed
     */
    private function parseMethod(){

        if($this->method == self::HTTP_METHOD_GET){
            curl_setopt($this->curl,CURLOPT_HTTPGET,true);
            curl_setopt($this->curl,CURLOPT_POST,false);
            return;
        }

        if($this->method == self::HTTP_METHOD_POST){
            curl_setopt($this->curl,CURLOPT_POST,true);
        }
    }

    /**
     * 获取HTTP方法
     *
     * @return string
     */
    public function getMethod(){
        return $this->method;
    }

    /**
     * 设置HTTP方法
     *
     * @param $method 允许POST、或GET
     * @throws Http_Curl_Exception
     */
    public function setMethod($method){
        $avalidMethos = array("POST","GET","PUT","DELETE");
        $method = strtoupper($method);
        if(!in_array($method,$avalidMethos)){
            throw new Http_Curl_Exception("使用了不允许的HTTP方法：".$method);
        }
        $this->method = $method;
    }

    /**
     * 设置请求参数
     *
     * @param array $params key/value
     */
    public function setParams(array $params){
        $this->params = array_merge($this->params,$params);
    }

    /**
     * 获取请求参数
     *
     * @return array
     */
    public function getParams(){
        return $this->params;
    }

    /**
     * 处理请求参数
     *
     * @return mixed
     */
    public function parseParams(){
        if(empty($this->params)) return;
        if($this->method == self::HTTP_METHOD_GET){
            $urlParam = "";
            foreach($this->params as $key => $get){
                $urlParam .= $key."=".urlencode($get)."&";
            }
            if(!empty($urlParam)){
                if(false === strpos("?",$this->url)){
                    $this->url .= rtrim($urlParam,"&");
                }else{
                    $this->url .= "?".rtrim($urlParam,"&");
                }
            }
        }
        if($this->method == self::HTTP_METHOD_POST){
            curl_setopt($this->curl,CURLOPT_POSTFIELDS,$this->params);
        }
    }

    public function enableRedirect(){

    }

    /**
     * 设置消息头
     *
     * @param array $header
     */
    public function setHeader(array $header){
        $this->header = array_merge($this->header,$header);
    }

    /**
     * 获取消息头
     * @ignory
     * @return array
     */
    public function getHeader(){
        return $this->header;
    }

    /**
     * 发起请求
     * 向服务端发起Http请求，如果请求失败则抛出异常
     *
     * @param array $params 请求参数
     * @param boolen $asBody 将参数值作为消息体发送，会忽略原先设置的参数
     * @return mixed 请求结果
     * @throws Http_Curl_Exception
     */
    public function request($params = array(),$asBody=false){
        //将参数作为消息体
        if($asBody){
            $this->params = $params;
        }else{
            if(!empty($params)) $this->setParams($params);
        }
        $this->parseMethod();
        $this->parseCookie();
        $this->parseUserAgent();
        $this->parseReferer();
        $this->parseParams();
        curl_setopt($this->curl,CURLOPT_URL,$this->url);
        curl_setopt($this->curl,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($this->curl);
        if(false === $result){
            $message = curl_error($this->curl);
            throw new Http_Curl_Exception($message);
        }else{
            return $result;
        }
    }
}

/**
 * CURL 异常类
 *
 * @package Bowl_Http
 * @version 2.1
 * @since 2.1
 * @author zhaotiejia@ebupt.com
 */
class Http_Curl_Exception extends FLEA_Exception{
}