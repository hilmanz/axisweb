<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class activity extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'detail_daily' ){
			return $this->detail_daily();
		}elseif( $act == 'detail_hourly' ){
			return $this->detail_hourly();
		}else{
			return $this->listing_activity();
		}
	}

	function listing_activity(){
		$start = intval($this->Request->getParam('st'));
		$qry = "
			SELECT a.id,a.user_id,count(a.user_id) as tot_login,d.tot_update,e.tot_upload,f.tot_download,g.tot_gift,h.tot_logout,b.nickname 
			FROM tbl_activity_log a
			LEFT JOIN axis_member b ON a.user_id = b.id
			LEFT JOIN (
				SELECT user_id,count(user_id) as tot_update FROM tbl_activity_log WHERE action_id='4' GROUP BY user_id
			) d ON a.user_id=d.user_id
			LEFT JOIN (
				SELECT user_id,count(user_id) as tot_upload FROM tbl_activity_log WHERE action_id='2' GROUP BY user_id
			) e ON a.user_id=e.user_id
			LEFT JOIN (
				SELECT user_id,count(user_id) as tot_download FROM tbl_activity_log WHERE action_id='3' GROUP BY user_id
			) f ON a.user_id=f.user_id
			LEFT JOIN (
				SELECT user_id,count(user_id) as tot_gift FROM tbl_activity_log WHERE action_id='5' GROUP BY user_id
			) g ON a.user_id=g.user_id
			LEFT JOIN (
				SELECT user_id,count(user_id) as tot_logout FROM tbl_activity_log WHERE action_id='6' GROUP BY user_id
			) h ON a.user_id=h.user_id
			WHERE a.action_id='1'
			GROUP BY a.user_id
			ORDER BY a.date_time DESC;
		";
		//print_r($qry);
		$this->open(0);
		$list = $this->fetch($qry,1);
		$this->close();
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news"));		
		return $this->View->toString("axis/admin/activity_list.html");
	}
	
	function detail_daily(){
		$this->open(0);
		$start = intval($this->Request->getParam('st'));
		$userid = $this->Request->getParam('user_id');
		
		$qry_login = "
			SELECT a.user_id,SUBSTR(a.date_time,1,10) as date_time,COUNT(SUBSTR(a.date_time,1,10)) as tot_login
			FROM tbl_activity_log a
			WHERE a.action_id=1 AND a.user_id='$userid'
			GROUP BY SUBSTR(a.date_time,1,10)
			ORDER BY a.date_time DESC;
		";
	
		$list_login = $this->fetch($qry_login,1);
		$this->View->assign('list',$list_login);
		
		$qry_update = "
			SELECT a.user_id,SUBSTR(a.date_time,1,10) as date_time,COUNT(SUBSTR(a.date_time,1,10)) as tot_update
			FROM tbl_activity_log a
			WHERE a.action_id=4 AND a.user_id='$userid'
			GROUP BY SUBSTR(a.date_time,1,10)
			ORDER BY a.date_time DESC;
		";
	
		$list_update = $this->fetch($qry_update,1);
		$this->View->assign('list_update',$list_update);
		
		$qry_upload = "
			SELECT a.user_id,SUBSTR(a.date_time,1,10) as date_time,COUNT(SUBSTR(a.date_time,1,10)) as tot_upload
			FROM tbl_activity_log a
			WHERE a.action_id=2 AND a.user_id='$userid'
			GROUP BY SUBSTR(a.date_time,1,10)
			ORDER BY a.date_time DESC;
		";
	
		$list_upload = $this->fetch($qry_upload,1);
		$this->View->assign('list_upload',$list_upload);
		
		$qry_download = "
			SELECT a.user_id,SUBSTR(a.date_time,1,10) as date_time,COUNT(SUBSTR(a.date_time,1,10)) as tot_download
			FROM tbl_activity_log a
			WHERE a.action_id=3 AND a.user_id='$userid'
			GROUP BY SUBSTR(a.date_time,1,10)
			ORDER BY a.date_time DESC;
		";
	
		$list_download = $this->fetch($qry_download,1);
		$this->View->assign('list_download',$list_download);
		
		$qry_gift = "
			SELECT a.user_id,SUBSTR(a.date_time,1,10) as date_time,COUNT(SUBSTR(a.date_time,1,10)) as tot_gift
			FROM tbl_activity_log a
			WHERE a.action_id=5 AND a.user_id='$userid'
			GROUP BY SUBSTR(a.date_time,1,10)
			ORDER BY a.date_time DESC;
		";
	
		$list_gift = $this->fetch($qry_gift,1);
		$this->View->assign('list_gift',$list_gift);
		
		$qry_logout = "
			SELECT a.user_id,SUBSTR(a.date_time,1,10) as date_time,COUNT(SUBSTR(a.date_time,1,10)) as tot_logout
			FROM tbl_activity_log a
			WHERE a.action_id=6 AND a.user_id='$userid'
			GROUP BY SUBSTR(a.date_time,1,10)
			ORDER BY a.date_time DESC;
		";
	
		$list_logout = $this->fetch($qry_logout,1);
		$this->View->assign('list_logout',$list_logout);
		
		$sql ="
			SELECT nickname FROM axis_member WHERE id='$userid'
		";
		
		$nama = $this->fetch($sql,1);
		foreach($nama as $val){
			$data = $val['nickname'];
		}
		$this->View->assign('nama',$data);
		$this->close();
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news"));
		return $this->View->toString("axis/admin/activity_daily.html");
	}
	
	function detail_hourly(){
		$this->open(0);
		$start = intval($this->Request->getParam('st'));
		$userid = $this->Request->getParam('userid');
		$date = $this->Request->getParam('date');
		list($y,$m,$d) = explode('-',$date);
		$day = $d+1;
		$enddate = $y."-".$m."-".$day;
		$show = $this->Request->getParam('show');
		if ($show=='login') {
			$qry_login = "
				SELECT a.user_id,SUBSTR(a.date_time,12,2) as hour,COUNT(SUBSTR(a.date_time,12,2)) as tot_login
				FROM tbl_activity_log a
				WHERE a.action_id=1 AND a.user_id='$userid' AND a.date_time>='$date' AND a.date_time<='$enddate'
				GROUP BY SUBSTR(a.date_time,12,2)
				ORDER BY a.date_time DESC;
			";
			
			$list_login = $this->fetch($qry_login,1);
			$this->View->assign('list',$list_login);
		} elseif($show=='update') {
			$qry_update = "
				SELECT a.user_id,SUBSTR(a.date_time,12,2) as hour,COUNT(SUBSTR(a.date_time,12,2)) as tot_update
				FROM tbl_activity_log a
				WHERE a.action_id=4 AND a.user_id='$userid' AND a.date_time>='$date' AND a.date_time<='$enddate'
				GROUP BY SUBSTR(a.date_time,12,2)
				ORDER BY a.date_time DESC;
			";
		
			$list_update = $this->fetch($qry_update,1);
			$this->View->assign('list_update',$list_update);
		} elseif($show=='upload') {
			$qry_upload = "
				SELECT a.user_id,SUBSTR(a.date_time,12,2) as hour,COUNT(SUBSTR(a.date_time,12,2)) as tot_upload
				FROM tbl_activity_log a
				WHERE a.action_id=2 AND a.user_id='$userid' AND a.date_time>='$date' AND a.date_time<='$enddate'
				GROUP BY SUBSTR(a.date_time,12,2)
				ORDER BY a.date_time DESC;
			";
		
			$list_upload = $this->fetch($qry_upload,1);
			$this->View->assign('list_upload',$list_upload);
		} elseif($show=='download') {
			$qry_download = "
				SELECT a.user_id,SUBSTR(a.date_time,12,2) as hour,COUNT(SUBSTR(a.date_time,12,2)) as tot_download
				FROM tbl_activity_log a
				WHERE a.action_id=3 AND a.user_id='$userid' AND a.date_time>='$date' AND a.date_time<='$enddate'
				GROUP BY SUBSTR(a.date_time,12,2)
				ORDER BY a.date_time DESC;
			";
		
			$list_download = $this->fetch($qry_download,1);
			$this->View->assign('list_download',$list_download);
		} elseif($show=='gift') {
			$qry_gift = "
				SELECT a.user_id,SUBSTR(a.date_time,12,2) as hour,COUNT(SUBSTR(a.date_time,12,2)) as tot_gift
				FROM tbl_activity_log a
				WHERE a.action_id=5 AND a.user_id='$userid' AND a.date_time>='$date' AND a.date_time<='$enddate'
				GROUP BY SUBSTR(a.date_time,12,2)
				ORDER BY a.date_time DESC;
			";
		
			$list_gift = $this->fetch($qry_gift,1);
			$this->View->assign('list_gift',$list_gift);
		} else {
			$qry_logout = "
				SELECT a.user_id,SUBSTR(a.date_time,12,2) as hour,COUNT(SUBSTR(a.date_time,12,2)) as tot_logout
				FROM tbl_activity_log a
				WHERE a.action_id=6 AND a.user_id='$userid' AND a.date_time>='$date' AND a.date_time<='$enddate'
				GROUP BY SUBSTR(a.date_time,12,2)
				ORDER BY a.date_time DESC;
			";
		
			$list_logout = $this->fetch($qry_logout,1);
			$this->View->assign('list_logout',$list_logout);
		}
		
		$sql ="
			SELECT nickname FROM axis_member WHERE id='$userid'
		";
		
		$nama = $this->fetch($sql,1);
		foreach($nama as $val){
			$data = $val['nickname'];
		}
		$this->View->assign('nama',$data);
		$this->View->assign('date',$date);
		$this->View->assign('show',$show);
		$this->close();
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news"));		
		return $this->View->toString("axis/admin/activity_hourly.html");
	}
}