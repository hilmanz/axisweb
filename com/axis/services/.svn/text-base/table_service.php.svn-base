<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
/**
 * Axis Web Service for Dining Tabel App
 * @author duf
 *
 */
class table_service extends API{
	var $user_id;
	var $helper;
	function init(){
		$this->user_id = $this->access_info['user_id'];
		$this->helper = new FacebookHelper($this->Request);
	}
	function foo(){
		return $this->toJson(1,'message',array("foo"=>"bar","name"=>"hello"));	
	}
	function init_user(){
		$fb_id = $this->Request->getParam("fb_id");
	}
	/**
	 * 
	 */
	function ping(){
		global $CONFIG;
		$this->init();
		$action = $this->access_info['action'];
		if($action=="service_ping"){
			$ping_request = $this->Request->getParam("r");
			$chunk = unserialize(urldecode64($ping_request));
			if($chunk['user_id']==$this->user_id
				&&
				$chunk['next_ping']<time()){
				$this->helper->ping($this->user_id,true);
				$next_ping_request = urlencode64(serialize(array("user_id"=>$this->user_id,
														"last_ping"=>time(),
														"next_ping"=>time()+3)
											 ));
				$access_token = get_access_token($this->user_id,$CONFIG['SERVICE_KEY'],'/',false);
				return $this->toJson(1,'ping',array("r"=>$next_ping_request,"t"=>$access_token));
			}
		}else{
			return $this->toJson(0,'wrong service');
		}
		return $this->toJson(0,'ping invalid',""); 
	}
	/**
	 * retrieve online people list from db
	 */
	function activity(){
		$this->init();
		$since_id = intval($this->Request->getParam("since_id"));
		$people = $this->helper->get_activity($this->user_id,$since_id);
		if(sizeof($people)>0){
			foreach($people as $p){
				$since_id = $p['since_id'];
			}
		}
		return $this->toJson(1,'ok',array('since_id'=>$since_id,'people'=>$people)); 
	}
	function seats(){
		$this->init();
		$seats = stripslashes($this->helper->get_seatings($this->user_id,true));
		if(strlen($seats)>0){
			$rs = unserialize($seats);
		}
		/*
		foreach($rs['seats'] as $n=>$v){
			$rs['seats'][$n]['fb_id'] = strval($v['fb_id']);
		}
		*/
		//have to check if every people in the seats is still online ?
		//$rs['seats'] = $this->helper->check_online_status($rs['seats']);
		return $this->toJson(1,'ok',$rs); 
	}
	
	function update_seats(){
		$this->init();
		$s = $_POST['s'];
		$seats = json_decode(stripslashes(urldecode($s)),true);
		//foreach($seats as $n=>$v){
			//$seats[$n]['fb_id'] = strval($v['fb_id']);
		//}
		for($i=0;$i<sizeof($seats);$i++){
			$seats[$n]['fb_id'] = strval($v['fb_id']);
		}
		//var_dump($seats);
		$q = $this->helper->update_seatings($this->user_id,serialize($seats),true);
		if($q){
			return $this->toJson(1,'ok',null); 
		}else{
			return $this->toJson(0,'failed',null);
		}
	}
}
?>