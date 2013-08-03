<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 12-4-18
 * Time: 下午4:59
 * To change this template use File | Settings | File Templates.
 */

class Controller_Curl extends Extension_Controller_Base{

    public function actionInit(){

        log_message("Test","error","GGGGGGGGGGGGGGGGGGGGGGGG");

        try{
            $httpCurl = new Bowl_Http_Curl("http://10.1.60.154:8888/BowlFramework2/public/index.php?controller=server&action=login");
            $httpCurl->setMethod("POST");
            $xml = "<?xml version='1.0' encoding='GB2312' standalone='yes'?>
               <wwwplatform>
                   <head>
                       <txid>".$tix."</txid>
                       <servicetype>1</servicetype>
                       <appid>1</appid>
                   </head>
                   <body>
                       <accountnum>".$accountnum."</accountnum>
                       <passwd>".$passwd."</passwd>
                       <shortmess>".$sms['smscontent']."</shortmess>
                       <receivermobile>".$receivermobile."</receivermobile>
                       <receiverflag>".$receiverflag."</receiverflag>
                       <groupid>".$groupid."</groupid>
                       <feeusertype>".$feeuserType."</feeusertype>
                       <smsflag>".$smsflag."</smsflag>
                       <mmsmsgid>".$mmsmsgid."</mmsmsgid>
                       <fee>".$feetype."</fee>
                       <chargenumber>".$accountnum."</chargenumber>
                       <sendmobile>".$sendmobile."</sendmobile>
                       <sendtime>".$sms['sendtime']."</sendtime>
                       <requesttimestamp>".date("YmdHis")."</requesttimestamp>
                       <smsdesc>".$smsdesc."</smsdesc>
                       <taskType>".$tasktype."</taskType>
                   </body>
               </wwwplatform>";
            $response = $httpCurl->request($xml,true);
            $httpCode = $httpCurl->getHttpCode();
            echo $response;
            $httpCurl->close();
        }catch(Http_Curl_Exception $e){
            echo "Error:<br>";
            echo $e->getMessage();
        }
    }
}