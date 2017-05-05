<?php
session_destroy();
session_start();
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

//access token for accessing our API
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY']);

$fb = new FacebookHelper($req);
$fb->init();

$_SESSION['fb_authorized'] = false;


$fb->open(0);
if($fb->login()){
	$userId = $fb->id();
	$me = $fb->myself();
	$likebtn = $fb->getLikeButton($CONFIG['BASE_DOMAIN']);
	$is_like = $fb->isLiked($_SESSION['user_id']);
	$_SESSION['login'] = true;
}
$signed_request = $fb->getSignedRequest();
$page_info = $signed_request['page'];
$page_liked = $page_info['liked'];

// print_r($signed_request);exit;

if($page_liked){
	$token = urlencode64(serialize(array("page"=>$page_info,"is_liked"=>true)));

	
}
$fb->close();
//login true, pas uda like
if($is_like) $_SESSION['login']=true;
// print_r($_SESSION);exit;
$view = new BasicView();
if($_SESSION['login']==true && $_SESSION['logout']==false){
	sendRedirect($CONFIG['BASE_DOMAIN_MOBILE']);
	global $CONFIG;
	$view = new BasicView();
	$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
	print $view->toString("axis/loading_message.html");
	die();
 }else {
	if($_SESSION['logout']==false){
		$view->assign("userId",$userId);
		/*set Log Activity*/
		global $APP_PATH;
		require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
		$track = new activityReportHelper();
		$track->log('login',$_SESSION['user_id'],TRUE);
	}else{
		$view->assign("userId",0);
		$_SESSION['logout']=false;
		
	}
	
	global $CONFIG;
	$view = new BasicView();
	$view->assign("mobileUrl",$CONFIG['MOBILE_DOMAIN']);
	
	$view->assign("me",$me);
	$view->assign("is_liked",$is_like);
	$view->assign("page_liked",$page_liked);
	$view->assign("likebtn",$likebtn);
	$view->assign("appId",$FB['appID']);
	$view->assign("access_token",$access_token);
	$view->assign("service_url",$CONFIG['SERVICE_URL']);
	$view->assign("friends",$fb->get_friends($_SESSION['user_id']));
	$view->assign("login_url",$fb->getLoginURL());
	print $view->toString("axis/mobile/mobile_login.html");
}
?>