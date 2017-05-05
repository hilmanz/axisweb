<?php
session_destroy();
session_start();
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
$service_url = $CONFIG['SERVICE_URL'];
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY'],'twaxis',true);
$view = new BasicView();

$view->assign("barokahUrl",$CONFIG['BAROKAH_SOURCES']);

$token = unserialize(urldecode64($req->getParam('t')));
$fb_id = $req->getParam('fb_id');
$_SESSION['t'] = $req->getParam('t');

if(!$fb_id||$token['me']['id']!=$fb_id) {
	sendRedirect($CONFIG['BERKAH_LANDING']);
	die();
}else{
	//$token = urlencode64(serialize(array("user_id"=>$_SESSION['user_id'])));
	//check apakah sudah kasih permission ke twitterAPP axis.
	$call = $CONFIG['BASE_DOMAIN_CONNECT'].$service_url."?service=twitter&m=login&access_token={$access_token}";
	$response = json_decode(file_get_contents($call));
	if($response->status==1){
		//sudah kasih ijin
		$_SESSION['login'] = true;
		$_SESSION['twit_id'] = $response->data->user;
		
		sendRedirect($CONFIG['BASE_DOMAIN_BAROKAH']);
		die();
	}else{
		//belum kasih ijin.
		if($response->data->c!=null){
			$_SESSION['c'] = $response->data->c;
		}
		$view->assign("login_twitter_url",$response->data->link);
	}
	
	print $view->toString("axisBerkah/landing.html");
}
?>
