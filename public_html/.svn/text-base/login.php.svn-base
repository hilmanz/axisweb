<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH. APPLICATION ."/helper/loginHelper.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName('smaclogin');
$logger->setDirectory('../logs/');

$view = new BasicView();
$app = new loginHelper($req);
print $app->loginSession();

?>