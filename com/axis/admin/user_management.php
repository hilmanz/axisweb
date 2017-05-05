<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class user_management extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	
	function admin(){
		
		$act = $this->Request->getParam('act');
		if( $act == 'add_photo' ){
			return $this->add_photo();
		}elseif( $act == 'edit_user' ){
			return $this->edit_user();
		}elseif( $act == 'change_status'){
			return $this->change_status2();
		}elseif( $act == 'delete_photo'){
			return $this->delete_photo();
		}elseif( $act == 'delete_posting' ){
			return $this->change_status();
		}else{
			return $this->listing_user();
		}
	}
	
	function listing_user($total_per_page=20){
		// $dasdas = "DELETE FROM tbl_user_posting";
		// $this->open(0);
		// $this->query($dasdas);
		// $this->close();
		$config['time'] = '%H:%M:%S';
		$start = intval($this->Request->getParam('st'));
		$search = intval($this->Request->getParam(search));
		$name = $this->Request->getParam("name");		
		$btn = $this->Request->getParam("btn");
		
		if($start==NULL){$start = 0;}

		$this->open(0);
		$filter = $search=='1' ? "WHERE c.nickname LIKE '%".$name."%'" : "";
		$sql = "
			SELECT a.id,c.nickname
			FROM tbl_user_posting a
			LEFT JOIN axis_member c ON a.user_id = c.id
			$filter
		";
		$rows = $this->fetch($sql,1);		
		$total = count($rows);
		$last_total=ceil($total/$total_per_page);
		
		$page = intval($this->Request->getParam('st'));
		if(empty($page)){
			$posisi=0;
			$page=1;
		} else {
			$hal = $page-1;
			$posisi = $hal==$last_total ? ($hal * $total_per_page)-2 : $page;
		}
		
		$qry = "
			SELECT a.id,a.user_id,b.image,a.posting,c.nickname,a.n_status,a.posting_date, b.date_time,d.last_login,e.tot_login 
			FROM tbl_user_posting a
			LEFT JOIN tbl_food_image b ON a.posting_date = b.date_time
			LEFT JOIN axis_member c ON a.user_id = c.id
			LEFT JOIN (
			SELECT user_id,MAX(date_time) as last_login
			FROM `tbl_activity_log` 
			WHERE 1 
			AND action_id=1
			GROUP BY user_id
			) d ON a.user_id = d.user_id
			LEFT JOIN (
			SELECT user_id,count(user_id) as tot_login
			FROM `tbl_activity_log` 
			WHERE 1
			AND action_id=1
			GROUP BY user_id
			) e ON a.user_id = e.user_id
			$filter
			ORDER BY a.posting_date DESC
			LIMIT
			$posisi,$total_per_page;
		";
		//print_r($qry);
		$img_path = "images/default-icon.gif"; //images/default-icon.gif (path klo di live)
		$this->View->assign('img_path',$img_path);
		$list = $this->fetch($qry,1);
		if(sizeof($list)==0){
			$this->View->assign("msg","No matched user..");
		}
			
		$no=$posisi+1;
		$key=0;
		foreach ($list as $val) {
			$val['no'] = $no++;
			$data[$key++] = $val;
			$this->View->assign("list",$data);
		}
		// print_r('<pre>');print_r($list);exit;
		$this->View->assign('config', $config);
		$this->View->assign("st",$start);
		$this->View->assign("search",$search);
		$this->View->assign("name",$name);
		$this->View->assign("btn",$btn);
		$this->Paging = new Paginate();
		//$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=user_management"));
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=user_management&name=".$name."&search=".$search."&btn=".$btn.""));
		return $this->View->toString("axis/admin/user_list.html");
		$this->close();
	}
	
	function edit_user(){
	$this->open(0);
		$id = intval($this->Request->getParam('id'));
		$userid = intval($this->Request->getParam('userid'));
		$name = $this->Request->getParam('name');
		$search = $this->Request->getParam('search');
		$start = $this->Request->getParam('st');
		$btn = $this->Request->getParam('btn');
		$datefoto = $this->Request->getParam('datefoto');
		
		$q = "SELECT a.id,a.user_id,b.image,a.posting,c.nickname,b.n_status,a.posting_date, b.date_time,d.last_login,e.tot_login 
			FROM tbl_user_posting a
			LEFT JOIN tbl_food_image b ON a.posting_date = b.date_time
			LEFT JOIN axis_member c ON a.user_id = c.id
			LEFT JOIN (
			SELECT user_id,MAX(date_time) as last_login
			FROM `tbl_activity_log` 
			WHERE 1 
			AND action_id=1
			GROUP BY user_id
			) d ON a.user_id = d.user_id
			LEFT JOIN (
			SELECT user_id,count(user_id) as tot_login
			FROM `tbl_activity_log` 
			WHERE 1
			AND action_id=1
			GROUP BY user_id
			) e ON a.user_id = e.user_id
			WHERE a.id ='$id'
			ORDER BY a.posting_date DESC;
		";
		//print_r($q);exit;
		$list = $this->fetch($q,1);
		$this->View->assign('list',$list);
		$img_path = "images/default-icon.gif"; //images/default-icon.gif (path klo di live)
		$this->View->assign('img_path',$img_path);
		return $this->View->toString("axis/admin/user_edit.html");
		$this->close();
	}
	
	function change_status(){
		$id = intval($this->Request->getParam('id'));
		$userid = intval($this->Request->getParam('userid'));
		$name = $this->Request->getParam('name');
		$search = $this->Request->getParam('search');
		$start = $this->Request->getParam('st');
		$btn = $this->Request->getParam('btn');
		$datefoto = $this->Request->getParam('datefoto');		
		
		$sql = "SELECT n_status FROM tbl_user_posting WHERE user_id={$userid} AND posting_date='{$datefoto}' ";
		
		$this->open(0);
		$status = $this->fetch($sql,1);
		foreach($status as $v){
			$val = $v['n_status'];
		}
		$n_status = $val=="1" ? "0" : "1";
		if($datefoto!='') {
			$qry = "UPDATE tbl_user_posting SET n_status='{$n_status}' WHERE user_id={$userid} AND posting_date='{$datefoto}'";
			$qry2 = "SELECT image FROM tbl_food_image WHERE user_id={$userid} AND date_time = '{$datefoto}'";
			if($this->fetch($qry2) != NULL){
				$qry3 = "UPDATE tbl_food_image SET n_status ='0' WHERE user_id={$userid} AND date_time = '{$datefoto}'";
				if($this->query($qry3)){
					$qry4 = "UPDATE tbl_food_image SET n_status ='1' WHERE user_id={$userid} AND date_time < '{$datefoto}' ORDER BY date_time DESC LIMIT 1";
					$this->query($qry4);
				}
			}
		} else {
			$qry = "";
		}
		//var_dump($qry);exit;
		if(!$this->query($qry)){
			return $this->View->showMessage('Gagal Update Status<br />', "index.php?s=user_management&name=".$name."&search=".$search."&btn=".$btn."&st=".$start."");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s=user_management&name=".$name."&search=".$search."&btn=".$btn."&st=".$start."");
		}
		$this->close();
	}
	
	function delete_photo(){
		$this->open(0);
		$id = $this->Request->getParam('id');
		$name = $this->Request->getParam('name');
		$search = $this->Request->getParam('search');
		$start = $this->Request->getParam('st');
		$btn = $this->Request->getParam('btn');
		$userid = intval($this->Request->getParam('userid'));
		$datefoto = $this->Request->getParam('datefoto');
		
		if ($datefoto!='') {
			$qry = "UPDATE tbl_food_image SET image = 'default-icon.gif' WHERE user_id='$userid' AND date_time='$datefoto'";
			
			$qry2 = "UPDATE tbl_user_posting SET img='default-icon.gif' WHERE user_id={$userid} AND posting_date='$datefoto'";
			$this->query($qry2);
			// if($this->query($qry)){
				// $qry2 = "INSERT INTO tbl_food_image (user_id, image, n_status, date_time)
						// VALUES ({$userid}, 'default-icon.gif', '1', '{$datefoto}')";
				// $this->query($qry2);
			// }
		} else {
			$qry = "";
		}
		if(!$this->query($qry)){
			return $this->View->showMessage('Gagal menghapus photo<br />', "index.php?s=user_management&name=".$name."&search=".$search."&btn=".$btn."&st=".$start."");
		} else {
			return $this->View->showMessage('Berhasil',"index.php?s=user_management&name=".$name."&search=".$search."&btn=".$btn."&st=".$start."");
		}
		$this->close();
	}
	
	function delete_posting(){
		$this->open(0);
		$id = $this->Request->getParam('id');
		$name = $this->Request->getParam('name');
		$search = $this->Request->getParam('search');
		$start = $this->Request->getParam('st');
		$btn = $this->Request->getParam('btn');
		$qry = "UPDATE tbl_user_posting SET posting='' WHERE id='$id'";
		if(!$this->query($qry)){
			return $this->View->showMessage('Gagal menghapus photo<br />', "index.php?s=user_management");
		} else {
			return $this->View->showMessage('Berhasil',"index.php?s=user_management&name=".$name."&search=".$search."&btn=".$btn."&st=".$start."");
		}
		$this->close();
	}
}