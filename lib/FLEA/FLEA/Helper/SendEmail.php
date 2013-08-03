<?php
/**
 * 发送Email类
 * Enter description here ...
 * @author kubo
 *
 */
class FLEA_Helper_SendEmail
{
    /**
     * 发送Email
     *  @author 孙家东
     *  @return_type
     *  @2011-3-24
     *  @param unknown_type $serverPath
     *  @param unknown_type $filename
     *  @param unknown_type $mimeType
     */
    function sendEmail($to, $subject,$message,$from)
    {

		$subject  = "=?UTF-8?B?".base64_encode($subject)."?=";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        //$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers = "From:".$from . "\r\n" .
		
		 mail($to,$subject,$message,$headers);
    }
}
