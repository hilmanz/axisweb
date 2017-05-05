<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
// var_dump($_SESSION['user_login_id']);exit;

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

// var_dump($_SESSION);exit;
$fb->open(0);
if($fb->login()){
	$userId = $fb->id();
	$me = $fb->myself();
	$likebtn = $fb->getLikeButton($CONFIG['BASE_DOMAIN']);
	$is_like = $fb->isLiked($_SESSION['user_id']);
	$_SESSION['login'] = true;
	$memberId = $_SESSION['user_id'];
}
$signed_request = $fb->getSignedRequest();
$page_info = $signed_request['page'];
$page_liked = $page_info['liked'];



if($page_liked){
	$token = urlencode64(serialize(array("page"=>$page_info,"is_liked"=>true)));
	//mekanisme flag_for_like dieksekusi di microsite, bukan di tab.
	//caranya dengan memparse token diatas, ambilk nilai is_liked_nya.
	$fb->flag_for_like($userId);
	
}else{
	$fb->flag_for_unlike($userId);
}
$fb->close();

$view = new BasicView();

$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);

if(!$_SESSION['user_login_id']){
	$mustRegister=true;	
	$view->assign("lanjutUrl",$CONFIG['REGISTRATION_DOMAIN']);
}else{
	$view->assign("lanjutUrl",$CONFIG['BASE_DOMAIN']);
}
if($_SESSION['register']==true) $mustRegister=true;	
$view->assign("me",$me);
$view->assign("userId",$userId);
$view->assign("memberId",$memberId);
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
// session_destroy();
//session_start();
// $currentSessionID = session_id();
// var_dump($_SESSION);
// if(!$_SESSION['user_login_id']){

// }

$_SESSION['register'] =$mustRegister;
if($_SESSION['register']==true) $view->assign("registerUrl",$CONFIG['REGISTRATION_DOMAIN']);

$currentSessionID = session_id();
$view->assign("cookies",$currentSessionID);

print $view->toString("axis/facebook_page_fan.html");
?>