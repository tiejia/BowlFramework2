<?php
define("BOWL_RUN_MODE","debug");
//projectBASEDIR
define("BOWL_BASE_DIR",dirname(dirname(__FILE__))."/");
//AppDir
define("BOWL_BASE_URL","http://yourhost.com/public/");
//define("BOWL_BASE_URL","http://localhost/mimao/public/");
require(BOWL_BASE_DIR."/lib/Bowl/Bowl.php");
//Go
Bowl::getInstance(BOWL_RUN_MODE)->loadApp()->run();
?>