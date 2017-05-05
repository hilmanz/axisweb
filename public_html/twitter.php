<?php
include_once "common.php";
session_start();
if($_REQUEST['user_id']!=null){
	$_SESSION['user_id'] = $_REQUEST['user_id'];
}
$service_url = $CONFIG['SERVICE_URL'];

$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY'],'twaxis',true);

if($_SESSION['user_id']!=null){
	//versi website
	if($_REQUEST['oauth_verifier']!=null){
		$url = "{$CONFIG['BASE_DOMAIN_CONNECT']}{$service_url}?service=twitter&m=authorize&c={$_SESSION['c']}&oauth_token={$_REQUEST['oauth_token']}&oauth_verifier={$_REQUEST['oauth_verifier']}&access_token={$access_token}";
		$resp = file_get_contents($url);
		$result = json_decode($resp,true);
		//$this->open(0);
		//$sql = "SELECT twitter_id FROM axis.tbl_user_twitter WHERE user_id={$_SESSION['user_id']} LIMIT 1";
		//$rs = $this->fetch($sql);
		//$_SESSION['twit_id']=$rs['twitter_id'];
		//$this->close();
		if($result['status']==1){
			sendRedirect($CONFIG['REGISTRATION_DOMAIN_BAROKAH']."?fb_id={$_SESSION['user_login_id']}&t={$_SESSION['t']}");
		}else{
			sendRedirect($CONFIG['REGISTRATION_DOMAIN_BAROKAH']."?error=1");
			die();
		}
	}else{
		sendRedirect($CONFIG['REGISTRATION_DOMAIN_BAROKAH']."?error=1");
		die();
	}
}else{
	//MOBILE
	//just redirect to mobile.
	sendRedirect($CONFIG['BERKAH_LOGIN_MOBILE']."?oauth_verifier={$_REQUEST['oauth_verifier']}&oauth_token={$_REQUEST['oauth_token']}");
	die();
	/*
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
			sendRedirect($CONFIG['BERKAH_DOMAIN_MOBILE']."?c={$_SESSION['c']}");
		}else{
			sendRedirect($CONFIG['BERKAH_LOGIN_MOBILE']."?error=1");
			die();
		}
	}else{
		sendRedirect($CONFIG['BERKAH_LOGIN_MOBILE']."?error=1");
		die();
	}
	*/
}
?>
