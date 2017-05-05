<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/dateHelper.php';

class adminHAP extends App{
	var $Request;
	var $View;
	var $dateHelper;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->dateHelper = new dateHelper();
		$this->run();
	}
	
	function run(){
		if($this->Request->getParam('act')=='updateAdminStatus'){
			$this->updateAdminStatus();
		}else{
			$this->main();
		}
	}
	
	function main(){
		$this->open(0);
		$sql = "SELECT * 
				FROM tbl_admin_posting
				WHERE 1 ORDER BY posting_date
				DESC LIMIT 100";
		$q = $this->fetch($sql, 1);
		$this->close();
		$i=0;
		foreach($q as $postDate){
			if ($i==0){
				$q[$i]['lastAdminStatus'] = $postDate['id'];
			}
			$postTime[] = $this->dateHelper->hoursCalendar($postDate['posting_date']);
			$i++;
		}
		
		$this->View->assign('adminPosting', $q);
		$this->View->assign('timePosting', $postTime);
		return $this->View->toString(APPLICATION.'/widgets/adminHAP.html');
	}
	
	function updateAdminStatus(){
		$since_id= intval($this->Request->getPost('since_id'));
		
		if($since_id==0) {
			$postingid = '';
			$limit = ' LIMIT 1 ';
			
		}
		else {
			$postingid = 'WHERE  id > '.intval($since_id).' ';
			$limit = '';
		}
		$this->open(0);
		$sql = "SELECT * 
				FROM tbl_admin_posting
				{$postingid} ORDER BY posting_date
				DESC {$limit} ";
		$q = $this->fetch($sql, 1);
		$this->close();
		if($q){
			$i=0;
			foreach($q as $postDate){
				if ($i==0){
					$q[$i]['lastAdminStatus'] = $postDate['id'];
				}
				$q[$i]['posting_date'] = $this->dateHelper->hoursCalendar($postDate['posting_date']);
				$i++;
			}
		}else{
			$q = false;
			$q[0]['lastAdminStatus'] = 0;
		}
		
		print_r(json_encode($q));exit;
	}
}
