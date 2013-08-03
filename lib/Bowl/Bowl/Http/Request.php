<?php
/**
 * Http请求处理类
 *
 * 提供Http请求输入的处理和获取
 * 用于获取POST、GET、COOKIE等客户端输入数据
 *
 * @package Bowl_Http
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 *
 */
class Bowl_Http_Request{

    private $autoFilter = false;

    protected $post = array();

    protected $get  = array();

    protected $request = array();

    protected $clientIp;


    public function __construct($config = array()){
        foreach($config as $key=>$value){
            $this->$key = $value;
        }
        if(!empty($_POST)){
            foreach($_POST as $key=>$value){
                if(is_array($value)){
                    $tmp = array();
                    foreach($value as $subkey=>$sub){
                        $tmp[$subkey] = htmlspecialchars($sub);
                    }
                    $this->post[$key] = $tmp;
                }else{
                    $this->post[$key] = htmlspecialchars($value);
                }
            }
        }
        if(!empty($_GET)){
            foreach($_GET as $key=>$value){
                if(is_array($value)){
                    $tmp = array();
                    foreach($value as $subkey=>$sub){
                       $tmp[$subkey] = htmlspecialchars($sub);
                    }
                    $this->get[$key] = $tmp;
                }else{
                    $this->get[$key] = htmlspecialchars($value);
                }
            }
        }

        $this->request = array_merge($this->get,$this->post);
    }


    /**
     * 获取$_REQUEST数据
     *
     * @param $key request的Key
     * @param bool $emptyValue 如果没有这个值的时候的默认值，默认为false
     * @return bool
     */
    public function request($key,$emptyValue = false){
        $value = isset($this->request[$key])?$this->request[$key]:$emptyValue;
        return $value;
    }

    /**
     * 获取整个$_REQUEST数组
     *
     * @param bool $emptyValue 没有值时的默认值，默认为false
     * @return array|bool
     */
    public function getRequestParameters($emptyValue = false){
        if(empty($this->request)){
            return $emptyValue;
        }else{
            return $this->request;
        }
    }

    /**
     * 获取$_GET数据
     * @param $key get值的Key
     * @param bool $emptyValue 没有设置值的时候的默认值
     * @return bool
     */
    public function get($key,$emptyValue = false){
        $value = isset($this->get[$key])?$this->get[$key]:$emptyValue;
        return $value;
    }

    /**
     * 获取整个$_GET数组
     *
     * @param bool $emptyValue 没有设置GET时候的默认值
     * @return array|bool
     */
    public function getGetParameters($emptyValue = false){
        if(empty($this->get)){
            return $emptyValue;
        }else{
            return $this->get;
        }
    }

    /**
     * 获取单个$_POST数据
     * @param $key POST的key
     * @param bool $emptyValue 没有值的时候的默认值
     * @return bool
     */
    public function post($key,$emptyValue = false){
        $value = isset($this->post[$key])?$this->post[$key]:$emptyValue;
        return $value;
    }


    /**
     * 获取单个$_SERVER数据
     *
     * @param $key SERVER key
     * @param bool $emptyValue 没有设置值的时候的默认值
     * @return bool|string
     */
    public function server($key,$emptyValue=false){
        return isset($_SERVER[$key])?$this->filterXss($_SERVER[$key]):$emptyValue;
    }

    /**
     * 获取单个$_COOKIE数据
     *
     * @param $key cookie的key
     * @param bool $emptyValue 没有设置值时候的默认值，默认为false
     * @return bool|string
     */
    public function cookie($key,$emptyValue=false){
        return isset($_COOKIE[$key])?$this->filterXss($_COOKIE[$key]):$emptyValue;
    }

    /**
     * 获取整个$_POST数组
     *
     * @param bool $emptyValue 没有设置值时候的默认值，默认为false
     * @return array|bool
     */
    public function getPostParameters($emptyValue = false){
        if(empty($this->post)){
            return $emptyValue;
        }else{
            return $this->post;
        }
    }

    /**
     * 过滤XSS攻击数据
     * @param $value
     * @return string
     */
    public function filterXss($value){
        return htmlspecialchars($value);
    }


    /**
     * 获取请求来源
     * 获取$_SERVER['HTTP_REFERER]值
     * @return mixed
     */
    public function getReferer($default = ""){
        return $_SERVER['HTTP_REFERER'];
    }


    private function valid_ip($ip)
    {
        $ip_segments = explode('.', $ip);

        if (count($ip_segments) != 4)
        {
            return FALSE;
        }

        if ($ip_segments[0][0] == '0')
        {
            return FALSE;
        }
        // Check each segment
        foreach ($ip_segments as $segment){
            if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
            {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * 获取客户端IP
     * 获取访问者的IP地址
     * @return
     */
    public function getClientIp(){

        if (!empty($this->clientIp)) {
            return $this->clientIp;
        }
        
        if ($this->server('REMOTE_ADDR') AND $this->server('HTTP_CLIENT_IP')) {
            $this->clientIp = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif ($this->server('REMOTE_ADDR'))
        {
            $this->clientIp = $_SERVER['REMOTE_ADDR'];
        }
        elseif ($this->server('HTTP_CLIENT_IP'))
        {
            $this->clientIp = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif ($this->server('HTTP_X_FORWARDED_FOR'))
        {
            $this->clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if ($this->clientIp === FALSE) {
            $this->clientIp = '0.0.0.0';
            return $this->clientIp;
        }

        if (strpos($this->clientIp, ',') !== FALSE) {
            $x = explode(',', $this->clientIp);
            $this->clientIp = trim(end($x));
        }

        if (!$this->valid_ip($this->clientIp)) {
            $this->clientIp = '0.0.0.0';
        }

        return $this->clientIp;
    }
}