<?php
session_start();
require_once "../config/config.inc.php";
require_once "../engines/functions.php";
/** PATH HACK for Admin page **/
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";
/*******************************/
require_once $ENGINE_PATH."View/BasicView.php";
require_once $ENGINE_PATH."Database/SQLData.php";
require_once $ENGINE_PATH."Utility/RequestManager.php";
require_once $ENGINE_PATH."Utility/SessionManager.php";
require_once $ENGINE_PATH."Admin/Admin.php";
$MAIN_TEMPLATE = "common/admin/default.html";
$req = new RequestManager();
?>