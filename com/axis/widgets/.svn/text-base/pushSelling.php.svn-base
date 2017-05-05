<?php

class pushSelling extends App{
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
	
		$this->open(0);
		$sql = "SELECT * FROM axis.axis_items WHERE n_status=0 AND item_type=1 AND home_status=0 ";
		
		$popupSell = $this->fetch($sql,1);
		
		$sql = "SELECT * FROM axis.axis_items WHERE n_status=0 AND item_type=1 AND home_status=1";
		
		$homeSell = $this->fetch($sql,1);
		
		$this->close();
		$this->View->assign("itemsSelling",$popupSell);
		$this->View->assign("homeSelling",$homeSell);
		// print_r('<pre>');print_r($q);exit;
		return $this->View->toString(APPLICATION.'/widgets/pushSelling.html');
	}
	
	
}
