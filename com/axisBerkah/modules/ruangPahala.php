<?php
GLOBAL $APP_PATH;
include_once $APP_PATH.BERKAHAPPLICATION."/helper/TwitterHelper.php";
class ruangPahala extends App{
	var $Request;
	var $View;

	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->twitterHelper= new TwitterHelper();
		$this->setVar();
		$this->run();
	}
	
	function run(){
		$this->main();
	}
	
	function tojson($data){
	
					header('Content-type: application/json');
					print(json_encode($data));exit;
	
	}
	
	function main(){
			// print_r('<pre>');print_r($this->Request->encrypt_params(array("page"=>"contentDownload","actAjax"=>"getUserData"))); exit;
		global $CONFIG,$LOCALE;
		
		$flagTweet = $this->twitterHelper->count_flagged_tweets($_SESSION['twit_id']);
		$countBadTweet = $this->twitterHelper->count_bad_tweets($_SESSION['twit_id']);
		$qry = "
			SELECT no_hp FROM axis.axis_member WHERE fb_id={$_SESSION['user_login_id']} AND n_status=1 LIMIT 1
		";
		$this->open(0);
			$userAxis=$this->fetch($qry);
		$this->close();		
			// print_r($userAxis);exit;
		$prefixUserNumber = preg_match("/838/i", substr($userAxis['no_hp'],0,5));
		$prefixUserNumber2 = preg_match("/831/i", substr($userAxis['no_hp'],0,5));

		if($prefixUserNumber||$prefixUserNumber2)$axis=true;
		else $axis=false;
		
		$masukRuangPahala =true;
		if($axis==false) $masukRuangPahala =false;
		if($flagTweet<=0) $masukRuangPahala =false;
		if($countBadTweet<=0) $masukRuangPahala =true;
			if($masukRuangPahala==false){
		
				$messages = $LOCALE['BERKAH']['AXIS_PAHALA_BAD_WORD'][0];
				if($axis==false) $messages = $LOCALE['BERKAH']['NON_AXIS_PAHALA'][round(rand(0,2))] ; 
				$this->View->assign('messages', $messages);
				$this->View->assign('berkahLanjutUrl', 'index.php?reg=success');
				$this->View->assign("barokahUrl",$CONFIG['BAROKAH_SOURCES']);	
				$this->View->assign("berkahUrl",$CONFIG['BASE_DOMAIN_BAROKAH']);			
				print $this->View->toString(BERKAHAPPLICATION.'/berkah_messages.html');
				die();
			}
		
		$this->View->assign('getUserData',$this->Request->encrypt_params(array("page"=>"contentDownload","actAjax"=>"getUserData")));
		$this->View->assign('purchaseContent',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"purchaseContent")));
		$this->View->assign('getVerificationCode',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"getVerificationCode")));
		$this->View->assign('validationCode',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"validationCode")));
		
		$this->log('page','ruang_pahala');
		return $this->View->toString(BERKAHAPPLICATION.'/ruangpahala.html');
	}
	
	function getContentsByID(){
		$flagTweet = $this->twitterHelper->count_flagged_tweets($_SESSION['twit_id']);
		$cid = intval($this->Request->getPost('c_id'));
			if($flagTweet>0 || $countBadTweet<=0) {
			
				if($cid!=0){
					$sql = "SELECT * FROM axis.axis_items WHERE home_status=2 AND  category = {$cid}";
					
					$this->open(0);
						$rsItems = $this->fetch($sql,1);
					$this->close();
					if($rsItems) $data = $rsItems;
					else $data = null;
				}else $data = null;
			}else $data = null;

		$this->tojson($data);
	
	}

}
