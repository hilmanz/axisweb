<?php

include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
global $CONFIG;
$view = new BasicView();


$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);

$landing = "axis/loading_message.html" ; 

if(!$_REQUEST['fb_id']) {
	sendRedirect($CONFIG['FB_FANS_PAGE']);
}else{
	$view->assign("urlConnect",$CONFIG['FB_DOMAIN'].'?fb_id='.$_REQUEST['fb_id']);
		if($_POST['insert']){
			
				if($_POST['fb_id']){
				
					$fb = new FacebookHelper($req);
					$data['fbid'] = strip_tags(stripslashes($_POST['fb_id']));
					
					$fbUser = $fb->checkUserDataInWeb($data['fbid']);
				
					if($fbUser['total']>=1){
						
						$data['user_id'] = $fbUser['user_id'];
						$updateStatus = updateUserStatus($data['user_id'],$fb);
					
						if($updateStatus) {
							$msg ="berhasil update status, selamat bergabung";
							$nomorAXIS = true;
							$_SESSION['login'] = true;
							$view->assign("ifAxis",$nomorAXIS);
							$view->assign("updateStatus",true);
							sendRedirect($CONFIG['FB_DOMAIN'].'?fb_id='.$data['fbid']);
							$landing ="axis/loading_message.html";
							
						}else $msg ="gagal update status, bisa di coba lagi nanti ya";
					}else $msg ="wah.. kamu belum pernah terdaftar di data kami";
			
				}else $msg ="wah.. kamu tidak terdaftar di facebook";
			}else{		
			
				$fbid=$_REQUEST['fb_id'];
				$view->assign("fbid",$fbid);
				$landing ="axis/register-update-status.html";
				
			}
		
}



print $view->toString($landing);
die();

	function updateUserStatus($userID,$fb){
		global $ENGINE_PATH;
		include_once $ENGINE_PATH."Utility/Thumbnail.php";
		$thumb = new Thumbnail(); 
		$msg = "";
		$checkSuccess = false;
		$updatePost = mysql_escape_string(htmlspecialchars( strip_tags(stripslashes($_POST['userStatus']))));
		$name = $_FILES['gambar']['name'];
		$type = $_FILES['gambar']['type'];
			
		if($name!="" && $updatePost!=""){
			$path = "/home/axis/public_html/image_update/";
			$newimg = rand(1,10000).sha1("Ymd").$userID.$name;
		
			if ($_FILES['gambar']['size'] > 2000000) {
				$msg = "Foto yang sudah kamu upload melebihi ukuran maksimal foto sebesar 2 MB.";
			}else if(($_FILES['gambar']["type"] == "image/png") || ($_FILES['gambar']["type"] == "image/gif")|| ($_FILES['gambar']["type"] == "image/jpeg")|| ($_FILES['gambar']["type"] == "image/pjpeg")){
				if(move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$newimg)){
				// print_r($_FILES);exit;
					if($thumb->createThumbnail($path.$newimg,$path."normal_".$newimg,540,400)&&
					$thumb->createThumbnail($path.$newimg,$path."medium_".$newimg,140,100)&&
					$thumb->createThumbnail($path.$newimg,$path."small_".$newimg,50,50)){
						
						$fb->open(0);
						$qry = "INSERT INTO tbl_user_posting (id, user_id, img, posting, posting_date, posting_date_ts, n_status) 
								VALUES (null,".$userID.",'".$newimg."','".$updatePost."',NOW(),".time().",1)";
						
						$upt = "UPDATE tbl_food_image a
								LEFT JOIN tbl_user_posting b
								ON a.user_id = b.user_id
								SET a.n_status = 0
								WHERE a.n_status = 1 AND a.user_id = ".$userID."";
								
						$qryIMG = "INSERT INTO tbl_food_image (user_id, image, n_status)
									VALUES (".$userID.", '".$newimg."', 1)";
									
						$rs=$fb->query($qry);
						$queryUPT=$fb->query($upt);
						$queryIMG=$fb->query($qryIMG);
						$fb->close();
						
						$msg = "Kamu telah sukses upload foto dan update status.";
						$checkSuccess = true;
						$param = 'upload_foto';
					}else $msg = "Maaf, thumbnail ga bisa di bikin dan update status kamu gagal. Silahkan coba lagi.";
					@unlink($path.$newimg);
				}else {
					$msg = "Maaf, upload foto dan update status kamu gagal. Silahkan coba lagi.";
				}
			}else{
				$msg =  "File foto yang diupload hanya diperkenankan berupa gif, jpeg dan png";
			}
		}
		elseif($name=="" && $updatePost!=""){
			$fb->open(0);
			$qry = "INSERT INTO tbl_user_posting (`id`, `user_id`, `posting`, `posting_date`, `posting_date_ts`, `n_status`) 
					VALUES (null,".$userID.",'".$updatePost."',NOW(),".time().",1)";
			$rs=$fb->query($qry);
			$fb->close();
			if ($rs){
				$msg = "Kamu telah sukses update status.";
				$checkSuccess = true;
				$param = 'update_status';
			}
		}
		elseif($name!="" && $updatePost==""){
			$msg = "Foto tidak bisa diupload. Kamu harus tulis dulu statusmu.";
		}else if($name=="" && $updatePost==""){
			$msg = "Silahkan kamu memasukan status atau memilih foto terlebih dahulu.";
		}else{
			$msg = "Maaf, karena gangguan teknis, saat ini kamu tidak bisa upload foto.";
		}
		
		return $checkSuccess;
	}

?>