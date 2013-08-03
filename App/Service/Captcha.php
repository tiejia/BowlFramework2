<?php
/**
 * 图片验证码服务
 *
 * 基于Bowl_Helper中的两个验证码助手生成验证码并提供验证码检测
 * @package Bowl_Service
 * @author zhaotiejia@ebupt.com
 * @version 2.1
 */
class Service_Captcha{

    private $captchaPrefix = "bowl_captcha_";
    private $captchaHelper;


    public function __construct(){
        $this->captchaHelper = new Bowl_Helper_CaptchaIm();
    }

    /**
     * 渲染图片验证码
     * 向前端发送验证码图片流
     *
     * @param string $captchaName 验证码标示名 用于标示当前存储的验证码
     */
    public function render($captchaName = "login"){
        $captchaCode = $this->captchaHelper->createImage();
        $_SESSION[$this->captchaPrefix.$captchaName] = strtolower(trim($captchaCode));
    }

    /**
     * 检测验证码是否正确
     *
     * @param $code
     * @param string $captchaName 验证码标示名 用于从SESSION中查找对应的值
     * @return bool
     */
    public function check($code,$captchaName = "login"){
        $codeName = $this->captchaPrefix.$captchaName;
        if(empty($_SESSION[$codeName])) return false;
        return strtolower($code) == $_SESSION[$codeName];
    }

}
