<?php

class tweeterFeed extends App{
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
		return $this->View->toString(APPLICATION.'/widgets/tweeterFeed.html');
	}
}
