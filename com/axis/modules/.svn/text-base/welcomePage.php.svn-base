<?php
class welcomePage extends App{
	var $Request;
	var $View;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->run();
	}
	
	function run(){
		$this->main();
	}
	
	function main(){
		
		$this->View->assign('jml_member', 10000);
		return $this->View->toString(APPLICATION.'/welcomePage.html');
	}
}
