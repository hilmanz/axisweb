<?php
include_once "locale.inc.php";
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//set aplikasi yang digunakan
define('APPLICATION','axis');
define('BERKAHAPPLICATION','axisBerkah');
define('MOBILEAPPLICATION','mobile');
//set TRUE jika dalam local
$local = false;
$DEVELOPMENT_MODE = true;
$CONFIG['LOCAL_DEVELOPMENT'] = true;
//WEB APP BASE DOMAIN
$httpStat = 'https://';

$CONFIG['CLOSED_WEB'] = false;
$CONFIG['LANDING_BASE_DOMAIN'] =  $httpStat."preview.kanadigital.com/axis/public_html/landing.php";
$CONFIG['TEASER_DOMAIN'] = $httpStat."preview.kanadigital.com/axis/public_html/teaser.html";
$CONFIG['BASE_DOMAIN'] = $httpStat."preview.kanadigital.com/axis/public_html/";
$CONFIG['BASE_DOMAIN_CONNECT'] = $httpStat."preview.kanadigital.com/axis/";
$CONFIG['FB_DOMAIN'] = $httpStat."preview.kanadigital.com/axis/fb/connect.php";
$CONFIG['FB_FANS_PAGE'] = $httpStat."www.facebook.com/pages/Thorfins-Co/177086605670838?sk=app_341380259214774";
$CONFIG['UPDATE_STATUS_DOMAIN'] = $httpStat."preview.kanadigital.com/axis/fb/updatestatus.php";
$CONFIG['REGISTRATION_DOMAIN'] = $httpStat."preview.kanadigital.com/axis/fb/register.php";
$CONFIG['IDLE_TOLERANCE'] = 60;

//MOBILE SITE
$CONFIG['FB_DOMAIN_MOBILE'] = $httpStat."preview.kanadigital.com/axis/fb/mobile.php";
$CONFIG['BASE_DOMAIN_MOBILE'] = $httpStat."preview.kanadigital.com/axis/mobile/";

//BERKAH SITE
$CONFIG['BAROKAH_SOURCES'] = $httpStat."preview.kanadigital.com/axis/public_html/";
$CONFIG['BERKAH_LANDING'] = $httpStat."preview.kanadigital.com/axis/axis_berkah/landing.php";
$CONFIG['LANDING_BERKAH_DOMAIN'] = $httpStat."preview.kanadigital.com/axis/axis_berkah/landing.php";
$CONFIG['BASE_DOMAIN_BAROKAH'] = $httpStat."preview.kanadigital.com/axis/axis_berkah/";
$CONFIG['REGISTRATION_DOMAIN_BAROKAH'] = $httpStat."preview.kanadigital.com/axis/axis_berkah/register.php";

if($local){
	$CONFIG['DATABASE'][0]['HOST'] 	= "202.80.113.52";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "coppermine";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "axis";
}else{
	$CONFIG['DATABASE'][0]['HOST'] 				= "202.80.113.52";
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

$CONFIG['EMAIL3PARTY'][0] = "irvan@kana.co.id";
$CONFIG['EMAIL3PARTY'][1] = "irvan@sitti.co.id";

$TWITTER['CONSUMER_KEY'] = "DYwf37RJWNScT8EnSjNgUA";
$TWITTER['CONSUMER_SECRET'] ="eSiP0um4QIwWGSY8f3etZrMGjx08AIQppSWEYDpSks"; 
$TWITTER['LOGIN_CALLBACK'] = "http://preview.kanadigital.com/axis/public_html/twitter.php";
$FB['appID'] = "341380259214774";
$FB['appSecret'] = "63685e1fd7db81fc51a04de0e2034ceb";


//MCP parameters
$MCP['api_url'] = "http://preview.kanadigital.com/axis/tools/mcp_dummy_server.php";
$MCP['cpid'] = "fbapps";
$MCP['cppwd'] = "Hz2hiq";
$MCP['verify_ccode'] = "124SMSPUSH0";
$MCP['verify_scode'] = "DKSFTESF";
?>
