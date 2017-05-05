<?php
session_start();
include_once "../engines/Gummy.php";
include_once "../engines/functions.php";
include_once "../com/Application.php";

$MAIN_TEMPLATE = "sample/default.html";
$req = new RequestManager();

$system = new System();

if($system->isMaintenance()){
	print json_encode(array("status"=>"909","message"=>"System is under maintenance"));
	die();
}
?>
