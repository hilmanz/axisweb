<?php
include_once "common.php";
include_once "../config/config.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH . APPLICATION . '/App.php';
include_once $APP_PATH . APPLICATION . '/helper/pdfHelper.php';

$view = new BasicView();
$pdf = new pdfHelper();
if($pdf->addJob(226,'1,2','test_226','2011-09-22','2011-09-24','global')){
	print "job added".PHP_EOL;
}else{
	print "job failed".PHP_EOL;
}
?>