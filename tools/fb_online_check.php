<?php
/**
 * AXIS Twitter Bot
 * bot for checking users who idle for more than n minutes (specified in config.inc.php)
 * we set the user as offline if idle more than threshold..
 */
include_once "common.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

$db = new SQLData();
while(1){
	//retrieve jobs
	$db->open(0);
	$sql = "SELECT UNIX_TIMESTAMP(NOW())-{$CONFIG['IDLE_TOLERANCE']} as ts";
	$ctime = $db->fetch($sql);
	print "threshold : ".$ctime['ts'].PHP_EOL;
	//retrieve 
	$sql = "SELECT id,user_id FROM axis.tbl_online WHERE is_online = 1 AND last_ping_ts < ({$ctime['ts']});";
	$expired = $db->fetch($sql,1);
	if(sizeof($expired)>0){
		foreach($expired as $exp){
			$sql = "UPDATE axis.tbl_online SET is_online = 0 
				WHERE 
				user_id = {$exp['user_id']} 
				AND
				is_online = 1
				AND 
				last_ping_ts < ({$ctime['ts']});";
	
			$q = $db->query($sql);
			
			$sql = "INSERT INTO axis.tbl_online_history 
					(user_id, flag, last_update)
					VALUES
					({$exp['user_id']}, 0, NOW())";
			print $sql.PHP_EOL;
			$q = $db->query($sql);
			if($q){
				print date("Y-m-d H:i:s")." online status checked #{$exp['user_id']}".PHP_EOL;
				print "online : 0, last_ping_ts < {$ctime['ts']}".PHP_EOL;
			}else{
				print date("Y-m-d H:i:s")." failed".PHP_EOL;
			}
		}
		
	}
	
	$db->close();
	sleep(30); //rest for a minute
}
?>