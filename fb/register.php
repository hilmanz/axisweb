<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
global $CONFIG;
$view = new BasicView();


$view->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
// print_r($_SESSION);exit;
$landing = "axis/loading_message.html" ; 

if(!$_REQUEST['fb_id']) {
	sendRedirect($CONFIG['FB_FANS_PAGE']);
}else{
	if($_SESSION['register']){

		if($_POST['insert']){
				if($_POST['fb_id']){
					$fb = new FacebookHelper($req);
					$data['nickname'] = strip_tags(stripslashes($_POST['inputNickname']));
					$data['no_hp'] = strip_tags(stripslashes($_POST['inputPhone']));
					$data['fbid'] = strip_tags(stripslashes( $_POST['fb_id']));
					
					$nomorHP = $data['no_hp'];
					/*helper=> Check nomor HP*/
					$zero = substr($nomorHP,0,1);
					$enamDuaNol = substr($nomorHP,0,3);
					$enamDuaPlus = substr($nomorHP,0,3);
					if($zero == '0'){
						$nomorHP = '62'.substr($nomorHP,1);
					}else if($enamDuaPlus == '+62'){
						$nomorHP = '62'.substr($nomorHP,3);
					}else if($enamDuaNol == '620'){
						$nomorHP = '62'.substr($nomorHP,3);
					}
					
					$data['no_hp'] = $nomorHP;
					
					/*check Nomor Axis*/
					$noAxis = substr($nomorHP,0,5);
					if($noAxis == '62838' || $noAxis == '62831'){
						$nomorAXIS = true;
					}else{
						$nomorAXIS = false;
					}
					
					$fbUser = $fb->checkUserhasBeenRgister($data['fbid']);
			
					if($fbUser['total']>=1){
				
					$data['user_id'] = $fbUser['user_id'];
					$registration = $fb->registrationUser($data);
					
						if($registration) {
							
							//$asd = $fb->flag_for_like($data['user_id'],true);
							// print_r($user_id);
							// exit;
							$msg ="berhasil registrasi, selamat bergabung";
							$_SESSION['register'] = false;
							$_SESSION['login'] = true;
							$view->assign("ifAxis",$nomorAXIS);
							$view->assign("urlUpdateStatus",$CONFIG['UPDATE_STATUS_DOMAIN'].'?fb_id='.$data['fbid']);
							$view->assign("urlConnect",$CONFIG['FB_DOMAIN'].'?fb_id='.$data['fbid']);
							$landing ="axis/register-messages.html";
						}else $msg ="gagal registrasi, bisa di coba lagi nanti ya";
					}else $msg ="wah.. kamu belum pernah terdaftar di data kami";
			
				}else $msg ="wah.. kamu tidak terdaftar di facebook";
			}else{		
			
				$fbid=$_REQUEST['fb_id'];
				$view->assign("fbid",$fbid);
				$landing ="axis/registration.html";
				
			}

	}else {
		sendRedirect($CONFIG['FB_FANS_PAGE']);
	}
}


print $view->toString($landing);
die();
?>