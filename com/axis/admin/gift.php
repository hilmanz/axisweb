<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class gift extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'add_gift' ){
			return $this->add_gift();
		}elseif( $act == 'edit_gift' ){
			return $this->edit_gift();
		}elseif( $act == 'update_gift' ){
			return $this->Update_gift();
		}elseif( $act == 'delete_gift' ){
			return $this->Delete_gift();
		}else{
			return $this->listing_gift();
		}
	}
	
	function listing_gift($total_per_page=20){
		$start = intval($this->Request->getParam('st'));
		if($start==NULL){$start = 0;}		
		$this->open(0);
		$q="SELECT * FROM tbl_gift a WHERE n_status=1";
		$r = $this->fetch($q,1);
		$total = count($r);
		
		$qry = "SELECT * FROM tbl_gift WHERE n_status=1 ORDER BY id 
				LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$img_path = "../public_html/content/gift";
		$this->View->assign('img_path',$img_path);
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=user_management&act=gift"));
		return $this->View->toString("axis/admin/gift_list.html");
		$this->close();
	}
	
	function add_gift(){
		$add = intval($this->Request->getPost('add'));
		$img = intval($this->Request->getPost('image'));
		$this->open(0);
		$err = "";
		if( $add == 1){
			$gift_name = $this->Request->getPost(mysql_escape_string('gift_name'));
			$n_status = 1;
			if( $gift_name != ''){
				if($_FILES['image']['name']!=""){
					$img_name = $_FILES['image']['name'];
					$img_type = $_FILES['image']['type'];
					$img_newname = "gift_".date('YmdHis');
					
					//declare extension primary image
					if($img_type=='image/jpeg'){$ext = '.jpg';}
					if($img_type=='image/png'){$ext = '.png';}
					if($img_type=='image/gif'){$ext = '.gif';}
					$newfile = $img_newname.$ext;
					move_uploaded_file($_FILES['image']['tmp_name'], "../public_html/content/gift/".$newfile);				
				} else {
					$newfile = $img;
				}				
				
				$que = "INSERT IGNORE INTO tbl_gift (gift_name,image,n_status) 
						VALUES ('$gift_name','$newfile','$n_status');";
				
				if(!$this->query($que)){
					$err = 'Save failed';
					return $this->View->showMessage($err, "index.php?s=user_management&act=add_gift");
				}else{
					$err = 'Berhasil';
					return $this->View->showMessage($err, "index.php?s=user_management&act=gift");
				}			
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err, "index.php?s=user_management&act=add_gift");
			}			
		}else{
			$err = 'Save failed';
		}
		return $this->View->toString("axis/admin/gift_new.html");
		$this->close();
	}
	
	function edit_gift(){
		$this->open(0);
		$id = $this->Request->getParam('id');
		$qry = "SELECT * FROM tbl_gift WHERE id='$id' LIMIT 1;";
		$r = $this->fetch($qry);
		$this->close();
		$this->View->assign("id", $r['id']);
		$this->View->assign("gift_name", $r['gift_name']);		
		$this->View->assign("img", $r['image']);
		$this->View->assign("status", $r['n_status']);
		return $this->View->toString("axis/admin/gift_edit.html");
	}
	
	function Update_gift(){
		$update = intval($this->Request->getPost('update_gift'));
		$id = intval($this->Request->getPost('id'));
		$err = "";
		if( $update == 1){
			$_id = intval($this->Request->getPost('id'));
			$gift_name = $this->Request->getPost(mysql_escape_string('gift_name'));
			$img = $this->Request->getPost('currimg');
			if( $gift_name != ''){
				if($_FILES['image']['name']!=""){
					$img_name = $_FILES['image']['name'];
					$img_loc = $_FILES['image']['tmp_name'];
					$img_type = $_FILES['image']['type'];
					$img_newname = "gift_".date('YmdHis');
					
					//declare extension primary image
					if($img_type=='image/jpeg'){$ext = '.jpg';}
					if($img_type=='image/png'){$ext = '.png';}
					if($img_type=='image/gif'){$ext = '.gif';}
					
					// new file
					$newfile = $img_newname.$ext; // new file name for primary image
					$thumbfile = "thumb_".$newfile; // new file name for thumbnail image
					move_uploaded_file($_FILES['image']['tmp_name'],"../public_html/content/gift/".$newfile);
				}else{
					$newfile = $img;
				}
				$this->open(0);
				$que = "UPDATE tbl_gift SET 
							gift_name='$gift_name',
							image='$newfile'
						WHERE id='$id'";
				if(!$this->query($que)){
					$err = 'Update failed';
					return $this->View->showMessage($err, "index.php?s=user_management&act=edit_gift&id=$id");
				}else{
					$err = 'Update Berhasil';
					return $this->View->showMessage($err, "index.php?s=user_management&act=gift");
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err,"index.php?s=user_management&act=edit_gift&id=$id");
			}
		}else{
			$err = 'Update failed';
		}
		$this->close();
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/news-edit.html");
	}
	
	function Delete_gift(){
		$id = $this->Request->getParam('id');
		$act = $this->Request->getParam('act');
		$this->open(0);
		$qry = "UPDATE tbl_gift SET n_status='0' WHERE id='$id';";		
		if(!$this->query($qry)){
			return $this->View->showMessage('Gagal menghapus photo<br />', "index.php?s=user_management&act=gift");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s=user_management&act=gift");
		}
		$this->close();
	}
}