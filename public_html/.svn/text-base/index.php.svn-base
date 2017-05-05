<?php
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

include_once $APP_PATH."axis/App.php";
include_once $APP_PATH."Interaction/Interaction.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName('smacapp');
$logger->setDirectory('../logs/');
$view = new BasicView();
$app = new App(&$req);
$app->main();

print $app;
die();
?>
