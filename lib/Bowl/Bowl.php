<?php
/**
 * Bowl 框架主文件
 *
 * 提供框架通用方法
 *
 * @package Bowl
 * @since 2.0
 * @version 2.1
 * @author zhaotiejia@ebupt.com
 */
class Bowl{

    static  $version = "2.1beta";

    /**
     * UUID静态方法
     *
     * 生成32位的UUID
     *
     * @static
     * @return string
     */
    static function uuid(){
        return md5(uniqid(rand(),true));
    }

    static function version(){
        return self::$version;
    }
}

function __autoload($class){
    Flea::loadClass($class);
}

