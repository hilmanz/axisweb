<?php

class newsTicker extends App{
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
	
	function main($fb_id=null,$berkah_token=null){
		global $CONFIG;
		$this->open(0);
			$sql = "SELECT *
					FROM axis.tbl_news_ticker 
					WHERE n_status = 1
					ORDER BY published_date DESC
					";
			$news = $this->fetch($sql,1);
		$this->close();
		$this->View->assign('news', $news);
		$this->assign("berkahUrl",$CONFIG['BASE_DOMAIN_BAROKAH']."?fb_id={$fb_id}&t={$berkah_token}");
		return $this->View->toString(APPLICATION.'/widgets/newsTicker.html');
	}
	
	function newsTick(){
		$this->open(0);
			$sql = "SELECT *
					FROM axis.tbl_news_ticker 
					WHERE n_status = 1
					ORDER BY published_date DESC
					";
			$news = $this->fetch($sql,1);
		$this->close();
		$this->View->assign('news', $news);
		return $this->View->toString(BERKAHAPPLICATION.'/widgets/newsTicker.html');
	}
}
?>