<?php
include_once "common.php";
include_once "../com/API.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH .'axis/ServiceAPI.php';
include_once $APP_PATH."Interaction/Interaction.php";
include_once $ENGINE_PATH."Utility/Debugger.php";

$logger = new Debugger();
$logger->setAppName('smacapp');
$logger->setDirectory('../logs/');
$view = new BasicView();
$app = new ServiceAPI(&$req);
print $app->run();
//print $app;
die();
?>
