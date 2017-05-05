<?php
include_once "common.php";

/*set Log Activity*/
global $APP_PATH,$CONFIG,$FB;
require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
$track = new activityReportHelper();
$track->log('logout',$_SESSION['user_id'],TRUE);

$view = new BasicView();

$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
setcookie('jam4_'.$_SESSION['user_id'],false);
unset($_SESSION);
session_destroy();

setcookie('fbsr_'.$FB['appID'],false);
session_start();
$_SESSION['isRegister'] = true;
print $view->toString("axis/loading_message.html");
sendRedirect($CONFIG['LANDING_BASE_DOMAIN']);
die();
?>