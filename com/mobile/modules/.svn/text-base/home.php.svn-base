<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/dateHelper.php';
class home extends App{
	var $Request;
	var $View;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->dateHelper = new dateHelper();
		$this->setVar();
		$this->run();
	}
	
	function run(){
		if ($this->Request->getParam('act')=='updateStatus'){
			$update = $this->updateStatus();
			$this->View->assign('msg', '<span id="msg" class="msgBox">'.$update.'</span>');
			return $this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION .'/home.html');
		}else{
			$this->main();
		}
	}
	function main(){
	// print_r('<pre>');print_r($_SESSION);
		$page=$this->Request->getParam('p');
		$post = $this->obrolanMeja($page);				
		
		if( $page==0) $page =1;
		if ($post['total'] == $page){
			$paging['next'] = $post['total'];
		}else{
			$paging['next'] = $page+1;
		}
		if( $page<=0) $page =0;
		$page = $page-1;
		if( $page<=0) $page=1;
		$paging['prev'] = $page;
		
		
		$this->View->assign('paging', $paging);
		$this->View->assign('obrolan', $post['posting']);
		$this->View->assign('lastPost', $post['lastPost']);
		$this->View->assign('timePosting', $post['postTime']);		
		return $this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION .'/home.html');
	}
	
	function obrolanMeja($page=0,$total=5){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		 // print_r($userID);exit;
		
		$this->open(0);
		
		$page = $page*$total-$total;
		if($page<=1)$page = 0;	
		// $page=0;
		$sqlImg = "SELECT a.img, a.user_id
					FROM tbl_user_posting a
					LEFT JOIN axis_member b
					ON a.user_id = b.id
					LEFT JOIN tbl_user_fb c
					ON a.user_id = c.user_id
					WHERE 1
					ORDER BY a.posting_date DESC";
		$sql3 = "SELECT a.posting, a.img, a.user_id, a.posting_date, a.posting_date_ts, c.fb_id, b.nickname
					FROM tbl_user_posting a
					LEFT JOIN axis_member b
					ON a.user_id = b.id
					LEFT JOIN tbl_user_fb c
					ON a.user_id = c.user_id
					WHERE a.n_status = 1
					ORDER BY a.posting_date DESC LIMIT {$page},{$total}";
		$posting = $this->fetch($sql3,1);
		$imgUpdate = $this->fetch($sqlImg,1);
		
		$this->close();
		 //print_r(sizeof($imgUpdate));exit;
		$row = $page;
		$endRow = $page + ($total-1);
		$i=0;$y = $row;
		$size = sizeof($imgUpdate);
		$found = false;
		foreach($posting as $postDate){
			$postTime[] = $this->dateHelper->hoursCalendar($postDate['posting_date']);
			if ($endRow >= $row && $posting[$i]['img'] == null){
				if ($imgUpdate[$row]['img'] == null){
					for($y;$y<$size;$y++){
						if($imgUpdate[$y]['img'] != null && $imgUpdate[$y]['user_id'] == $posting[$i]['user_id']){
							$posting[$i]['img'] = "".$CONFIG['MOBILE_DOMAIN']."image_update/small_".$imgUpdate[$y]['img']."";
							$found = true;
							break;
						}else{
							$found = false;
						}	
					}
					if($found == false){
						$posting[$i]['img'] = "".$CONFIG['MOBILE_DOMAIN']."images/default-icon.gif";
					}
					$row++;
					$i++;
				}else{
					$posting[$i]['img'] = "".$CONFIG['MOBILE_DOMAIN']."image_update/small_".$imgUpdate[$row]['img']."";
					$row++;
					$i++;
				}			
			}else{
				$posting[$i]['img'] = "".$CONFIG['MOBILE_DOMAIN']."image_update/small_".$posting[$i]['img']."";
				$i++;
				$row++;
				$y++;
			}
		}
		  // print_r($i);exit;
		$data['posting'] = $posting;
		$data['lastPost'] = $posting[0]['posting_date_ts'];
		$data['postTime'] = $postTime;
		$data['total'] = ceil($size/5);
		return $data;
	}
	
	function updateStatus(){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";
		$thumb = new Thumbnail(); 
		
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		
		$msg = "";
		$updatePost = mysql_escape_string(htmlspecialchars(stripslashes(strip_tags($this->Request->getPost('userStatus')))));
		$name = $_FILES['gambar']['name'];
		$tipe = $_FILES['gambar']['type'];
		
		// print_r($tipe);exit;
		if ($jmlChar > 140){
				$msg = "Maaf Beb, status kamu lebih dari 140 karakter.";
		}else{
		if($name!="" && $updatePost!=""){
			$path = "/home/axis/public_html/image_update/";
			// var_dump($path);exit;
			$newimg = rand(1,10000).sha1("Ymd").$userID.$name;
			
			if(($_FILES['gambar']["type"] == "image/png") || ($_FILES['gambar']["type"] == "image/jpg")|| ($_FILES['gambar']["type"] == "image/jpeg")|| ($_FILES['gambar']["type"] == "image/pjpeg")){
				if ($_FILES['gambar']['size'] > 200000) {
					$msg = "Photo yang sudah kamu upload melebihi ukuran maksimal photo sebesar 200 KB.";
				}else if(move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$newimg)){
					if($thumb->createThumbnail($path.$newimg,$path."normal_".$newimg,540, 400) && $thumb->createThumbnail($path.$newimg,$path."medium_".$newimg,140,100) && $thumb->createThumbnail($path.$newimg,$path."small_".$newimg,50,50)){
						
						$this->open(0);
						$qry = "INSERT INTO tbl_user_posting (id, user_id, img, posting, posting_date, posting_date_ts, n_status) 
								VALUES (null,".$userID.",'".$newimg."','".$updatePost."',NOW(),".time().",1)";
						
						$upt = "UPDATE tbl_food_image a
								LEFT JOIN tbl_user_posting b
								ON a.user_id = b.user_id
								SET a.n_status = 0
								WHERE a.n_status = 1 AND a.user_id = ".$userID."";
								
						$qryIMG = "INSERT INTO tbl_food_image (user_id, image, n_status, date_time)
									VALUES (".$userID.", '".$newimg."', 1,NOW())";
							
						$rs=$this->query($qry);
						$queryUPT=$this->query($upt);
						$queryIMG=$this->query($qryIMG);
						
							
						$this->close();
						
						@unlink($path.$newimg);
						$msg = "Kamu telah sukses upload photo dan update status.";	
						$checkSuccess = true;
						$param = 'upload_foto';
					}else{
						$msg = "Maaf, upload photo dan update status kamu gagal. Silahkan coba lagi.";
					}
				}
			}else{
				$msg =  "File photo yang diupload harus dalam format jpg, jpeg dan png ya Beb.";
			}
		}else if($name=="" && $updatePost!=""){
			$this->open(0);
			$qry = "INSERT INTO tbl_user_posting (id, user_id, posting, posting_date, posting_date_ts, n_status) 
					VALUES (null,".$userID.",'".$updatePost."',NOW(),".time().",1)";
			$rsz=$this->query($qry);
			$this->close();
			if ($rsz){
				$msg = "Kamu telah sukses update status.";
				$checkSuccess = true;
				$param = 'update_status';
			}
		}
		elseif($name!="" && $updatePost==""){
			$msg = "Photo tidak bisa diupload. Kamu harus tulis dulu statusmu.";
		}else if($name=="" && $updatePost==""){
			$msg = "Beb, jangan lupa upload photo dan status kamu dulu ya..";
		}else{
			$msg = "Maaf, karena gangguan teknis, saat ini kamu tidak bisa upload photo.";
		}
		}
		/*if the process is success, it'll tracked*/
		if ($checkSuccess == true){
			$this->log($param, $userID, true);
		}
		return $msg;
		// sendRedirect('/axis/mobile/');
		// $this->View->assign('msg', $msg);
		// return $this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION .'/home.html');
		
	}
}
