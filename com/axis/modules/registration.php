<?php
class registration extends App{
	var $Request;
	var $View;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->run();
	}
	
	function run(){
	
		$act = $this->Request->getParam('act');
		if($act!=''){
			$this->$act();
		}else{
			$this->main();
		}
	}
	function main(){
	
		sendRedirect('index.php');
		return $this->View->toString(APPLICATION.'/loading_message.html');	
		
	}
	
	function registration(){
		
		
		exit;
	}
	
}
