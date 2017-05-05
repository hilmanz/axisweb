<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
class FacebookHelper extends Application{
	var $fb;
	var $user_id;
	var $me;
	var $_access_token;
	function init($settings=0){
		global $FB,$FB_BERKAH;
		if($settings==0){
			//barokah app
			$this->fb = new Facebook(array(
			  'appId'  => $FB['appID'],
			  'secret' => $FB['appSecret'],
			));
		}else{
			//berkah app
			$this->fb = new Facebook(array(
			  'appId'  => $FB_BERKAH['appID'],
			  'secret' => $FB_BERKAH['appSecret'],
			));
		}
			
			// print_r('<pre>'); print_r($_SESSION);		exit;
			if($this->fb->getUser()==0) $_SESSION=null;
			elseif($this->fb->getUser()!=0 && !$_SESSION['login']) $_SESSION['login']=false; 
			else $_SESSION['login'] = true;
		
	
		//setting up facebook user_id
		if($_SESSION['user_login_id']==null){
			try{
				$_SESSION['user_login_id'] = $this->fb->getUser();
			}catch(Exception $e){}
		}
		$this->id($_SESSION['user_login_id']);
		
		//facebook /me
		if($_SESSION['user_me']==null){
			try{
				$me = $this->fb->api('/me');
				$_SESSION['user_me'] = $me;
			}catch(Exception $e){}
		}
		
		$this->myself($_SESSION['user_me']);
		
		//facebook access token
		if($access_token==null){
			try{
				$access_token = $this->fb->getAccessToken();
				$_SESSION['fb_access_token'] = $access_token;
			}catch(Exception $e){}
		}
		$this->access_token($_SESSION['fb_access_token']);

		
	}
	function getSignedRequest(){
		return $this->fb->getSignedRequest();
	}
	function authorized($flag=null){
		if($flag!=null){
			$_SESSION['fb_authorized'] = $flag;
		}
		
		if($_SESSION['fb_authorized']==1){
			return true;
		}
	}
	function login(){
		if($this->id()!=null){
			//make sure that the user has been linked into 
			
			if(!$this->authorized()){
			
				if($this->link_user_to_fb($this->id())){
					// print_r('<pre>');print_r($_SESSION);exit;
					return true;
				}
			}
			return true;
		}		
	}
	function offline_login(){
		
	}
	function link_user_to_fb($fb_id,$reconnect=false){
			
		if(strlen($fb_id)>0){
	
			//cek uda pernah ada di web blom data nya
			$checkUser = $this->checkUserDataInWeb($fb_id);
			
			if($checkUser['total']<=0) {
				$user_id = $this->addUserIDforRegistration($fb_id);
					
			}else{
				$user_id = $checkUser['user_id'];
			}
				
				// if($user_id==0) return false;
				
				
			
				//cek uda pernah register blom			
				$checkUserRegistered = $this->checkUserhasBeenRgister($fb_id);
				//klo blom register true
				
				if($checkUserRegistered['total']>=1)
				{
					$_SESSION['register'] = true; // bikin false di sisi setelah register
				}else{
				//klo uda register false
					$_SESSION['register'] = false; 
				}
				
			$reconnect = true;				
	
			$_SESSION['user_id'] =  $user_id;
			$sql = "INSERT INTO axis.tbl_user_fb
					(user_id,fb_id,authorized_date)
					VALUES
					({$user_id},{$fb_id},NOW())
					ON DUPLICATE KEY UPDATE
					fb_id = VALUES(fb_id),
					authorized_date = VALUES(authorized_date)";
		
			if($reconnect) $this->open(0);
			$q = $this->query($sql);
		
			if($q){
			
					$this->create_seating($user_id);
					$this->create_online($user_id);
					$this->scan_friendlist($user_id,$fb_id);
					$this->authorized(1);
					$this->ping($user_id);
				return true;
			}
			if($reconnect) $this->close();
		}
	}
	function create_seating($user_id,$reconnect=false){
		$sql = "INSERT IGNORE INTO axis.tbl_seatings(user_id)
				VALUES({$user_id})";
				
		$q = $this->query($sql,$reconnect);
		
	
	}
	
