<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/dateHelper.php';
include_once $APP_PATH.'axis/helper/noWhiteSpaceHelper.php';

class profile extends App{
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
		if($this->Request->getParam('act')=='loadProfile'){
			$this->loadProfile($userID);
		}else if($this->Request->getParam('act')=='updateProfile'){
			$this->updateProfile($userID);
		}else if($this->Request->getParam('act')=='profileConversation'){
			$this->profileConversation($userID);
		}else if($this->Request->getParam('act')=='bigImage'){
			$this->bigImage();
		}else if($this->Request->getParam('act')=='moreConversation'){
			$this->moreConversation($userID);
		}else if($this->Request->getParam('act')=='shareFB'){
			$this->shareFB($userID);
		}
	}
	
	function loadProfile($userID){
		$this->open(0);
		$sql = "SELECT * FROM axis_member WHERE id=".$userID."";
		$q = $this->fetch($sql);
		$this->close();
		
		echo json_encode($q);exit;
	}
	function updateProfile($userID){
		$nickname = $this->Request->getPost('nickname');
		$nomorHP = $this->Request->getPost('hp');
		
		
		$this->open(0);
		$qr = "SELECT no_hp 
				FROM axis_member
				WHERE id=".$userID."";
		$checkNumber = $this->fetch($qr);
		$this->close();
		
		if ($nomorHP != $checkNumber['no_hp']){
			/*helper=> Check nomor HP*/
			$zero = substr($nomorHP,0,1);
			$null = $nomorHP;
			$enamDuaNol = substr($nomorHP,0,3);
			$enamDuaPlus = substr($nomorHP,0,3);
			if($zero == '0'){
				$nomorHP = '62'.substr($nomorHP,1);
			}else if($enamDuaPlus == '+62'){
				$nomorHP = '62'.substr($nomorHP,3);
			}else if($enamDuaNol == '620'){
				$nomorHP = '62'.substr($nomorHP,3);
			}
			
			if($nomorHP == '62'){
				$nomorHP = '';
			}
			
			/*check Nomor Axis*/
			if ($nomorHP != null || $nomorHP != ""){
				$noAxis = substr($nomorHP,0,5);
			}
			
			if($noAxis == '62838' || $noAxis == '62831'){
				$nomorAXIS = true;
			}else{
				$nomorAXIS = false;
			}
			
			$this->open(0);
			
				$sql = "UPDATE axis_member 
					SET nickname = '".$nickname."', no_hp = '".$nomorHP."',verified=0
					WHERE id=".$userID."";
			
			
			$q = $this->query($sql);
			$this->close();
			if ($q){
				if($nomorAXIS){
					$msg = "Kamu telah sukses update data.<br>Oh ya pastiin nomor AXIS kamu aktif dan valid ya karena AXIS bakal bagi-bagi Barokah.";
				}else{
					$msg = "Kamu telah sukses update data.<br>Kalau bisa nanti Bebeb edit pakai nomor AXIS ya biar gampang nyamber hadiah Barokah dari AXIS.";
				}
				
			}else{
				$msg = "Update data gagal, silahkan coba lagi Beb.";
			}
		}else if ($nomorHP == $checkNumber['no_hp']){
			$this->open(0);
			$sql = "UPDATE axis_member 
					SET nickname = '".$nickname."'
					WHERE id=".$userID."";
			$q = $this->query($sql);
			$this->close();
			if ($q){
				$msg = "Data kamu sudah tersimpan Beb";
			}else{
				$msg = "Update nickname gagal, silahkan coba lagi.";
			}
		}
		print_r($msg);exit;
	}
	function profileConversation($userID){
		$user_id = $this->Request->getPost('userID');
		$this->open(0);
		$sql = "SELECT a.user_id, a.posting, a.posting_date, a.img ,b.nickname, c.fb_id FROM tbl_user_posting a
				LEFT JOIN axis_member b
				ON a.user_id = b.id
				LEFT JOIN tbl_user_fb c
				ON a.user_id = c.user_id
				WHERE a.user_id=".$user_id." AND a.n_status = 1
				ORDER BY a.posting_date DESC LIMIT 20";
		$q = $this->fetch($sql,1);
		$this->close();
		$i=0;
		foreach($q as $postDate){
			$q[$i]['posting_date'] = $this->dateHelper->hoursCalendar($postDate['posting_date']);
			$q[$i]['posting'] = $this->noWhiteSpace->noWhiteSpace($q[$i]['posting'], 16, 20);
			$i++;
		}
		$q[0]['isMe'] = $userID;
		echo json_encode($q);exit;
	}
	function moreConversation($userID){
		$start = $this->Request->getPost('startID');
		$this->open(0);
		$sql = "SELECT a.user_id, a.posting, a.posting_date, a.img ,b.nickname, c.fb_id FROM tbl_user_posting a
				LEFT JOIN axis_member b
				ON a.user_id = b.id
				LEFT JOIN tbl_user_fb c
				ON a.user_id = c.user_id
				WHERE a.user_id=".$userID." AND a.n_status = 1
				ORDER BY a.posting_date DESC LIMIT ".$start.",20";
		$q = $this->fetch($sql,1);
		$this->close();
		$i=0;
		foreach($q as $postDate){
			$q[$i]['posting_date'] = $this->dateHelper->hoursCalendar($postDate['posting_date']);
			$q[$i]['posting'] = $this->noWhiteSpace->noWhiteSpace($q[$i]['posting'], 16, 20);
			$i++;
		}
		echo json_encode($q);exit;
	}
	
	function bigImage(){
		$user_id = intval($this->Request->getPost('userID'));
		// var_dump($user_id);exit;
		$this->open(0);
		$sql = "SELECT a.image, b.fb_id, c.nickname
				FROM tbl_food_image a 
				LEFT JOIN tbl_user_fb b
				ON a.user_id = b.user_id
				LEFT JOIN axis_member c
				ON a.user_id = c.id
				WHERE a.n_status = 1 
				AND a.user_id =".$user_id."
				LIMIT 1";
		$q = $this->fetch($sql);
		$this->close();
		
		echo json_encode($q);exit;
	}
	
	function shareFB($userID){
		$this->open(0);
		$sql = "SELECT a.posting, a.img ,b.nickname
				FROM tbl_user_posting a
				LEFT JOIN axis_member b
				ON a.user_id = b.id
				WHERE a.user_id=".$userID."
				ORDER BY a.posting_date DESC LIMIT 1";
		$q = $this->fetch($sql);
		$this->close();
		
		$q['posting'] = strip_tags($q['posting']);
		$this->log('sharefb',$userID,true);
		echo json_encode($q);exit;
	}
}
