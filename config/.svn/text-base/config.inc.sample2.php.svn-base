<?php
include_once "locale.inc.php";
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//set aplikasi yang digunakan
define('APPLICATION','axis');

//set TRUE jika dalam local
$local = false;
$DEVELOPMENT_MODE = true;
$CONFIG['LOCAL_DEVELOPMENT'] = true;
//WEB APP BASE DOMAIN
$CONFIG['BASE_DOMAIN'] = "localhost";

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

$CONFIG['SERVICE_URL'] = "http://localhost/axis/axis/trunk/web/service/";

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
$TWITTER['LOGIN_CALLBACK'] = "http://localhost/axis/axis/trunk/web/public_html/twitter.php";


$FB['appID'] = "341380259214774";
$FB['appSecret'] = "63685e1fd7db81fc51a04de0e2034ceb";
$FB['liked_page_url'] = "http://localhost/axis/axis/trunk/web/fb/";

$CONFIG['IDLE_TOLERANCE'] = 300;// in seconds

?>