<?php
session_start();
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";

/** DEVICE TRACKING **/
	include_once "deviceTrack.php";
	/** END **/
	/** MOBILE REDIRECTION **/
		include_once  $ENGINE_PATH."Utility/Mobile_Detect.php";
		$detect = new Mobile_Detect();
		if ($detect->isMobile()) {
			header('Location: http://m.tangkapberkahaxis.com');
		}
	/** END **/ 
	
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

//check if user hit logout button, means he/she already registered
$isRegistered = $_SESSION['isRegister'];
// var_dump($_SESSION);exit;
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

//ini fb buat berkah
$fb2 = new FacebookHelper($req);
$fb2->init(1);
$fb2->open(0);
if($fb2->login()){
	$userId2 = $fb2->id();
	$me2 = $fb2->myself();
	$likebtn2 = $fb2->getLikeButton($CONFIG['BASE_DOMAIN']);
	$is_like2 = $fb2->isLiked($_SESSION['user_login_id']);
	$_SESSION['login'] = true;
	
}
$signed_request2 = $fb2->getSignedRequest();
$page_info2 = $signed_request2['page'];
$page_liked2 = $page_info2['liked'];

// print_r($signed_request);exit;

if($page_liked2){
	$token2 = urlencode64(serialize(array("page"=>$page_info,"is_liked"=>true)));
}

$fb2->close();
// print_r('<pre>');
// print_r($is_like);exit;

$view = new BasicView();

$isRegistered = true;
if(!$_SESSION['user_login_id']){
	if(!$isRegistered){
		$mustRegister=true;
	}	
	$view->assign("lanjutUrl",$CONFIG['FB_DOMAIN']);
	$view->assign("lanjutUrl2",$CONFIG['FB_DOMAIN_BERKAH']);
}else {
	if(!$is_like) {
		if($_SESSION['login']){
			$view->assign("lanjutUrl",$CONFIG['FB_DOMAIN']);
		}else{
			$view->assign("lanjutUrl",$CONFIG['FB_FANS_PAGE']);
		}
	}else{
		$view->assign("lanjutUrl",$CONFIG['BASE_DOMAIN']);
	}
	//berkah gak perlu ngelike dulu
	if($_SESSION['login']){
		$view->assign("lanjutUrl2",$CONFIG['FB_DOMAIN_BERKAH']);
	}else{
		$view->assign("lanjutUrl2",$CONFIG['BASE_DOMAIN_BAROKAH']);
	}
}
if($_SESSION['register']==true) $mustRegister=true;	
$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
$view->assign("fbConnect",$CONFIG['FB_DOMAIN']);
$view->assign("fbConnect2",$CONFIG['FB_DOMAIN_BERKAH']);

$view->assign("me",$me);
$view->assign("userId",$userId);
$view->assign("is_liked",$is_like);
$view->assign("page_liked",$page_liked);
$view->assign("likebtn",$likebtn);

$view->assign("me2",$me2);
$view->assign("userId2",$userId2);
$view->assign("is_liked2",$is_like2);
$view->assign("page_liked2",$page_liked2);
$view->assign("likebtn2",$likebtn2);


$view->assign("appId",$FB['appID']);
$view->assign("appId2",$FB_BERKAH['appID']);
$view->assign("access_token",$access_token);
$view->assign("mustRegister",$mustRegister);
$view->assign("service_url",$CONFIG['SERVICE_URL']);
$view->assign("userStat",$fb->userStat());
$view->assign("friends",$fb->get_friends($_SESSION['user_id']));
$view->assign("liked_token",$token);
$view->assign("liked_token2",$token2);

$_SESSION['register'] =$mustRegister;

if($_SESSION['register']==true) {
$view->assign("registerUrl",$CONFIG['REGISTRATION_DOMAIN']);
$view->assign("registerUrl2",$CONFIG['REGISTRATION_DOMAIN_BAROKAH']);
}

//random landing Page
$random = rand(0,100);
if ($random%2 == 0){
	$landingHtml = "landing_berkah_barokah";	
}else{
	$landingHtml = "landing_berkah_barokah2";
}
$_SESSION['isRegister'] = $isRegistered;
print $view->toString("axisBerkah/".$landingHtml.".html");

?>
