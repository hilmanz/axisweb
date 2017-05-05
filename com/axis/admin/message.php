<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class message extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'add_message' ){
			return $this->add_message();
		}elseif( $act == 'edit_message' ){
			return $this->edit_message();
		}elseif( $act == 'update_message' ){
			return $this->update_message();
		}elseif( $act == 'delete_message' ){
			return $this->delete_message();
		}else{
			return $this->listing_message();
		}
	}

	function listing_message(){
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT * 
				FROM tbl_admin_posting
				WHERE 1 ORDER BY posting_date
				DESC";
		$this->open(0);
		$list = $this->fetch($qry,1);
		$img_path = "../public_html/images";
		$this->View->assign('img_path',$img_path);
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=message"));		
		return $this->View->toString("axis/admin/message_list.html");
		$this->close();
	}
	
	function add_message(){
		$add = intval($this->Request->getPost('add'));
		$img = intval($this->Request->getPost('image'));
		$this->open(0);
		if( $add == 1){
			$posting = $this->Request->getPost(mysql_escape_string('posting'));
			$status = $this->Request->getPost('status');
			if( $posting != ''){
				if($_FILES['image']['name']!=""){
					$img_name = $_FILES['image']['name'];
					$img_type = $_FILES['image']['type'];
					$img_newname = "message_".date('YmdHis');
					
					//declare extension primary image
					if($img_type=='image/jpeg'){$ext = '.jpg';}
					if($img_type=='image/png'){$ext = '.png';}
					if($img_type=='image/gif'){$ext = '.gif';}
					$newfile = $img_newname.$ext;
					move_uploaded_file($_FILES['image']['tmp_name'], "../public_html/images/".$newfile);
				} else {
					$newfile = $img;
				}
				
				$post_date = date('Y-m-d H:i:s');
				$post_date_ts = time();
				/* print_r($post_date);
				print_r($post_date_ts);exit; */
				$que = "INSERT IGNORE INTO tbl_admin_posting (img,posting,n_status,posting_date,posting_date_ts)
						VALUES ('$newfile','$posting','$status','$post_date','$post_date_ts');";
				if(!$this->query($que)){
					$err = 'Save failed';
					return $this->View->showMessage($err, "index.php?s=message&act=add_message");
				}else{
					$err = 'Berhasil';
					return $this->View->showMessage($err, "index.php?s=message");
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err, "index.php?s=message&act=add_message");
			}
		}else{
			$err = 'Save failed';
		}
		return $this->View->toString("axis/admin/message_new.html");
		$this->close();
	}
	
	function edit_message(){
		$this->open(0);
		$id = $this->Request->getParam('id');
		$qry = "SELECT * FROM tbl_admin_posting WHERE id='$id' LIMIT 1;";
		$r = $this->fetch($qry);
		$this->close();
		$this->View->assign("id", $r['id']);
		$this->View->assign("img", $r['img']);
		$this->View->assign("posting", $r['posting']);
		$this->View->assign("status", $r['n_status']);
		return $this->View->toString("axis/admin/message_edit.html");
	}
	
	function update_message(){
		$update = intval($this->Request->getPost('update_message'));
		$id = intval($this->Request->getPost('id'));
		$err = "";
		if( $update == 1){
			$_id = intval($this->Request->getPost('id'));
			$posting = $this->Request->getPost(mysql_escape_string('posting'));
			$img = $this->Request->getPost('currimg');
			$status = $this->Request->getPost('status');
			if( $posting != '' || $status != ''){
				if($_FILES['image']['name']!=""){
					$img_name = $_FILES['image']['name'];
					$img_loc = $_FILES['image']['tmp_name'];
					$img_type = $_FILES['image']['type'];
					$img_newname = "message_".date('YmdHis');
					
					//declare extension primary image
					if($img_type=='image/jpeg'){$ext = '.jpg';}
					if($img_type=='image/png'){$ext = '.png';}
					if($img_type=='image/gif'){$ext = '.gif';}
					
					// new file
					$newfile = $img_newname.$ext; // new file name for primary image
					$thumbfile = "thumb_".$newfile; // new file name for thumbnail image
					move_uploaded_file($_FILES['image']['tmp_name'],"../public_html/images/".$newfile);
				}else{
					$newfile = $img;
				}
				$this->open(0);
				$post_date = date('Y-m-d H:i:s');
				$post_date_ts = time();
				$que = "UPDATE tbl_admin_posting SET 
							img='$newfile',
							posting='$posting',
							n_status='$status',
							posting_date='$post_date',
							posting_date_ts='$post_date_ts'
						WHERE id='$_id'";
				if(!$this->query($que)){
					$err = 'Update failed';
					return $this->View->showMessage($err, "index.php?s=message&act=edit_message&id=$id");
				}else{
					$err = 'Update Berhasil';
					return $this->View->showMessage($err, "index.php?s=message");
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err,"index.php?s=message&act=edit_message&id=$id");
			}
		}else{
			$err = 'Update failed';
		}
		$this->close();
		return $this->View->toString("marlboro/admin/message_edit.html");
	}
	
	function delete_message(){
		$id = $this->Request->getParam('id');
		$act = $this->Request->getParam('act');
		$this->open(0);
		$qry = "DELETE FROM tbl_admin_posting WHERE id='$id';";
		if(!$this->query($qry)){
			return $this->View->showMessage('Gagal menghapus Message<br />', "index.php?s=message");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s=message");
		}
		$this->close();
	}
}