	function create_online($user_id,$reconnect=false){
	
		$sql = "INSERT IGNORE INTO axis.tbl_online(user_id,last_ping,last_ping_ts,is_online)
				VALUES({$user_id},NOW(),".time().",1)";
				
		$q = $this->query($sql,$reconnect);
		
	}
	/**
	 * add friendlist scan job
	 * @param $user_id
	 * @param $fb_id
	 * @param $reconnect
	 */
	function scan_friendlist($user_id,$fb_id,$reconnect=false){
		$access_token = mysql_escape_string($this->access_token());
		$fb_id = mysql_escape_string($fb_id);
		$sql = "INSERT INTO axis.job_facebook 
				(fb_id, access_token,friend_retrieved, submit_date, last_process, n_status)
				VALUES
				({$fb_id}, '{$access_token}',0, NOW(), '0000-00-00', 0)";
		if($reconnect) $this->open(0);
		$q = $this->query($sql);
		if($reconnect) $this->close();
		
		return $q;
	}
	
	function add_friend($fb_id,$friend_name,$friend_id,$reconnect = false){
		$sql = "INSERT IGNORE INTO axis.tbl_fb_friends 
				(fb_id, friend_name, friend_id, retrieve_date)
				VALUES
				({$fb_id}, '{$friend_name}', {$friend_id}, NOW())";
		if($reconnect) $this->open(0);
		$q = $this->query($sql);
		if($reconnect) $this->close();
		return $q;
	}
	function flag_for_like($fb_id,$reconnect=false){
		
		if($fb_id>0){
			$sql = "INSERT IGNORE INTO axis.tbl_fb_like 
					(fb_id, is_like, last_update)
					VALUES
					({$fb_id}, 1, NOW())
					ON DUPLICATE KEY UPDATE 
					is_like=VALUES(is_like) ,
					last_update = NOW()
					";
			
			if($reconnect) $this->open(0);
			$q = $this->query($sql);
			if($reconnect) $this->close();
			
			return $q;
		}
	}
	
	function flag_for_unlike($fb_id,$reconnect=false){
		
		if($fb_id>0){
			$sql = "INSERT INTO axis.tbl_fb_like 
					(fb_id, is_like, last_update)
					VALUES
					({$fb_id}, 0, NOW()) 
					ON DUPLICATE KEY UPDATE 
					is_like=VALUES(is_like) ,
					last_update = NOW()					
					";
			
			if($reconnect) $this->open(0);
			$q = $this->query($sql);
			if($reconnect) $this->close();
			
			return $q;
		}
	}
	
