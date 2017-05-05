<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/noWhiteSpaceHelper.php';

class home extends App{
	var $Request;
	var $View;
	var $noWhiteSpace;

	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->noWhiteSpace = new noWhiteSpaceHelper();
		$this->run();
	}
	
	function run(){
		$act = $this->Request->getParam('actAjax');
		if($act!=''){
			$this->$act();
		}else{
			$this->main();
		}
	}
	function main(){
		
			global $CONFIG;
			if($_SESSION['user_id']==null){
				sendRedirect($CONFIG['FB_DOMAIN']);
				die();
			}
			if(!$_COOKIE['jam4_'.$_SESSION['user_id']]){
				if(date('H:i:s')< '16:00:00'){
				
					setcookie('jam4_'.$_SESSION['user_id'],true);
					$this->assign("popupjam4",true); 
				}
			}
			
			//access token for accessing our API
			$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY'],'/');
			$access_token2 = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY'],'service_ping',true);
			
			$fb = new FacebookHelper($req);

			// print_r('<pre>');print_r($_SESSION);exit;
			$fb->open(0);
			$fb->init();
			$fb_id = $fb->getFBId($_SESSION['user_id']);
			$ping_token = $fb->getPingRequestToken($_SESSION['user_id']);
			$user_info = $fb->getUserInfo($_SESSION['user_id']);
			$me = $fb->myself();
			
			$is_like = $fb->isLiked($fb_id);	
			
			//early ping
			$fb->ping($_SESSION['user_id']);

			$fb->close();
			//token for accessing berkah
			$berkah_token = urlencode64(serialize(array("page"=>null,"is_liked"=>$is_like,"me"=>$me)));
			//-->
			 $_SESSION['lastPostingID']=0;
			// $_SESSION['lastPostingID']=0;
			$this->assign("fb_id",$fb_id);
			$this->assign("user_id",$_SESSION['user_id']);
			$this->assign("access_token",$access_token);
			$this->assign("access_token2",$access_token2);
			$this->assign("berkah_token",$berkah_token);
			$this->assign("service_url",$CONFIG['SERVICE_URL']);
			$this->assign("friends",$fb->get_online_friends($_SESSION['user_id']));
			$this->assign("ping_token",$ping_token);
			$this->assign("nickname",$user_info['nickname']);
			$this->widgets($fb_id,$berkah_token);
			return $this->View->toString(APPLICATION.'/homepage.html');
	}
	
	function widgets($fb_id=null,$berkah_token=null){
		global $APP_PATH;
		
		//Obrolan Meja Makan
		include_once $APP_PATH . APPLICATION .'/widgets/obrolanMejaMakan.php';
		$obrolanMejaMakan = new obrolanMejaMakan($this->Request);
		$this->assign('obrolanMejaMakan', $obrolanMejaMakan->main());
		
		//Admin HAP Status
		include_once $APP_PATH . APPLICATION .'/widgets/adminHAP.php';
		$adminHAP = new adminHAP($this->Request);
		$this->assign('adminHAP', $adminHAP->main());
		
		//User Statistic
		include_once $APP_PATH . APPLICATION .'/widgets/userStats.php';
		$userStats = new userStats($this->Request);
		$this->assign('userStats', $userStats->main());
		
		//Push Selling Item
		include_once $APP_PATH . APPLICATION .'/widgets/pushSelling.php';
		$pushSelling = new pushSelling($this->Request);
		$this->assign('pushSelling', $pushSelling->main());
		
		// $resttimefrom = 100000;
		// $resttimeto = 240000;

		// $currentTime = (int) date('His');
		// if ($currentTime > $resttimefrom && $currentTime < $resttimeto )
		// {
			//User Update Status
			include_once $APP_PATH . APPLICATION .'/widgets/updateStatus.php';
			$updateStatus = new updateStatus($this->Request);
			$this->assign('updateStatus', $updateStatus->main());
			// }
		// else
		// {	
			// $this->assign('updateStatus', $this->View->toString(APPLICATION.'/widgets/updateStatus_disable.html'));
		// } 
		
		//Weekly Winner
		include_once $APP_PATH . APPLICATION .'/widgets/weeklyWinner.php';
		$weeklyWinner = new weeklyWinner($this->Request);
		$this->assign('weeklyWinner', $weeklyWinner->main());
		
		//News Ticker
		include_once $APP_PATH . APPLICATION .'/widgets/newsTicker.php';
		$newsTicker = new newsTicker($this->Request);
		$this->assign('newsTicker', $newsTicker->main($fb_id,$berkah_token));
		
	}
	
	
	function getTablePostingAndFoodPict(){
		

		$since_id= $_SESSION['lastPostingID'];
		if($since_id!=0) {
		$qSince=" userPosting.id > {$since_id}  AND ";
		}else $qSinceLimit =" LIMIT 1 ";
					 // $_SESSION['lastPostingID']=0;
		$this->open(0);
		$qry =" SELECT userFB.special, userFB.fb_id, am.id as userid ,am.nickname as nickname
				FROM axis.tbl_user_fb userFB
				INNER JOIN axis.axis_member am
				ON userFB.user_id = am.id
				WHERE EXISTS (SELECT 1 FROM axis.tbl_online online WHERE online.user_id = userFB.user_id AND online.is_online = 1);";
				// print_r($qry);
		$user=$this->fetch($qry,1);
		foreach($user as $val){
			$userID[] = $val['userid'];
			$userRows[$val['userid']]['nickname'] = $val['nickname'];
			$userRows[$val['userid']]['fb_id'] = $val['fb_id'];
			$userRows[$val['userid']]['special'] = $val['special'];
		}
		$user = null;
		$user_id_list = implode(',',$userID);
		if($user_id_list!='') {
			$qry ="
			SELECT userPosting.id  as postingID,userPosting.user_id,userPosting.posting,userPosting.posting_date ,imageUser.image as img 
			FROM axis.tbl_user_posting userPosting   
			LEFT JOIN  axis.tbl_food_image imageUser  
			ON userPosting.user_id = imageUser.user_id AND imageUser.n_status=1
			WHERE {$qSince} userPosting.user_id IN ({$user_id_list}) AND userPosting.n_status = 1 ORDER BY userPosting.posting_date DESC {$qSinceLimit};";
			$rs=$this->fetch($qry,1);
			foreach($rs as $key => $val){
				$rs[$key]['special'] 	= $userRows[$val['user_id']]['special'];
				$rs[$key]['fb_id']	 	= $userRows[$val['user_id']]['fb_id'];
				$rs[$key]['nickname']	= $userRows[$val['user_id']]['nickname'];
			}
		
		// $qry = "
				// SELECT userPosting.id as postingID,userPosting.user_id, userFB.special, userFB.fb_id,userPosting.posting,userPosting.posting_date,imageUser.image as img,am.nickname as nickname
				// FROM (SELECT id,user_id,posting,posting_date FROM axis.tbl_user_posting WHERE n_status = 1 ORDER BY posting_date DESC) as userPosting 
				// LEFT JOIN axis.tbl_user_fb userFB ON userFB.user_id = userPosting.user_id 
				// LEFT JOIN axis.tbl_food_image imageUser ON imageUser.user_id = userPosting.user_id AND  imageUser.n_status=1
				// LEFT JOIN axis.axis_member am ON am.id = userPosting.user_id
				// WHERE exists ( select 1 FROM axis.tbl_online online WHERE online.is_online=1 AND userPosting.user_id=online.user_id)
				// AND userPosting.id > {$_SESSION['lastPostingID']}
				// GROUP BY userPosting.user_id 
				// ORDER BY userPosting.posting_date DESC;
		// ";
		// print_r($_SESSION['lastPostingID']);
		// print_r($qry);
		// $rs=$this->fetch($qry,1);
	
		$i=0;
		$lastPostingID = array();
			foreach($rs as $updateRS){
				$lastPostingID[] =  $updateRS['postingID'];
				$textLength = strlen($this->noWhiteSpace->noWhiteSpace($updateRS['posting'], 20, 20));
				if ($textLength > 60){
					$rs[$i]['posting'] = substr($this->noWhiteSpace->noWhiteSpace($updateRS['posting'], 20, 20),0,60).'...';
				}else{
					$rs[$i]['posting'] = $this->noWhiteSpace->noWhiteSpace($updateRS['posting'], 20, 20);
				}
				$i++;
			}
			// print_r($lastPostingID);
			if(count($lastPostingID)>0)	$_SESSION['lastPostingID'] = max($lastPostingID);
		}
		// print_r($this->lasPostingID);
		$this->close();
		
		header('Content-type: application/json');
		print_r(json_encode($rs));exit;
	}
	
	function cekUnlikeLogout(){
		$this->open(0);
		$qry = "
				SELECT is_like FROM tbl_fb_like
				WHERE fb_id={$_SESSION['user_login_id']} LIMIT 1;
		";
		// print_r($qry);exit;
		$rs=$this->fetch($qry);
				
		$this->close();
		if($rs['is_like']==1) $data = 1;
		else $data = 0;
		header('Content-type: application/json');
		print_r(json_encode($data));exit;
	
	
	}
	
	
}
