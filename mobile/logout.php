<?php
include_once "common.php";

/*set Log Activity*/
global $APP_PATH;
require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
$track = new activityReportHelper();
$track->log('logout',$_SESSION['user_id'],TRUE);

$view = new BasicView();

$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);

session_destroy();
global $CONFIG,$FB;
unset($_COOKIE['fbsr_'.$FB['appID']]);
sendRedirect($CONFIG['BOTH_LANDING_PAGE']);
print $view->toString("axis/loading_message.html");
?>