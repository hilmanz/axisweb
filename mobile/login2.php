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


if($_REQUEST['oauth_verifier']!=null){
	$url = "{$CONFIG['BASE_DOMAIN_CONNECT']}{$service_url}?service=twitter&m=authorize&c={$_SESSION['c']}&oauth_token={$_REQUEST['oauth_token']}&oauth_verifier={$_REQUEST['oauth_verifier']}&access_token={$access_token}&nouser=1";
	
	$resp = file_get_contents($url);
	$result = json_decode($resp,true);
	
	if($result['status']==1){
		$twitter_id = $result['data']['twitter_id'];
		$_SESSION['twit_id'] = $twitter_id;
		$db = new Application();
		$db->open(0);
		$sql = "SELECT user_id FROM axis.tbl_user_twitter WHERE twitter_id='{$twitter_id}' LIMIT 1";
		$rs = $db->fetch($sql);
		$_SESSION['user_id']=$rs['user_id'];
		$_SESSION['login'] = true;
		$db->close();
		sendRedirect($CONFIG['BERKAH_DOMAIN_MOBILE']);
		die();
	}else{
		sendRedirect($CONFIG['BERKAH_LOGIN_MOBILE']."?error=1");
		die();
	}
}
	//$token = urlencode64(serialize(array("user_id"=>$_SESSION['user_id'])));
	//check apakah sudah kasih permission ke twitterAPP axis.
	$call = $CONFIG['BASE_DOMAIN_CONNECT'].$service_url."?service=twitter&m=login&access_token={$access_token}&nouser=1";
	$response = json_decode(file_get_contents($call));
	
	if($response->status==1){
		//sudah kasih ijin
		$_SESSION['login'] = true;
		$_SESSION['twit_id'] = $response->data->user;
		/*set Log Activity*/
		global $APP_PATH;
		require_once $APP_PATH.BERKAHAPPLICATION."/helper/activityReportHelper.php";
		$track = new activityReportHelper();
		$track->log('login',$_SESSION['user_id'],TRUE);
		
		sendRedirect($CONFIG['BERKAH_DOMAIN_MOBILE']);
		die();
	}else{
		//belum kasih ijin.
		if($response->data->c!=null){
			$_SESSION['c'] = $response->data->c;
		}
		$view->assign("login_twitter_url",$response->data->link);
	}
	
	$msg = "Untuk login via mobile, kamu harus registrasi di www.tangkapberkahaxis.com terlebih dahulu.";
	$view->assign("msg",$msg);
	print $view->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION .'/mobile_login.html');

?>
