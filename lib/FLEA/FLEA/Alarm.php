<?php
function alarm_message($msg, $level = 'log', $title = '')
{
    static $instance = null;

    if (is_null($instance)) {
        $instance = array();
        $obj =& FLEA::getSingleton('FLEA_Alarm');
        $instance = array('obj' => & $obj);
    }

    return $instance['obj']->alarm_send($msg, $level, $title);
}

/**
 * 
 * 用户告警用户发送Email或者是手机短信
 * @author 孙家东
 *
 */
class FLEA_Alarm
{
    /**
     * 项目名称
     */
    private $_title = '';
	/**
	 * 
	 * 
	 * @var unknown_type
	 */
 	private $_emailTo = '';
    
	private $_telphoneTo = ''; 
    /**
     * 
     * 要发送告警的级别
     *  @var unknown_type
     */
    private $_alarmLevel;
    
    private $_helperEmail;
    
    private $_helperSms;

    /**
     * 构造函数
     *
     * @return FLEA_Alarm
     */
    function FLEA_Alarm()
    {
        $cfgAlarm = FLEA::getAppInf('alarmNotify');
        if (empty($cfgAlarm)) {
            // 如果没有指定警告配置 默认返回
            return false;
        }
        if(!isset($cfgAlarm['title']) || !isset($cfgAlarm['email']) || !isset($cfgAlarm['telphone']) || !isset($cfgAlarm['alarmLevel'])){
        	return false;
        }
        $this->_title = $cfgAlarm['title'];
        $this->_emailTo = $cfgAlarm['email']; 
        $this->_telphoneTo = $cfgAlarm['telphone'];
        $this->_alarmLevelParam = $cfgAlarm['alarmLevel'];
    	
        $errorLevel = explode(',', strtolower($this->_alarmLevelParam));
        $errorLevel = array_map('trim', $errorLevel);
    	foreach ($errorLevel as $value) {
                $this->_alarmLevel[$value] = true;       	
        }
        $this->_helperEmail = & FLEA::getSingleton('FLEA_Helper_SendEmail');
        $this->_helperSms = new Send_Sms();
    }

   	function alarm_send($msg, $level = 'error',$title=''){
   		
   		$msg = $this->_title.':'.$title.':'.$msg;
   		$level = strtolower($level);
   		
   		if (!isset($this->_alarmLevel[$level])) { return; }
   		//发送Email
   		if(!isset($this->_emailTo['to']) || !is_array($this->_emailTo['to'])){ return;}
   		foreach($this->_emailTo['to'] as $to){
   			
   			//$this->_helperEmail->sendEmail($to, $this->_emailTo['subject'],$msg,$this->_emailTo['from']);
   			log_message($msg,'info',__CLASS__.'::'.__FUNCTION__);
   		}
   		
   		//发送短信
   		if(!isset($this->_telphoneTo['to']) || !is_array($this->_telphoneTo['to'])){return;}
   		foreach($this->_telphoneTo['to'] as $to){
   			$data["username"] = $this->_telphoneTo['sendPhone'];  
			$data["password"] = $this->_telphoneTo['sendPhonePassword'];  
			$data["sendto"] = $to;  
			$data["message"] = $msg;  
   		}
   	}

}
  
class Send_Sms{
	private $curl;
	function Send_Sms(){
		$this->curl = new Curl_Class();  
	}
	
	function send($data){
		if(!is_array($data)){
			return false;
		}
		return @$this->curl->post("http://sms.api.bz/fetion.php", $data);  
	}
}


//curl类  
class Curl_Class  
{  
    function Curl_Class()  
    {  
        return true;  
    }  
  
    function execute($method, $url, $fields = '', $userAgent = '', $httpHeaders = '', $username = '', $password = '')  
    {  
        $ch = Curl_Class::create();  
        if (false === $ch)  
        {  
            return false;  
        }  
  
        if (is_string($url) && strlen($url))  
        {  
            $ret = curl_setopt($ch, CURLOPT_URL, $url);  
        }  
        else  
        {  
            return false;  
        }  
        //是否显示头部信息  
        curl_setopt($ch, CURLOPT_HEADER, false);  
        //  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
  
        if ($username != '')  
        {  
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);  
        }  
  
        $method = strtolower($method);  
        if ('post' == $method)  
        {  
            curl_setopt($ch, CURLOPT_POST, true);  
            if (is_array($fields))  
            {  
                $sets = array();  
                foreach ($fields AS $key => $val)  
                {  
                    $sets[] = $key . '=' . urlencode($val);  
                }  
                $fields = implode('&',$sets);  
            }  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  
        }  
        else if ('put' == $method)  
        {  
            curl_setopt($ch, CURLOPT_PUT, true);  
        }  
  
        //curl_setopt($ch, CURLOPT_PROGRESS, true);  
        //curl_setopt($ch, CURLOPT_VERBOSE, true);  
        //curl_setopt($ch, CURLOPT_MUTE, false);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);//设置curl超时秒数  
  
        if (strlen($userAgent))  
        {  
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);  
        }  
  
        if (is_array($httpHeaders))  
        {  
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);  
        }  
  
        $ret = curl_exec($ch);  
  
        if (curl_errno($ch))  
        {  
            curl_close($ch);  
            return array(curl_error($ch), curl_errno($ch));  
        }  
        else  
        {  
            curl_close($ch);  
            if (!is_string($ret) || !strlen($ret))  
            {  
                return false;  
            }  
            return $ret;  
        }  
    }  
  
    function post($url, $fields, $userAgent = '', $httpHeaders = '', $username = '', $password = '')  
    {  
        $ret = Curl_Class::execute('POST', $url, $fields, $userAgent, $httpHeaders, $username, $password);  
        if (false === $ret)  
        {  
            return false;  
        }  
  
        if (is_array($ret))  
        {  
            return false;  
        }  
        return $ret;  
    }  
  
    function get($url, $userAgent = '', $httpHeaders = '', $username = '', $password = '')  
    {  
        $ret = Curl_Class::execute('GET', $url, '', $userAgent, $httpHeaders, $username, $password);  
        if (false === $ret)  
        {  
            return false;  
        }  
  
        if (is_array($ret))  
        {  
            return false;  
        }  
        return $ret;  
    }  
  
    function create()  
    {  
        $ch = null;  
        if (!function_exists('curl_init'))  
        {  
            return false;  
        }  
        $ch = curl_init();  
        if (!is_resource($ch))  
        {  
            return false;  
        }  
        return $ch;  
    }  
}  
?>