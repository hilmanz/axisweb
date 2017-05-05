<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
global $CONFIG;

if ($_REQUEST['cookies'] != null && $_REQUEST['mem_id'] != null){
	$currentSessionID = $_REQUEST['cookies'];

	// Set a cookie for the session ID.
	session_id($currentSessionID);

	// Start a session.
	session_start();
}
// var_dump($_SESSION);
	if($_SESSION['user_id']==null){
		$_SESSION['user_id'] = 0;
	}
	if ($_SESSION['user_login_id'] == null){
		$_SESSION['user_login_id'] = $_REQUEST['fb_id'];
		
		$_SESSION['user_id'] = $_REQUEST['mem_id'];
	}
//access token for accessing our API
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY']);

$fb = new FacebookHelper($req);
$fb->init();

$_SESSION['fb_authorized'] = false;
		$fb->open(0);
		// print_r($fb->login($_SESSION['user_id']));exit;
		// var_dump($_SESSION['user_login_id']);
		//var_dump($_SESSION);
		if($fb->login($_SESSION['user_id'])){
			
			$userId = $fb->id();
			$me = $fb->myself();
			$likebtn = $fb->getLikeButton($CONFIG['FB_DOMAIN']);
			$is_like = $fb->isLiked($_SESSION['user_login_id']);	
			//print $_SESSION['user_login_id']."->".$is_like."<br/>";
			/*set Log Activity*/
			global $APP_PATH;
			require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
			$track = new activityReportHelper();
			$track->log('login',$_SESSION['user_id'],TRUE);
			$_SESSION['login'] = true;
		}else{
			print "cannot login<br/>";
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
	if(!$is_like) {
		// var_dump($_SESSION['user_login_id']);
		//print "1";
		//die();
		sendRedirect($CONFIG['FB_FANS_PAGE']);
		print $view->toString("axis/loading_message.html");
		die();
	}

	if($_SESSION['login']!=true){
	
		$_SESSION=null;
		
		if($_REQUEST['fb_id']=='') {
			// var_dump($_SESSION);
			if($is_like){
			//	print "2";
				//die();
				sendRedirect($CONFIG['LANDING_BASE_DOMAIN']);
			}else{
				//print "3";
				//die();
				sendRedirect($CONFIG['FB_FANS_PAGE']);
			}
			print $view->toString("axis/loading_message.html");
			die();
		}
		
	}
	
	if($_SESSION['login']==true && $_SESSION['register']==false)
	{
		// if(!$page_liked) {
			// sendRedirect($CONFIG['FB_FANS_PAGE']);
			// }else{
			// var_dump($_SESSION);
			//print "4";
			//die();
			sendRedirect($CONFIG['BASE_DOMAIN']);
			// }
		print $view->toString("axis/loading_message.html");
		die();
		
		
		
	}
	if($_SESSION['login']==true && $_SESSION['register']==true) {
		sendRedirect($CONFIG['REGISTRATION_DOMAIN']);
		print $view->toString("axis/loading_message.html");
		die();
	}

print $view->toString("axis/loading_message.html");
die();
?>