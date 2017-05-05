<?php
include_once "common.php";
/*set Log Activity*/
global $APP_PATH,$CONFIG,$FB;
require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
$track = new activityReportHelper();
$track->log('logout',$_SESSION['user_id'],TRUE);
$view = new BasicView();
$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
unset($_SESSION);
session_destroy();
sendRedirect($CONFIG['LANDING_BERKAH_DOMAIN']);
print $view->toString("axis/loading_message.html");
?>