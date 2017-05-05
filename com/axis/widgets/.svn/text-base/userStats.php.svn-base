<?php

class userStats extends App{
	var $Request;
	var $View;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->run();
	}
	
	function run(){
		if($this->Request->getParam('act') == 'updateStats'){
			$this->updateStats();
		}else{
			$this->main();
		}
	}
	
	function main(){
	
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
		
		$sql = "
				SELECT COUNT(user_online.total) as total
				FROM (
					SELECT count(user_id) as total
					FROM axis.tbl_online
					WHERE is_online =1
					GROUP BY user_id	
				) as user_online
				";				
		$totalUserOnline = $this->fetch($sql, 1);
		
		$this->close();
		// print_r($totalUserOnline);exit;
		$this->View->assign('totalUser', $totalUser[0]['total']);
		$this->View->assign('totalUserOnline', $totalUserOnline[0]['total']);
		
		return $this->View->toString(APPLICATION.'/widgets/userStats.html');
	}
	
	function updateStats(){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		$this->open(0);
		$sql1 = "
				SELECT COUNT(fb_user.total) as total
				FROM (
					SELECT count(fb_id) as total
					FROM axis.tbl_user_fb
					GROUP BY fb_id	
				) as fb_user
				";
		$totalUser = $this->fetch($sql1, 1);
		
		$sql2 = "
				SELECT COUNT(user_online.total) as total
				FROM (
					SELECT count(user_id) as total
					FROM axis.tbl_online
					WHERE is_online =1
					GROUP BY user_id	
				) as user_online
				";				
		$totalUserOnline = $this->fetch($sql2, 1);
		
		$sql3 = "SELECT nickname FROM axis_member WHERE id=".$userID." LIMIT 1";
		$nickname = $this->fetch($sql3,1);
		$this->close();
		
		$user['daftar'] = $totalUser[0]['total'];
		$user['online'] = $totalUserOnline[0]['total'];
		$user['nickname'] = $nickname[0]['nickname'];
		
		echo json_encode($user);exit;
	}
}
