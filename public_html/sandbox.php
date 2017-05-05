<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

if($_REQUEST['user_id']!=null){
	$user_id = intval($_REQUEST['user_id']);
	if($user_id==0){
		$user_id = 1;
	}
	$_SESSION['user_id'] = $user_id;
}
if($_SESSION['user_id']==null){
	print "you must provide a user_id in parameter !<br/> for example : index.php?user_id=12";
	die();
}
//access token for accessing our API
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY']);

$fb = new FacebookHelper($req);


$fb->open(0);
$fb_id = $fb->getFBId($_SESSION['user_id']);
$ping_token = $fb->getPingRequestToken($_SESSION['user_id']);
$user_info = $fb->getUserInfo($_SESSION['user_id']);
//early ping
$fb->ping($_SESSION['user_id']);

$fb->close();

$view = new BasicView();
$view->assign("fb_id",$fb_id);
$view->assign("user_id",$_SESSION['user_id']);
$view->assign("access_token",$access_token);
$view->assign("service_url",$CONFIG['SERVICE_URL']);
$view->assign("friends",$fb->get_online_friends($_SESSION['user_id']));
$view->assign("ping_token",$ping_token);
$view->assign("nickname",$user_info['nickname']);
print $view->toString("axis/sandbox.html");
?>