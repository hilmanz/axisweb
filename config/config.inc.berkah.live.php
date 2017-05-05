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
define('BERKAHAPPLICATION','axisBerkah');
define('MOBILEAPPLICATION','mobile');
define('MOBILEAPPLICATIONBERKAH','mobileBerkah');
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
$CONFIG['MOBILE_DOMAIN'] = "http://m.tangkapberkahaxis.com/";
$CONFIG['FB_DOMAIN'] = "https://www.tangkapberkahaxis.com/fb/connect.php";
$CONFIG['FB_FANS_PAGE'] = "https://www.facebook.com/AXISgsm/app_373478669386709";
$CONFIG['UPDATE_STATUS_DOMAIN'] = "https://www.tangkapberkahaxis.com/fb/updatestatus.php";
$CONFIG['REGISTRATION_DOMAIN'] = "https://www.tangkapberkahaxis.com/fb/register.php";
$CONFIG['IDLE_TOLERANCE'] = 180;

//MOBILE SITE
$CONFIG['FB_DOMAIN_MOBILE'] = "http://m.tangkapberkahaxis.com/fb/mobile.php";
$CONFIG['BASE_DOMAIN_MOBILE'] = "http://m.tangkapberkahaxis.com/";
$CONFIG['BERKAH_LOGIN_MOBILE'] = "http://m.tangkapberkahaxis.com/login2.php";
$CONFIG['BERKAH_DOMAIN_MOBILE'] = "http://m.tangkapberkahaxis.com/index2.php";
$CONFIG['LANDING_MOBILE'] = "http://m.tangkapberkahaxis.com/landing.php";
$CONFIG['BERKAH_LOGIN_GAGAL'] = "http://m.tangkapberkahaxis.com/msg.php";
$CONFIG['BOTH_LANDING_PAGE'] = "http://m.tangkapberkahaxis.com/mobile_landing.php";


//BERKAH SITE
$CONFIG['BAROKAH_SOURCES'] = "https://www.tangkapberkahaxis.com/berkah/";
$CONFIG['BERKAH_LANDING'] = "https://www.tangkapberkahaxis.com/berkah/landing.php";
$CONFIG['LANDING_BERKAH_DOMAIN'] ="https://www.tangkapberkahaxis.com/berkah/landing.php";
$CONFIG['BASE_DOMAIN_BAROKAH'] = "https://www.tangkapberkahaxis.com/berkah/";
$CONFIG['REGISTRATION_DOMAIN_BAROKAH'] = "https://www.tangkapberkahaxis.com/berkah/register.php";
$CONFIG['FB_FANS_PAGE_BERKAH'] =  "https://www.facebook.com/AXISgsm/app_267918769990528";
$CONFIG['FB_DOMAIN_BERKAH'] = "https://www.tangkapberkahaxis.com/fb/connect_berkah.php";
$CONFIG['FB_CONNECT_BERKAH'] = "https://www.tangkapberkahaxis.com/fb/connect_berkah.php";
$CONFIG['BASE_DOMAIN_CONNECT'] = "https://www.tangkapberkahaxis.com/";


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




$TWITTER['CONSUMER_KEY'] = "yUugWrOmIbRQ0WUQ3ES1zA";
$TWITTER['CONSUMER_SECRET'] ="5FKwuo0lcgEKkswtEWIxSKQL0sCnbPL23NDnypPOs"; 
$TWITTER['LOGIN_CALLBACK'] = "https://www.tangkapberkahaxis.com/twitter.php";
$FB['appID'] = "373478669386709";
$FB['appSecret'] = "eab7d09fc26fdec6ae6fd932a6bceb51";

//fb appnya berkah
$FB_BERKAH['appID'] = "267918769990528";
$FB_BERKAH['appSecret'] = "1cbe924be0f899fee6c221256e716034";

//MCP parameters
$MCP['api_url'] = "http://103.3.221.154:10000/mt/mvas/send/";
// $MCP['api_url'] = $CONFIG['SERVICE_URL']."callback.php";
$MCP['cpid'] = "fbapps";
$MCP['cppwd'] = "QxNM0q";
$MCP['verify_ccode'] = "124SMSPUSH0";
$MCP['verify_scode'] = "DKSFESFB";
?>
