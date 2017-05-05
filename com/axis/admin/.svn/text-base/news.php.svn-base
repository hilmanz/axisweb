<?php

global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class news extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'add_news' ){
			return $this->add_news();
		}elseif( $act == 'edit_news' ){
			return $this->edit_news();
		}elseif( $act == 'update_news' ){
			return $this->update_news();
		}elseif( $act == 'delete_news' ){
			return $this->delete_news();
		}else{
			return $this->listing_news();
		}
	}

	function listing_news(){
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT * FROM tbl_news_ticker ORDER BY id;";
		$this->open(0);
		$list = $this->fetch($qry,1);
		$this->close();
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news"));		
		return $this->View->toString("axis/admin/news_list.html");
	}
	
	function add_news(){
		$add = intval($this->Request->getPost('add'));
		$this->open(0);
		if( $add == 1){
			$title = $this->Request->getPost(mysql_escape_string('title'));
			$detail = $this->Request->getPost(mysql_escape_string('detail'));
			$status = $this->Request->getPost('status');
			if( $title != ''){
				$que = "INSERT IGNORE INTO tbl_news_ticker (title,description,n_status) 
						VALUES ('$title','$detail','$status');";
				if(!$this->query($que)){
					$err = 'Save failed';
					return $this->View->showMessage($err, "index.php?s=news&act=add_news");
				}else{
					$err = 'Berhasil';
					return $this->View->showMessage($err, "index.php?s=news");
				}			
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err, "index.php?s=news&act=add_news");
			}
		}else{
			$err = 'Save failed';
		}
		return $this->View->toString("axis/admin/news_new.html");
		$this->close();
	}
	
	function edit_news(){
		$this->open(0);
		$id = $this->Request->getParam('id');
		$qry = "SELECT * FROM tbl_news_ticker WHERE id='$id' LIMIT 1;";
		$r = $this->fetch($qry);
		$this->close();
		$this->View->assign("id", $r['id']);
		$this->View->assign("title", $r['title']);		
		$this->View->assign("description", $r['description']);
		$this->View->assign("status", $r['n_status']);
		return $this->View->toString("axis/admin/news_edit.html");
	}
	
	function update_news(){
		$update = intval($this->Request->getPost('update_news'));
		$id = intval($this->Request->getPost('id'));
		$err = "";
		if( $update == 1){
			$_id = intval($this->Request->getPost('id'));
			$title = $this->Request->getPost(mysql_escape_string('title'));
			$description = $this->Request->getPost(mysql_escape_string('detail'));
			$status = $this->Request->getPost('status');
			if( $title != '' || $description != '' || $status != ''){
				$this->open(0);
				$que = "UPDATE tbl_news_ticker SET 
							title='$title',
							description='$description',
							n_status='$status'
						WHERE id='$_id'";
				if(!$this->query($que)){
					$err = 'Update failed';
					return $this->View->showMessage($err, "index.php?s=news&act=edit_news&id=$id");
				}else{
					$err = 'Update Berhasil';
					return $this->View->showMessage($err, "index.php?s=news");
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err,"index.php?s=news&act=edit_news&id=$id");
			}
		}else{
			$err = 'Update failed';
		}
		$this->close();
		return $this->View->toString("marlboro/admin/news-edit.html");
	}
	
	function delete_news(){
		$id = $this->Request->getParam('id');
		$act = $this->Request->getParam('act');
		$this->open(0);
		$qry = "DELETE FROM tbl_news_ticker WHERE id='$id';";		
		if(!$this->query($qry)){
			return $this->View->showMessage('Gagal menghapus News<br />', "index.php?s=news");
		}else{
			return $this->View->showMessage('Berhasil',"index.php?s=news");
		}
		$this->close();
	}
}