<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 12-4-23
 * Time: 上午11:09
 * To change this template use File | Settings | File Templates.
 */

class Controller_Soap extends Extension_Controller_Base{

    public function actionClient(){

        try{
            $soapClient = new Bowl_Http_SoapClient("http://211.141.83.35:8080/eaa/services/IfSSO?wsdl");
            $res = $soapClient->authen($xml);
            $traceStr = $soapClient->getTraceAsXml();
            dump($traceStr);
        }catch(Http_SoapClient_Exception $e){
            dump($e->getTrace());
            echo "Trigger error".$e->getMessage();
        }
    }


}