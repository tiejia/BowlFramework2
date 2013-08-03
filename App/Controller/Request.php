<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 12-4-23
 * Time: 下午1:14
 * To change this template use File | Settings | File Templates.
 */

class Controller_Request extends Extension_Controller_Base{


      public function actionIndex(){
          $post = $this->input->getPostParameters(false);
          if(empty($post)){
               $this->view->render("request.html");
          }else{
               dump($post);
          }
      }
}