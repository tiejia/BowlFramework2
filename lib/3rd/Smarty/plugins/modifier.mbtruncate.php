<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 11-10-11
 * Time: 下午5:44
 * To change this template use File | Settings | File Templates.
 */
 
function smarty_modifier_mbtruncate($string, $length = 80, $etc = '',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (mb_strlen($string,'UTF-8') > $length) {
        $length -= min($length, mb_strlen($etc,'UTF-8'));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length+1,'UTF-8'));
        }
        if(!$middle) {
            return mb_substr($string, 0, $length,'UTF-8') . $etc;
        } else {
            return mb_substr($string, 0, $length/2,'UTF-8') . $etc . mb_substr($string, -$length/2,'UTF-8');
        }
    } else {
        return $string;
    }
}