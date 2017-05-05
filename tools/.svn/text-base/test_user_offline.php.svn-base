<?php
/**
 * script for creating dummy users based on facebook friend ids
 */
include_once "common.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";


$db = new SQLData();
$user_id = intval($argv[1]);
$db->open(0);
$sql = "UPDATE axis.tbl_online 
				SET 
				last_ping = NOW(),
				last_ping_ts = UNIX_TIMESTAMP(NOW()),
				is_online=0
				WHERE user_id={$user_id};";
$q = $db->query($sql);

$sql = "INSERT INTO axis.tbl_online_history 
				(user_id, flag, last_update)
				VALUES
				({$user_id}, 0, NOW())";
		
	
$q = $db->query($sql);

$db->close();

if($q){
	print "set #{$user_id} as offline".PHP_EOL;
}else{
	print "failed".PHP_EOL;
}
?>