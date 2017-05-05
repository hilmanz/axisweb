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
	//print "you must provide a user_id in parameter !<br/> for example : index.php?user_id=12";
	//die();
	$_SESSION['user_id'] = 0;
}
//access token for accessing our API
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY']);

$fb = new FacebookHelper($req);
$fb->init();

$_SESSION['fb_authorized'] = false;


$fb->open(0);
if($fb->login($_SESSION['user_id'])){
	$userId = $fb->id();
	$me = $fb->myself();
	$likebtn = $fb->getLikeButton("http://localhost/axis/web/fb/");
	$is_like = $fb->isLiked($_SESSION['user_id']);
}
$signed_request = $fb->getSignedRequest();
$page_info = $signed_request['page'];
$page_liked = $page_info['liked'];

if($page_liked){
	$fb->flag_for_like($userId);
}
$fb->close();

$view = new BasicView();
$view->assign("me",$me);
$view->assign("userId",$userId);
$view->assign("is_liked",$is_like);
$view->assign("page_liked",$page_liked);
$view->assign("likebtn",$likebtn);
$view->assign("appId",$FB['appID']);
$view->assign("access_token",$access_token);
$view->assign("service_url",$CONFIG['SERVICE_URL']);
$view->assign("friends",$fb->get_friends($_SESSION['user_id']));
$view->assign("login_url",$fb->getLoginURL());
print $view->toString("axis/dummy_mobile.html");
?>