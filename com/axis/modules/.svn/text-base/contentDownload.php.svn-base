<?php
class contentDownload extends App{
	var $Request;
	var $View;

	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
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
	
	function getUserData(){
				global $LOCALE;
					$user_id = intval($_SESSION['user_id']);
					
					$this->open(0);
					$qry = "
							SELECT * FROM axis.axis_member WHERE n_status =1 AND id={$user_id} LIMIT 1
					";
					
					if($user_id!=0) $axisMemberData=$this->fetch($qry);
					$this->close();
					
					$prefixUserNumber = preg_match("/838/i", substr($axisMemberData['no_hp'],0,5));
					$prefixUserNumber2 = preg_match("/831/i", substr($axisMemberData['no_hp'],0,5));
					
					if($prefixUserNumber||$prefixUserNumber2)$axis=true;
					else $axis=false;

					
					if($axisMemberData){
						if($axis){
							if($axisMemberData['verified']==1) $data['response'] = 1;
							else $data['response'] = 0;	
						}else {
						
								$idxType = round(rand(0,3));
						
								$data['response'] = 2;
								$data['status'] =$LOCALE['FAIL_GET_CONTENT'][$idxType];
						}
						
										
						
					}					
					// print_r($data);exit;
					header('Content-type: application/json');
					print_r(json_encode($data));exit;
	}
	
	
}