	function getLikeButton($url){
		global $FB;
		return '<div class="fb-like" data-href="'.$url.'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="lucida grande"></div>';
	}
	function getLoginButton(){}
	function getLoginURL(){
		$params = array('scope' => 'email,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships');
		return $this->fb->getLoginUrl($params);
	}
	function isLiked($fb_id,$reconnect=false){
		// print_r($user_id);
		$fb_id = mysql_escape_string($fb_id);
		$sql = "SELECT fb_id, is_like
				FROM 
				axis.tbl_fb_like
				WHERE fb_id={$fb_id}
				LIMIT 1";
		
		if($reconnect) $this->open(0);
		$fetch = $this->fetch($sql);
		if($reconnect) $this->close();
		
		if($fetch['fb_id']==$fb_id&&$fetch['is_like']==1){
		// print_r($fetch);
			return true;
		}
	}
	function get_friends($user_id){
		$user_id = intval($user_id);
		$this->open(0);
		$sql = "SELECT fb_id FROM axis.tbl_user_fb WHERE user_id = {$user_id} LIMIT 1";
		$fbuser = $this->fetch($sql);
		$fb_id = $fbuser['fb_id'];
		$sql = "SELECT * FROM axis.tbl_fb_friends WHERE fb_id = {$fb_id} LIMIT 100";
		$rs = $this->fetch($sql,1);
		$this->close();
		
		// print_r($rs);exit;
		return $rs;
	}
	function getFBId($user_id,$reconnect=false){
		$sql = "SELECT fb_id FROM axis.tbl_user_fb WHERE user_id={$user_id} LIMIT 1;";
		$this->force_connect($reconnect);
		
		$rs = $this->fetch($sql);
		return $rs['fb_id'];
	}
	//getters/setters for data taken from facebook
	function myself($me=null){
		if($me!=null){
			$this->me = $me;
		}
		
		return $this->me;
	}
	function id($userId=null){
		if($userId!=null){
			$this->user_id = $userId;
		}
		return $this->user_id;
	}
	function access_token($token=null){
		if($token!=null){
			$this->_access_token = $token;
		}
		return $this->_access_token;
	}
	function getPingRequestToken($user_id){
		return urlencode64(serialize(array("user_id"=>$user_id,
													"last_ping"=>time(),
													"next_ping"=>time()+60*1)
		));
	}
	function ping($user_id,$reconnect=false){
		
		$sql = "UPDATE axis.tbl_online 
				SET 
				last_ping = NOW(),
				last_ping_ts = UNIX_TIMESTAMP(NOW()),
				is_online=1
				WHERE user_id={$user_id};";
		$q = $this->query($sql,$reconnect);
		
		if($q){
			//put to log that the user is online
			$sql = "SELECT id,user_id,flag FROM axis.tbl_online_history
					WHERE id IN (SELECT MAX(id) as id FROM 
					axis.tbl_online_history 
					WHERE user_id={$user_id})";
			
			$this->force_connect($reconnect);
			$check = $this->fetch($sql);
			if($check['user_id']==null||$check['flag']==0){
				
				$sql = "INSERT INTO axis.tbl_online_history 
						(user_id, flag, last_update)
						VALUES
						({$user_id}, 1, NOW())";
				
				$q = $this->query($sql,$reconnect);
				
				$this->create_seating($user_id,$reconnect);
				
			}
		}
		return $q;
	}
	function get_seatings($user_id,$reconnect=false){
		$sql = "SELECT seatings FROM axis.tbl_seatings WHERE user_id={$user_id} LIMIT 1";
		$this->force_connect($reconnect);
		$rs = $this->fetch($sql);
		
		return $rs['seatings'];
	}
	function update_seatings($user_id,$seatings,$reconnect=false){
		$seatings = mysql_escape_string(($seatings));
		$sql = "UPDATE axis.tbl_seatings SET seatings = '{$seatings}' WHERE user_id={$user_id}";
		$q = $this->query($sql,$reconnect);
		return $q;
	}
	function get_online_friends($user_id){
		$this->open(0);
		$sql = "SELECT fb_id FROM axis.tbl_user_fb WHERE user_id = {$user_id} LIMIT 1";
		$fbuser = $this->fetch($sql);
		$fb_id = $fbuser['fb_id'];
		
		$sql = "SELECT a.friend_id as fb_id,b.user_id,a.friend_name 
				FROM axis.tbl_fb_friends a
				INNER JOIN axis.tbl_user_fb b
				ON a.friend_id = b.fb_id
				INNER JOIN axis.tbl_online c
				ON b.user_id = c.user_id
				WHERE a.fb_id = {$fb_id} 
				AND c.is_online=1;";
		
		$rs = $this->fetch($sql,1);
		$this->close();
		
		return $rs;
	}
	function get_online_non_friends($user_id){
		$this->open(0);
		
		$this->close();
		return $rs;
	}
	function get_activity($user_id,$since_id){
		
		$this->open(0);
		$fb_id = $this->getFBId($user_id);
		$since_id = mysql_escape_string($since_id);
		$user_id = mysql_escape_string($user_id);
		$sql = "SELECT user_id,MAX(id) as max_id FROM axis.tbl_online_history 
				WHERE id > {$since_id} 
				AND user_id <> {$user_id} GROUP BY user_id ORDER BY max_id LIMIT 100";
		
		$rs = $this->fetch($sql,1);
		$id = "";
		if(sizeof($rs)>0){
			foreach($rs as $n=>$v){
				if($n>0){
					$id.=",";
				}
				$id.=$v['max_id'];
			}
			
			$sql = "SELECT a.id as since_id,a.user_id,b.fb_id,c.nickname as name,
					a.flag,b.special 
					FROM axis.tbl_online_history a
					INNER JOIN axis.tbl_user_fb b 
					ON a.user_id = b.user_id
					INNER JOIN axis.axis_member c
					ON b.user_id = c.id
					WHERE a.id IN ({$id}) 
					ORDER BY since_id ASC;";
			
			$people = $this->fetch($sql,1);
			
			foreach($people as $nn=>$vv){
				//check if the online people were a friends to user
				$sql = "SELECT fb_id,friend_id FROM axis.tbl_fb_friends 
						WHERE fb_id = {$fb_id} AND friend_id = {$vv['fb_id']} 
						LIMIT 1;";
				$friend = $this->fetch($sql);
				if($friend['fb_id']==$fb_id&&$friend['friend_id']==$vv['fb_id']){
					$people[$nn]['is_friend']=1;
				}else{
					$people[$nn]['is_friend']=0;
				}
			}
		}
		$this->close();
		return $people;
	}
	function getUserInfo($user_id,$reconnect=false){
		$sql = "SELECT * FROM axis.axis_member WHERE id = {$user_id} LIMIT 1";
		$this->force_connect($reconnect);
		//$this->open(0);
		$rs = $this->fetch($sql);
		//$this->close();
		return $rs;
	}
	
