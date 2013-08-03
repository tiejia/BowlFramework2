<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 12-1-11
 * Time: 下午4:21
 * To change this template use File | Settings | File Templates.
 */

class Controller_Default extends Extension_Controller_Base{

    public function actionIndex(){
        redirect(url("Default","Welcome"));
    }

    public function actionWelcome(){
       $this->view->pageTitle = "BowlFramework欢迎页";
       $this->view->render("index.html");
    }

    public function actionEnvCheck(){
        Bowl_Assistant_Project::envCheck();
    }
}