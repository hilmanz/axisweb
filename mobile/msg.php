<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";

$view = new BasicView();

$view->assign("barokahUrl",$CONFIG['BAROKAH_SOURCES']);
$view->assign("msg","Maaf kamu harus registrasi di www.tangkapberkahaxis.com terlebih dahulu.");

print $view->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION .'/gagal_login.html');

?>
