<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";

$view = new BasicView();

$view->assign("barokahUrl",$CONFIG['BAROKAH_SOURCES']);
$view->assign("barokahLink",$CONFIG['FB_DOMAIN_MOBILE']);
$view->assign("berkahLink",$CONFIG['BERKAH_LOGIN_MOBILE']);

print $view->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION .'/mobile_landing.html');

?>
