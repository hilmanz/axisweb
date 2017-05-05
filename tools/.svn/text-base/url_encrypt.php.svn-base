<?php
include_once "common.php";
$params = $_REQUEST;
if($params['subdomain']==null){
	if($_REQUEST['subdomain']!=NULL){
		$params['subdomain'] = $_REQUEST['subdomain'];
	}else{
		$params['subdomain'] = $_SESSION['subdomain'];
	}
}else{
	$_SESSION['subdomain'] = $params['subdomain'];
}
$params=array("subdomain"=>"foo","page"=>"keyopinionleader");
$str = http_build_query($params);
print "req=".urlencode64($str);
?>