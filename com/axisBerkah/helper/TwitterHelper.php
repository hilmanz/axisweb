<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
class TwitterHelper extends Application{
	var $fb;
	var $user_id;
	var $me;
	var $_access_token;
	/**
	 * untuk mendapatkan jumlah twitter yang mengandung kata2 kotor.
	 * @param $twitter_id
	 * @return integer
	 */
	function count_bad_tweets($twitter_id){
		if(strlen($twitter_id)>0){
			$this->open(0);
			$sql = "SELECT COUNT(id) as total FROM axis.tbl_twitter 
					WHERE twitter_id='{$twitter_id}' AND flag=1";
			$rs = $this->fetch($sql);
			$this->close();
			return $rs['total'];
		}
	}
	/**
	 * untuk mendapatkan jumlah twitter 
	 * yang mengandung kata2 kotor 
	 * yang telah di hapus.
	 * @param $twitter_id
	 * @return integer
	 */
	function count_flagged_tweets($twitter_id){
		if(strlen($twitter_id)>0){
			$this->open(0);
			$sql = "SELECT COUNT(id) as total FROM axis.tbl_twitter 
					WHERE twitter_id='{$twitter_id}' 
					AND flag=1 AND n_status=1";
			$rs = $this->fetch($sql);
			$this->close();
			return $rs['total'];
		}
	}
	/**
	 * hapus tweet dari twitter.
	 * @param $user_id
	 * @param $twitter_id
	 * @param $feed_id_str
	 * @param boolean true if success
	 */
	function delete_tweets_from_twitter($user_id,$twitter_id,$feed_id_str){
		global $CONFIG;
		
		$access_token = get_access_token($user_id,$CONFIG['SERVICE_KEY'],'twaxis',true);
		$url = "{$CONFIG['BASE_DOMAIN_CONNECT']}{$CONFIG['SERVICE_URL']}?service=twitter&m=remove_tweet&id={$feed_id_str}&access_token={$access_token}";
		
		$resp = json_decode(file_get_contents($url),1);
		if($response['status']==1){
			return true;
		}
	}
	
}
?>