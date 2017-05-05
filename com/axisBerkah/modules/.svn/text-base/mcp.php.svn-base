<?php
global $APP_PATH;
include_once $APP_PATH."axis/helper/AxisHelper.php";

class mcp extends App{
	var $Request;
	var $View;
	var $axisHelper;
	var $user_id;
	var $item_id;
	var $ccode;
	var $scode;
	var $no_hp;
	var $code;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->axisHelper = new AxisHelper($req);
		$this->setVar();
		$this->run();
	}
	
	function run(){
		$this->getUserData();
		
		$act = $this->Request->getParam('actAjax');
		if($act!=''){
			$this->$act();
		}else{
			$this->main();
		}
	}
	
	function tojson($data){
	
					header('Content-type: application/json');
					print_r(json_encode($data));exit;
	
	}
	
	function checkAxisNumber(){
									
						
							$prefixUserNumber = preg_match("/838/i", substr($this->no_hp,0,5));
							$prefixUserNumber2 = preg_match("/831/i", substr($this->no_hp,0,5));
							
							if($prefixUserNumber||$prefixUserNumber2)$axis=true;
							else $axis=false;
						return $axis;
	
	}
	
	
	function getUserData(){
			global $MCP,$LOCALE;
			$this->user_id=$_SESSION['user_id'];
			$this->item_id = $this->Request->getPost('item_id');
			$this->ccode= $MCP['verify_ccode']; //CCODE
			$this->scode= $MCP['verify_scode']; //SCODE
			$this->code = $this->Request->getPost('code');
			
			$this->open(0);
					$qry = "
							SELECT * FROM axis.axis_member WHERE n_status =1 AND id={$this->user_id} LIMIT 1
					";
					
			$axisMemberData=$this->fetch($qry);	
// print_r($axisMemberData);exit;			
			$this->close();		
			$this->no_hp =$axisMemberData['no_hp'];
			$axisMemberData=null;
			
			$isAxis = $this->checkAxisNumber();
			if(! $isAxis) {
				$idxType = round(rand(0,3));
				$data['data'] = $LOCALE['FAIL_GET_CONTENT'][$idxType];
				$data['response'] = 0;
				$this->tojson($data);			
			}
			//MUST INIT FIRST
			$initAxisUser = $this->axisHelper->init($this->user_id,true);
			
	}
	
	function getVerificationCode(){

		$verificationCode = $this->axisHelper->get_verification_code($this->user_id,$this->no_hp,true);
		exit;
	
	}
	
	function purchaseContent(){	
			global $LOCALE;
			// $this->item_id = 4;
			// print_r($this->item_id);exit;
			if($this->axisHelper->purchase_content($this->user_id,$this->item_id,$this->ccode,$this->scode,true)){
				$rand = round(rand(0,4));
				$data['response'] = 1;
				$data['message'] = '<h1>'.$LOCALE['CLAIM_CONTENT_FREE'][$rand].'</h1>';
				$this->log('download',$this->item_id);
			}else{
				$data['response'] = 0;
			}
				$this->tojson($data);
	
	}
	
	function validationCode(){
		
		if($this->axisHelper->validateCode($this->code,true)){
			$data['response'] = 1;
		}else{
			$data['response'] = 0;
		}
			$this->tojson($data);
	
	}
	
	
	function isItemAvailable(){
		if($this->axisHelper->isItemAvailable($this->item_id,true)){
			$data['response'] = 1;
		}else{
			$data['response'] = 0;
		}
			$this->tojson($data);
	
	}
	
	function isItemRedeemedByUser(){
		if($this->axisHelper->isItemRedeemedByUser($this->user_id,$this->item_id,true)){
			$data['response'] = 1;
		}else{
			$data['response'] = 0;
		}
			$this->tojson($data);
	
	
	}
	
	function getItemDetail(){
		$result = $this->axisHelper->getItemDetail($this->item_id,true);
		if($result){
			$data['data'] = $result;
			$data['response'] = 1;
		}else{
			$data['response'] = 0;
		}
			$this->tojson($data);
	
	}
	
	
}
