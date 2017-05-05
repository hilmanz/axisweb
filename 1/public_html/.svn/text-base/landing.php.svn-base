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
	$is_like = $fb->isLiked($_SESSION['user_login_id']);
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
// print_r('<pre>');
// print_r($is_like);exit;
// print_r('<pre>');print_r($_SESSION);exit;
$view = new BasicView();


if(!$_SESSION['user_login_id']){
	$mustRegister=true;	
	$view->assign("lanjutUrl",$CONFIG['FB_DOMAIN']);
}else {
	if(!$is_like) {
		$view->assign("lanjutUrl",$CONFIG['FB_FANS_PAGE']);
	}else{
		$view->assign("lanjutUrl",$CONFIG['BASE_DOMAIN']);
	}
}
if($_SESSION['register']==true) $mustRegister=true;	
$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);

$view->assign("me",$me);
$view->assign("userId",$userId);
$view->assign("is_liked",$is_like);
$view->assign("page_liked",$page_liked);
$view->assign("likebtn",$likebtn);
$view->assign("appId",$FB['appID']);
$view->assign("access_token",$access_token);
$view->assign("mustRegister",$mustRegister);
$view->assign("service_url",$CONFIG['SERVICE_URL']);
$view->assign("userStat",$fb->userStat());
$view->assign("friends",$fb->get_friends($_SESSION['user_id']));
$view->assign("liked_token",$token);
session_destroy();
session_start();
$_SESSION['register'] =$mustRegister;
if($_SESSION['register']==true) $view->assign("registerUrl",$CONFIG['REGISTRATION_DOMAIN']);


print $view->toString("axis/landing.html");

?>
