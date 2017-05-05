<?php
/**
 * AXIS Twitter Bot
 * the bot will retrieve user's past 1000 tweets.
 * the bot also will scan for bad words and flag it right away.
 */
include_once "common.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

$db = new SQLData();
while(1){
	//retrieve jobs
	$sql = "SELECT * FROM axis.job_facebook WHERE n_status < 2 ORDER BY id ASC LIMIT 1";
	$db->open(0);
	$job = $db->fetch($sql);
	$job_id = $job['id'];
	$fb_id = $job['fb_id'];
	if($job_id>0){
		$access_token = $job['access_token'];
		print "Proccessing #{$job['id']} - {$fb_id}".PHP_EOL;
		print "using access token : {$access_token}".PHP_EOL;
		$url = "https://graph.facebook.com/{$fb_id}/friends";
		print "Call : ".$url.PHP_EOL;
		$response = curlGet($url,array("access_token"=>$access_token));
		$sql = "UPDATE axis.job_facebook SET n_status=1 WHERE id={$job_id}";
		$db->query($sql);
		while(1){
			
			if(strlen($response)>0){
				$o = json_decode($response,true);
				
				if(isset($o['data'])){
					if(sizeof($o['data'])>0){
						$n_friends = 0;
						foreach($o['data'] as $friends){
							$friend_name = mysql_escape_string($friends['name']);
							$friend_id = mysql_escape_string($friends['id']);
							$sql = "INSERT INTO axis.tbl_fb_friends 
									(fb_id, friend_name, friend_id, retrieve_date)
									VALUES
									({$fb_id}, '{$friend_name}', '{$friend_id}', NOW())";
							$q = $db->query($sql);
							if(mysql_insert_id()>0){
								$n_friends++;
							}
						}	
						print $n_friends." friends added".PHP_EOL;
						$sql = "UPDATE axis.job_facebook 
								SET last_process = NOW(),friend_retrieved=friend_retrieved+{$n_friends} 
								WHERE id={$job_id}";
						$db->query($sql);
					}else{
						//we should break from here
						$sql = "UPDATE axis.job_facebook SET n_status=2 WHERE id={$job_id}";
						$db->query($sql);
						print "finished".PHP_EOL;
						break;
					}
				}else{
					$sql = "UPDATE axis.job_facebook SET n_status=2 WHERE id={$job_id}";
					$db->query($sql);
					break;
				}
				if(isset($o['paging']['next'])){
					$response = curlGet($o['paging']['next'],null);
				}else{
					break;
				}
			}else{
				print "no response".PHP_EOL;
				$sql = "UPDATE axis.job_facebook SET n_status=2 WHERE id={$job_id}";
				$db->query($sql);
				break;
			}
		}
	}
	$db->close();
sleep(1);
}
?>