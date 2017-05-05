<?php

class weeklyWinner extends App{
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
		$sql = " select * from axis.tbl_weekly_winner";
		$weeklyWinner = $this->fetch($sql,1);
		$this->close();
		$this->View->assign('weeklyWinner', $weeklyWinner);
		return $this->View->toString(APPLICATION.'/widgets/weeklyWinner.html');
	}
}