	function addUserIDforRegistration($fb_id){
		
		$sql = "
		INSERT IGNORE INTO axis.axis_member
		(nickname ,	no_hp, 	register_date,n_status,fb_id,email,name)
		VALUES
		('{$_SESSION['user_me']['first_name']}',NULL,NULL,0,'{$fb_id}','{$_SESSION['user_me']['email']}','{$_SESSION['user_me']['name']}')
		";
			
		$this->open(0);
			$q = $this->query($sql);
			$userid = mysql_insert_id();
		$this->close();
	
	return 	$userid ;
	
	}
	
	function checkUserDataInWeb($fb_id,$reconnect=false){
		$sql = "SELECT count(*) as total,user_id, 	fb_id  FROM axis.tbl_user_fb WHERE fb_id = {$fb_id} LIMIT 1";
			$this->open(0);
		$rs = $this->fetch($sql);
			$this->close();
		
		return $rs;
	
	}
	
	function checkUserhasBeenRgister($fb_id,$reconnect=false){
		$sql = "SELECT count(*) as total,id as user_id, fb_id  FROM axis.axis_member WHERE n_status = 0 AND fb_id='{$fb_id}' LIMIT 1";
			$this->open(0);
		$rs = $this->fetch($sql);
			$this->close();

		return $rs;
	
	}
	
	
	function registrationUser($data,$reconnect=false){
		$sql = "
			UPDATE axis.axis_member 
			SET  nickname='{$data['nickname']}' ,	no_hp='{$data['no_hp']}', 	register_date=NOW() ,n_status=1 
			WHERE id = {$data['user_id']} LIMIT 1
			";
					
			 $this->open(0);
				$q = $this->query($sql);
			 $this->close();
		return $q;
		
	}
	function save_fb_and_user_id($user_id,$fb_id){
		
		$sql = "INSERT IGNORE INTO axis.tbl_user_fb
					(user_id,fb_id,authorized_date)
					VALUES
					({$user_id},{$fb_id},NOW())";
		
			$this->open(0);
			$q = $this->query($sql);
			$this->close();

		if($q) return true;
		else  return false;
	}
	
	function userStat(){	
		$this->open(0);
		$sql = "
				SELECT COUNT(fb_user.total) as total
				FROM (
					SELECT count(fb_id) as total
					FROM axis.tbl_user_fb
					GROUP BY fb_id	
				) as fb_user
				";				
		
		$totalUser = $this->fetch($sql, 1);
		
		$this->close();
		// print_r($sql);exit;
		return $totalUser[0]['total'];
	}
	function check_online_status($seats,$reconnect=false){
		$n= sizeof($seats);
		$check = array();
		$check[] = $seats[0];
		for($i=1;$i<$n;$i++){
			$sql = "SELECT is_online FROM axis.tbl_online a INNER JOIN axis.tbl_user_fb b
					ON a.user_id = b.user_id 
					WHERE b.fb_id='{$seats[$i]['fb_id']}' LIMIT 1";
			$this->force_connect($reconnect);
			$rs = $this->fetch($sql);
			if($rs['is_online']=1){
				$check[] = $rs;
			}
		}
		return $check;
	}
}
?>