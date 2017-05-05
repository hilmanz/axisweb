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
				is_online=1
				WHERE user_id={$user_id};";
$q = $db->query($sql);


if($q){
	//put to log that the user is online
	$sql = "SELECT id,user_id,flag FROM axis.tbl_online_history
			WHERE id IN (SELECT MAX(id) as id FROM 
			axis.tbl_online_history 
			WHERE user_id={$user_id})";
	$check = $db->fetch($sql);
	
	if($check['user_id']==null||$check['flag']==0){
		$sql = "INSERT INTO axis.tbl_online_history 
				(user_id, flag, last_update)
				VALUES
				({$user_id}, 1, NOW())";
		
		$q = $db->query($sql);
	}
	print "set #{$user_id} as online".PHP_EOL;
}else{
	print "failed".PHP_EOL;
}
$db->close();
?>