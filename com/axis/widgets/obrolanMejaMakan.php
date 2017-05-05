<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/dateHelper.php';
include_once $APP_PATH.'axis/helper/noWhiteSpaceHelper.php';

class obrolanMejaMakan extends App{
	var $Request;
	var $View;
	var $dateHelper;
	var $noWhiteSpace;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->dateHelper = new dateHelper();
		$this->noWhiteSpace = new noWhiteSpaceHelper();
		$this->run();
	}
	
	function run(){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		if($this->Request->getParam('act')=='updateOMM'){
			$this->updateOMM();
		}else{
			$this->main();
		}
	}
	
	function main(){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		// var_dump($userID);exit;
		$this->open(0);
		
		// $sql = "SELECT * FROM tbl_seatings WHERE user_id = ".$userID."";
		// $seats = $this->fetch($sql);
		// $seating = unserialize($seats['seatings']);
		
		// $seat = $seating['seats'];
		// foreach($seat as $val){
			// if ($val['fb_id'] != ""){
				// $fb_ID[] = $val['fb_id'];
			// }
		// }
		// $fbID = implode(",",$fb_ID);
		
		// $sql2 = "SELECT user_id FROM tbl_user_fb WHERE fb_id IN ($fbID)";
		// $userID = $this->fetch($sql2,1);
		// foreach($userID as $usrID){
			// $myFriendID[] = $usrID['user_id'];
		// }
		
		// $myFriendID = implode(",",$myFriendID);
		// $_SESSION['myFriendID'] = $myFriendID;
		// $sql3 = "SELECT a.posting, a.user_id, a.posting_date, a.posting_date_ts, c.fb_id, b.nickname
				// FROM tbl_user_posting a
				// LEFT JOIN axis_member b
				// ON a.user_id = b.id
				// LEFT JOIN tbl_user_fb c
				// ON a.user_id = c.user_id
				// WHERE a.user_id IN ($myFriendID) 
				// ORDER BY a.posting_date DESC LIMIT 20";
		
			$sql3 = "SELECT a.posting, a.user_id, a.posting_date, a.posting_date_ts, c.fb_id, b.nickname
					FROM tbl_user_posting a
					LEFT JOIN axis_member b
					ON a.user_id = b.id
					LEFT JOIN tbl_user_fb c
					ON a.user_id = c.user_id
					WHERE a.n_status = 1
					ORDER BY a.posting_date DESC LIMIT 100";
		// $sql3 = "SELECT * FROM tbl_user_posting";
		$posting = $this->fetch($sql3,1);
		// print_r('<pre>');
		// print_r($posting);exit;
		$this->close();
		$i = 0;
		foreach($posting as $postDate){
			$postTime[] = $this->dateHelper->hoursCalendar($postDate['posting_date']);
			$posting[$i]['posting'] = stripslashes($postDate['posting']);
			$posting[$i]['posting'] = $this->noWhiteSpace->noWhiteSpace($posting[$i]['posting'], 27, 32);
			$i++;
		}
		// print_r('<pre>');
		// 
		$this->View->assign('obrolan', $posting);
		$this->View->assign('lastPost', $posting[0]['posting_date_ts']);
		$this->View->assign('timePosting', $postTime);
		
		return $this->View->toString(APPLICATION.'/widgets/obrolanMejaMakan.html');
	}
	
	function updateOMM(){
		$postTS = intval($this->Request->getPost('ts'));
		$myFriend = $_SESSION['myFriendID'];
		$this->open(0);
		// $sql = "SELECT a.posting, a.user_id, a.posting_date, a.posting_date_ts, c.fb_id, b.nickname
				// FROM tbl_user_posting a
				// LEFT JOIN axis_member b
				// ON a.user_id = b.id
				// LEFT JOIN tbl_user_fb c
				// ON a.user_id = c.user_id
				// WHERE a.user_id IN ($myFriend) 
				// AND a.posting_date_ts > ($postTS)
				// ORDER BY a.posting_date ASC
				// LIMIT 1";
		$sql = "SELECT a.posting, a.user_id, a.posting_date, a.posting_date_ts, c.fb_id, b.nickname
				FROM tbl_user_posting a
				LEFT JOIN axis_member b
				ON a.user_id = b.id
				LEFT JOIN tbl_user_fb c
				ON a.user_id = c.user_id
				WHERE a.posting_date_ts > ($postTS)
				AND a.n_status = 1
				ORDER BY a.posting_date DESC
				LIMIT 1";
		$lastPost = $this->fetch($sql);
		$this->close();
		if ($lastPost){
			$lastPost['posting_date'] = $this->dateHelper->hoursCalendar($lastPost['posting_date']);
			$lastPost['posting'] = $this->noWhiteSpace->noWhiteSpace($lastPost['posting'], 27, 32);
		}
		print_r(json_encode($lastPost));exit;
	}
}
