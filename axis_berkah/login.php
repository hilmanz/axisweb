<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
session_start();
if($_REQUEST['user_id']!=null){
	$_SESSION['user_id'] = $_REQUEST['user_id'];
}
if($_SESSION['user_id']==null){
	print "<a href='twitter.php?user_id='>twitter.php?user_id=[isi_user_id]</a>";
	die();
}
if($_REQUEST['r']!=null){
	$r = unserialize(urldecode64($req->getParam("r")));
	if($r['user_id']==$_SESSION['user_id']&&strlen($_SESSION['user_id'])>0){
		//nothing todo.. we are just fine
	}else{
		sendRedirect($CONFIG['FB_FANS_PAGE_BERKAH']);
		die();
	}
}else{
	sendRedirect($CONFIG['FB_FANS_PAGE_BERKAH']);
	die();
}
//everything is good

$service_url = $CONFIG['SERVICE_URL'];
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY'],'twaxis',true);
if($_REQUEST['oauth_verifier']!=null){
	$url = "{$service_url}?service=twitter&m=authorize&c={$_SESSION['c']}&oauth_token={$_REQUEST['oauth_token']}&oauth_verifier={$_REQUEST['oauth_verifier']}&access_token={$access_token}";
	$resp = file_get_contents($url);
	print $resp;
}

/*
if($_REQUEST['delete']==1){
	$url = "{$service_url}?service=twitter&m=remove_tweet&id={$_REQUEST['id']}&access_token={$access_token}";
	$resp = file_get_contents($url);
	print $resp."<br/>";
}
*/
?>