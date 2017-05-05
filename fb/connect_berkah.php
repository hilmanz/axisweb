<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
global $CONFIG;
$fb = new FacebookHelper($req);
$fb->init(1);

$fb->open(0);

if($_SESSION['user_id']==null){
	$_SESSION['user_id'] = 0;
	if(eregi("[0-9]+",$_REQUEST['fb_id'])){
		$sql = "SELECT user_id FROM axis.tbl_user_fb WHERE fb_id={$_REQUEST['fb_id']} LIMIT 1";
		$user = $fb->query($sql);
		$_SESSION['fb_id'] = $_REQUEST['fb_id'];
		$_SESSION['user_id'] = $user['id'];
		
	}
}
	
//access token for accessing our API
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY']);



$_SESSION['fb_authorized'] = false;

// print_r($fb->login($_SESSION['user_id']));exit;

if($fb->login($_SESSION['user_id'])){
	$userId = $fb->id();
	$me = $fb->myself();
	$likebtn = $fb->getLikeButton($CONFIG['FB_DOMAIN']);
	$is_like = $fb->isLiked($_SESSION['user_login_id']);	
	
	/*set Log Activity*/
	global $APP_PATH;
	require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
	$track = new activityReportHelper();
	$track->log('login',$_SESSION['user_id'],TRUE);
	$_SESSION['login'] = true;
	if($_REQUEST['t']==null){
		$token = urlencode64(serialize(array("page"=>null,"is_liked"=>$is_like,"me"=>$me)));
	}else{
		$token = $_REQUEST['t'];
	}
}
$signed_request = $fb->getSignedRequest();
$page_info = $signed_request['page'];
$page_liked = $page_info['liked'];

if($page_liked){
	$fb->flag_for_like($userId);
}
$fb->close();

$view = new BasicView();

$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);

	/*
	if(!$is_like) {
		sendRedirect($CONFIG['FB_FANS_PAGE_BERKAH']);
		print $view->toString("axis/loading_message.html");
		die();
	}
	*/
	if($_SESSION['login']!=true){
	
		$_SESSION=null;
		
		if($_REQUEST['fb_id']=='') {
			if($is_like)sendRedirect($CONFIG['LANDING_BERKAH_DOMAIN']);
			else sendRedirect($CONFIG['FB_FANS_PAGE_BERKAH']);
			print $view->toString("axis/loading_message.html");
			die();
		}
		
	}
	
	if($_SESSION['login']==true)
	{
		
		sendRedirect($CONFIG['BASE_DOMAIN_BAROKAH']."?fb_id=".$_REQUEST['fb_id']."&t=".$token);
			
		print $view->toString("axis/loading_message.html");
		die();
		
		
		
	}
	

print $view->toString("axis/loading_message.html");
die();
?>