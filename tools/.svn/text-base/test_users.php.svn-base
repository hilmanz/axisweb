<?php
/**
 * script for creating dummy users based on facebook friend ids
 */
include_once "common.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

$db = new SQLData();
$db->open(0);
$sql = "SELECT friend_id FROM axis.tbl_fb_friends WHERE friend_id NOT IN (SELECT fb_id FROM axis.tbl_user_fb);";
$rs = $db->fetch($sql,1);
$user_id = 30;
foreach($rs as $r){
	$user_id++;
	$sql = "INSERT IGNORE INTO axis.tbl_user_fb
			(user_id,fb_id)
			VALUES
			({$user_id},{$r['friend_id']})";
	$q = $db->query($sql);
	print $user_id.PHP_EOL;
}
$db->close();
?>