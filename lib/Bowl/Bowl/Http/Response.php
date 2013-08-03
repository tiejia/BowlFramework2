<?php
define("BOWL_HTTP_BASE_DIR",dirname(__FILE__).DS);
/**
 * HttpResponse类
 * 向客户端发送Http数据
 *
 * @package Bowl_Http
 * @author zhaotiejia@ebupt.com
 * @version 2.1
 */
class Bowl_Http_Response{

    /**
     * 让网页弹出一个alert信息，用户点击确定后跳转
     * @param $message alert信息
     * @param string $redirectUrl 跳转地址
     */
    public function jsAlert($message,$redirectUrl="/"){
        js_alert($message,"",$redirectUrl);
    }

    /**
     * 返回特定格式的JSON数据
     * 当需要向客户端返回JSON数据时，必须使用此方法。
     *
     * @param $state 请求状态：true 成功 false 失败
     * @param string $info 信息：给客户端返回的信息，如失败原因
     * @param null $data 返回给客户端的数据 Array key/value形式
     * @param bool $send 是否直接发送，如果true则终止程序执行，如果false则返回json字符串
     * @return string
     */
    public function ajaxJSON($state,$info="",$data=null,$send=true){
        if($send){
            echo json_encode(array("success"=>$state,"info"=>$info,"data"=>$data));
            exit(0);
        }else{
            return json_encode(array("success"=>$state,"info"=>$info,"data"=>$data));
        }
    }

    /**
     * 返回HTTP 401 状态码
     *
     * @param string $message 提示信息
     */
    public function show401($message = "对不起，您还未登录！"){
        echo $this->showError(401,$message);
        exit(0);
    }

    /**
     * 返回HTTP 404 状态码
     *
     * @param string $message 提示信息
     */
    public function show404($message = "对不起，找不到您请求的资源！"){
        echo $this->showError(404,$message);
        exit(0);
    }

    /**
     * 返回HTTP 500 状态码
     *
     * @param string $message 提示信息
     */
    public function show500($message = "对不起，系统忙，请稍后重试！"){
        echo $this->showError(500,$message);
        exit(0);
    }

    /**
     * 返回HTTP 403 状态码
     *
     * @param string $message 提示信息
     */
    public function show403($message = "对不起，你没有访问权限！"){
        echo $this->showError(403,$message);
        exit(0);
    }

    private function showError($statusCode = 500,$message = "" , $template = null ){
        //设置消息头
        $this->setResponseHeader($statusCode,$message);
        //显示页面
        if(is_null($template)){
            $template = $statusCode;
        }
        if (ob_get_level() > $this->ob_level + 1){
            ob_end_flush();
        }
        ob_start();
        $message = $message;
        include(BOWL_HTTP_BASE_DIR.'errors'.DS.$template.".php");
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    /**
     * 设置响应消息头
     *
     * @param $code
     * @param $text
     * @return void
     */
    private function setResponseHeader($code,$text = ''){

        $httpCodeTextMap = array(
            200	=> 'OK',
            201	=> 'Created',
            202	=> 'Accepted',
            203	=> 'Non-Authoritative Information',
            204	=> 'No Content',
            205	=> 'Reset Content',
            206	=> 'Partial Content',

            300	=> 'Multiple Choices',
            301	=> 'Moved Permanently',
            302	=> 'Found',
            304	=> 'Not Modified',
            305	=> 'Use Proxy',
            307	=> 'Temporary Redirect',

            400	=> 'Bad Request',
            401	=> 'Unauthorized',
            403	=> 'Forbidden',
            404	=> 'Not Found',
            405	=> 'Method Not Allowed',
            406	=> 'Not Acceptable',
            407	=> 'Proxy Authentication Required',
            408	=> 'Request Timeout',
            409	=> 'Conflict',
            410	=> 'Gone',
            411	=> 'Length Required',
            412	=> 'Precondition Failed',
            413	=> 'Request Entity Too Large',
            414	=> 'Request-URI Too Long',
            415	=> 'Unsupported Media Type',
            416	=> 'Requested Range Not Satisfiable',
            417	=> 'Expectation Failed',

            500	=> 'Internal Server Error',
            501	=> 'Not Implemented',
            502	=> 'Bad Gateway',
            503	=> 'Service Unavailable',
            504	=> 'Gateway Timeout',
            505	=> 'HTTP Version Not Supported'

        );
        if (isset($httpCodeTextMap[$code])){
            $text = $httpCodeTextMap[$code];
        }
        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
        if (substr(php_sapi_name(), 0, 3) == 'cgi'){
            header("Status: {$code} {$text}", TRUE);
        }elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0'){
            header($server_protocol." {$code} {$text}", TRUE, $code);
        }else{
            header("HTTP/1.1 {$code} {$text}", TRUE, $code);
        }
    }

    /**
     * 向客户端发送Cookie
     *
     * @param $key Cookie的Key
     * @param $value Cookie的值
     * @param $expire cookie 过期时间
     *
     */
    public function setCookie($key,$value,$expire){
        return setcookie($key,$value,$expire);
    }
}