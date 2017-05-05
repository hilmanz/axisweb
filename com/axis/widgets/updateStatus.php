<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/ImagesHelper.php';

class updateStatus extends App{
	var $Request;
	var $View;
	var $ImagesHelper;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->ImagesHelper = new ImagesHelper();
		$this->run();
	}
	
	function run(){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		if($this->Request->getParam('act')=='updateStatus'){
			$this->updateUserStatus($userID);
		}elseif($this->Request->getParam('act')=='statusAdmin'){
			
			$this->updateStatusAdmin();
		}else{
			$this->main();
		}
	}
	
	function main(){
	
		$this->assign('is_admin',$this->is_admin());
		// print_r($this->is_admin($_SESSION['user_id']));
		return $this->View->toString(APPLICATION.'/widgets/updateStatus.html');		
	}
	
	function updateUserStatus($userID){
		// $resttimefrom = 100000;
		// $resttimeto = 190000;
		// $currentTime = (int) date('His');
		// if ($currentTime > $resttimefrom && $currentTime < $resttimeto ){
			global $ENGINE_PATH;
			include_once $ENGINE_PATH."Utility/Thumbnail.php";
			$thumb = new Thumbnail(); 
			$msg = "";
			$checkSuccess = false;
			$updatePost = nl2br($this->Request->getPost('userStatus'));
			$jmlChar =  intval(strlen($updatePost));
			$name = $_FILES['gambar']['name'];
			$type = $_FILES['gambar']['type'];
			if ($jmlChar > 140){
				$msg = "Maaf Beb, status kamu lebih dari 140 karakter.";
			}else{
			if($name!="" && $updatePost!=""){
				$path = "/home/axis/public_html/image_update/";
				$newimg = rand(1,10000).sha1("Ymd").$userID.$name;
				
				if(($_FILES['gambar']["type"] == "image/png") || ($_FILES['gambar']["type"] == "image/jpg")|| ($_FILES['gambar']["type"] == "image/jpeg")|| ($_FILES['gambar']["type"] == "image/pjpeg")){
					if ($_FILES['gambar']['size'] > 200000) {
						$msg = "Photo yang sudah kamu upload melebihi ukuran maksimal foto sebesar 200 KB.";
					}else if(move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$newimg)){
						if($thumb->createThumbnail($path.$newimg,$path."normal_".$newimg,540,400)&&
						$thumb->createThumbnail($path.$newimg,$path."medium_".$newimg,140,100)&&
						$thumb->createThumbnail($path.$newimg,$path."small_".$newimg,50,50)){
							
							$this->open(0);
							$qry = "INSERT INTO tbl_user_posting (id, user_id, img, posting, posting_date, posting_date_ts, n_status) 
									VALUES (null,".$userID.",'".$newimg."','".$updatePost."',NOW(),".time().",1)";
							
							$upt = "UPDATE tbl_food_image a
									LEFT JOIN tbl_user_posting b
									ON a.user_id = b.user_id
									SET a.n_status = 0
									WHERE a.n_status = 1 AND a.user_id = ".$userID."";
									
							$qryIMG = "INSERT INTO tbl_food_image (user_id, image, n_status, date_time)
										VALUES (".$userID.", '".$newimg."', 1, NOW())";
										
							$rs=$this->query($qry);
							$queryUPT=$this->query($upt);
							$queryIMG=$this->query($qryIMG);
							$this->close();
							
							$msg = "Kamu telah sukses upload photo dan update status.";
							$checkSuccess = true;
							$param = 'upload_foto';
						}else $msg = "Maaf, thumbnail ga bisa di bikin dan update status kamu gagal. Silahkan coba lagi.";
						@unlink($path.$newimg);
					}else {
						$msg = "Maaf, upload photo dan update status kamu gagal. Silahkan coba lagi.";
					}
				}else{
					$msg =  "File photo yang diupload harus dalam format jpg, jpeg dan png ya Beb.";
				}
			}
			elseif($name=="" && $updatePost!=""){
				$this->open(0);
				$qry = "INSERT INTO tbl_user_posting (`id`, `user_id`, `posting`, `posting_date`, `posting_date_ts`, `n_status`) 
						VALUES (null,".$userID.",'".$updatePost."',NOW(),".time().",1)";
				$rs=$this->query($qry);
				$this->close();
				if ($rs){
					$msg = "Kamu telah sukses update status.";
					$checkSuccess = true;
					$param = 'update_status';
				}
			}
			elseif($name!="" && $updatePost==""){
				$msg = "Photo tidak bisa diupload. Kamu harus tulis dulu statusmu.";
			}else if($name=="" && $updatePost==""){
				$msg = "Beb, jangan lupa upload photo dan status kamu dulu ya.";
			}else{
				$msg = "Maaf, karena gangguan teknis, saat ini kamu tidak bisa upload photo.";
			}
			
			/*if the process is success, it'll tracked*/
			if ($checkSuccess == true){
				$this->log($param, $userID, true);
			}
			}
		// }else{
			// $msg = '<div style="margin-top:-70px;"><p style="font-size: 25px"> Beb, kamu bisa upload <br />
			// foto makanan dan statusmu <br />
			// pukul 10.00 - 19.00 WIB.</p>
			// <p>Hadiah Barokah akan mulai ada<br />
			 // di meja pukul 16.00 - 18.00 WIB.<br />
			// Jangan telat login ya!</p></div>';
		// }
		print_r($msg);exit;
	}
	
	function updateStatusAdmin(){
		$msg = "Kamu Bukan Admin Ya Bep";
		if( $this->is_admin()!=1 ) {print_r($msg); exit;}
	
		// $resttimefrom = 100000;
		// $resttimeto = 240000;
		// $currentTime = (int) date('His');
	
		$userID = $_SESSION['user_id'];
		// if ($currentTime > $resttimefrom && $currentTime < $resttimeto ){
		
			$updatePost = nl2br($this->Request->getPost('userStatus'));
						
							$qry = "INSERT INTO tbl_admin_posting (id, posting, posting_date, posting_date_ts, n_status) 
									VALUES (null,'".$updatePost."',NOW(),".time().",1)";
							$this->open(0);
							$rs=$this->query($qry);
							$this->close();
					
							$msg = "Kamu telah sukses update status admin kamu.";
							
			if($rs) $this->log($param, $userID, true);
			
		// }else{
			// $msg = '<div style="margin-top:-70px;"><p style="font-size: 25px"> Beb, kamu bisa upload <br />
			// foto makanan dan statusmu <br />
			// pukul 10.00 - 19.00 WIB.</p>
			// <p>Hadiah Barokah akan mulai ada<br />
			 // di meja pukul 16.00 - 18.00 WIB.<br />
			// Jangan telat login ya!</p></div>';
		// }
	
		print_r($msg);exit;
	}
	
	
	
}
