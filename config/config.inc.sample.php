<?php
include_once "locale.inc.php";
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";
error_reporting(0);
//set aplikasi yang digunakan
define('APPLICATION','axis');
define('MOBILEAPPLICATION','mobile');
//set TRUE jika dalam local
$local = false;
$DEVELOPMENT_MODE = true;
$CONFIG['LOCAL_DEVELOPMENT'] = false;
//WEB APP BASE DOMAIN
$CONFIG['CLOSED_WEB'] = false;
$CONFIG['LANDING_BASE_DOMAIN'] = "https://www.tangkapberkahaxis.com/landing.php";
$CONFIG['TEASER_DOMAIN'] = "https://www.tangkapberkahaxis.com/teaser.html";
$CONFIG['BASE_DOMAIN'] = "https://www.tangkapberkahaxis.com/";
$CONFIG['BASE_DOMAIN_SECURE'] = "https://www.tangkapberkahaxis.com/";
$CONFIG['MOBILE_DOMAIN'] = "https://m.tangkapberkahaxis.com/";
$CONFIG['FB_DOMAIN'] = "https://www.tangkapberkahaxis.com/fb/connect.php";
$CONFIG['FB_FANS_PAGE'] = "https://www.facebook.com/AXISgsm/app_373478669386709";
$CONFIG['UPDATE_STATUS_DOMAIN'] = "https://www.tangkapberkahaxis.com/fb/updatestatus.php";
$CONFIG['REGISTRATION_DOMAIN'] = "https://www.tangkapberkahaxis.com/fb/register.php";
$CONFIG['IDLE_TOLERANCE'] = 180;

//MOBILE SITE
$CONFIG['FB_DOMAIN_MOBILE'] = "https://m.tangkapberkahaxis.com/fb/mobile.php";
$CONFIG['BASE_DOMAIN_MOBILE'] = "https://m.tangkapberkahaxis.com/";


if($local){
	$CONFIG['DATABASE'][0]['HOST'] 		= "202.80.113.123";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "coppermine";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "axis";
}else{
	$CONFIG['DATABASE'][0]['HOST'] 		= "202.80.113.123";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "coppermine";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "axis";
	
	
	//koneksi ke database code 
    $CONFIG['DATABASE'][1]['HOST']          = "localhost";
    $CONFIG['DATABASE'][1]['USERNAME']      = "root";
    $CONFIG['DATABASE'][1]['PASSWORD']      = "root";
    $CONFIG['DATABASE'][1]['DATABASE']      = "marlboro_code";
}

$CONFIG['SERVICE_URL'] = "service/";

/* DATETIME SET */
$timeZone = 'Asia/Jakarta';
date_default_timezone_set($timeZone);


$SMAC_SECRET = sha1("harveyspecterssuits");
$SMAC_HASH = sha1("mikerosssuits");

$CONFIG['SERVICE_KEY'] = sha1("kanaaxisbates");


/**
 * Email settings
 */
$CONFIG['EMAIL_FROM_DEFAULT'] = "noreply@tangkapberkahaxis.com";
$CONFIG['EMAIL_SMTP_HOST'] = "localhost";
$CONFIG['EMAIL_SMTP_PORT'] = 25;
$CONFIG['EMAIL_SMTP_USER'] = "";
$CONFIG['EMAIL_SMTP_PASSWORD'] = "";
$CONFIG['EMAIL_SMTP_SSL'] = 0;


$TWITTER['CONSUMER_KEY'] = "DYwf37RJWNScT8EnSjNgUA";
$TWITTER['CONSUMER_SECRET'] ="eSiP0um4QIwWGSY8f3etZrMGjx08AIQppSWEYDpSks"; 
$TWITTER['LOGIN_CALLBACK'] = "http://www.tangkapberkahaxis.com/twitter.php";
$FB['appID'] = "373478669386709";
$FB['appSecret'] = "eab7d09fc26fdec6ae6fd932a6bceb51";

//MCP parameters
$MCP['api_url'] = "http://103.3.221.154:10000/mt/mvas/send/";
// $MCP['api_url'] = $CONFIG['SERVICE_URL']."callback.php";
$MCP['cpid'] = "fbapps";
$MCP['cppwd'] = "QxNM0q";
$MCP['verify_ccode'] = "124SMSPUSH0";
$MCP['verify_scode'] = "DKSFESFB";
?>
