<?php
/**
 * AXIS Twitter Bot
 * the bot will retrieve user's past 1000 tweets.
 * the bot also will scan for bad words and flag it right away.
 */
include_once "common.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";


$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 	$TWITTER['CONSUMER_KEY'],
  'consumer_secret' =>	$TWITTER['CONSUMER_SECRET'],
));

$count = 200;

$db = new SQLData();
while(1){
	$db->open(0);
	$sql = "SELECT * FROM axis.job_twitter WHERE n_status < 2 ORDER BY id ASC LIMIT 1";
	$rs = $db->fetch($sql);
	
	if($rs['id']!=null){
		print "retrieving tweet for {$rs['twitter_id']}".PHP_EOL;
		print "Tweet retrieved : {$rs['tweet_retrieved']}".PHP_EOL;
		$max_id = "".$rs['max_id'];
		$sql = "UPDATE axis.job_twitter SET n_status=1 WHERE id = {$rs['id']}";
				$db->query($sql);
		if($rs['tweet_retrieved']<1000){
			print "lets go".PHP_EOL;
			if($max_id==null){
				print "start from recent".PHP_EOL;
			}
	
			$tmhOAuth->config['user_token']  = $rs['token'];
			$tmhOAuth->config['user_secret'] = $rs['secret'];
			
			
			//verfiy the user credential first
			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
			
			if($code==200){
				print "credential ok !".PHP_EOL;
				//retrieve the tweets.
				if($max_id!=null){
					$params = array('max_id'=>$max_id,'count'=>$count);
				}else{
					$params = array('count'=>$count);
				}
				print "max_id : {$max_id}".PHP_EOL;
				$code = $tmhOAuth->request('GET', $tmhOAuth->url('1/statuses/user_timeline'),$params);
				print "Response : ".$code.PHP_EOL;
				$d = json_decode($tmhOAuth->response['response'],true);
				print "fetched from twitter : ".sizeof($d).PHP_EOL;
				print $tmhOAuth->response['response'].PHP_EOL;
				foreach($d as $feed){
					print $feed['id_str'].PHP_EOL;
					if(strlen($feed['id'])>5){
						$created_at = date("Y-m-d H:i:s",strtotime($feed['created_at']));
						$txt = mysql_escape_string($feed['text']);
						$sql = "INSERT IGNORE INTO axis.tbl_twitter 
								(twitter_id, feed_id, feed_id_str, created_at_str, created_at, txt, inserted_date, flag)
								VALUES
								('{$rs['twitter_id']}', '{$feed['id']}', '{$feed['id_str']}', '{$feed['created_at']}', 
								'{$created_at}', '{$txt}', NOW(), 0)";
						$q = $db->query($sql);
						
						$new_max_id = "".$feed['id'];
						
					}
				}
				
				$n_feeds = sizeof($d);
				if($max_id!=$new_max_id){
					$max_id = "".$new_max_id;
					
					$sql = "UPDATE axis.job_twitter SET max_id='{$max_id}',tweet_retrieved = tweet_retrieved + {$n_feeds} WHERE id={$rs['id']}";
					
					$db->query($sql);
				}else{
					//it means that there's no more tweets available
					print "no more tweets available".PHP_EOL;
					$sql = "UPDATE axis.job_twitter SET n_status=2 WHERE id = {$rs['id']}";
					$db->query($sql);
				}
				//scan for bad words
				print "Scan for badwords".PHP_EOL;
				$sql = "SELECT * FROM axis.tbl_bad_words";
				$bad = $db->fetch($sql,1);
				$rs['twitter_id'] = mysql_escape_string($rs['twitter_id']);
				$sql = "SELECT id,txt FROM axis.tbl_twitter 
						WHERE twitter_id='{$rs['twitter_id']}' AND flag=0";
				$q = mysql_query($sql,$db->conn);
				while($f = mysql_fetch_assoc($q)){
					$wordlist = extract_words($f['txt']);
					$strWord = "";
					foreach($wordlist as $n=>$w){
						if($n>0){
							$strWord.=",";
						}
						$strWord.="'".mysql_escape_string(trim($w))."'";
					}
					$sql = "SELECT keyword FROM axis.tbl_bad_words WHERE keyword IN ($strWord)";
					//print $sql.PHP_EOL;
					$check = $db->fetch($sql,1);
					var_dump($check);
					if(sizeof($check)>0){
						$sql = "UPDATE axis.tbl_twitter SET flag=1 
						WHERE id={$f['id']} AND twitter_id='{$rs['twitter_id']}' AND flag=0";
						$q = $db->query($sql);
						print $sql.PHP_EOL;
						print "------------".PHP_EOL;
					}
				}
				//-->
			}else{
				print "invalid user, so stop processing".PHP_EOL;
				$sql = "UPDATE axis.job_twitter SET n_status=2 WHERE id = {$rs['id']}";
				$db->query($sql);
			}
			
			
		}else{
			$sql = "UPDATE axis.job_twitter SET n_status=2 WHERE id = {$rs['id']}";
			$db->query($sql);
			print "finished".PHP_EOL;
		}
		print "next iteration".PHP_EOL;
	}
	$db->close();
	sleep(10);
}
?>