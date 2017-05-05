<?php
global $APP_PATH;
include_once "helper/loginHelper.php";
require_once $APP_PATH . APPLICATION . "/helper/AccessControlHelper.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";


class App extends Application{
	
	var $Request;
	var $View;
	var $_mainLayout=""; 
	var $user = array();
	var $ACL;
	var $fb;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function setVar(){
		
		$this->ACL = new AccessControlHelper();
		$fb = new FacebookHelper($req);
		
	}
	
	/*set Log Activity*/
	function log($param=NULL,$id=NULL,$expLog=FALSE){
		require_once "helper/activityReportHelper.php";
		$track = new activityReportHelper();
		$track->log($param,$id,$expLog);
	}
	
	function main(){
		global $CONFIG;
		
		if($CONFIG['CLOSED_WEB']==true){
				sendRedirect($CONFIG['TEASER_DOMAIN']);
				die();
		}
		if($CONFIG['MAINTENANCE']==true){
			$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(APPLICATION . '/under-maintenance.html'));
			$this->mainLayout(APPLICATION . '/master.html');
		}else{
			
			$_SESSION['inWeb'] = true;
			// $_SESSION['login'] = true;
			// print_r('<pre>');print_r($_SESSION);exit;
			if($_SESSION['login']==false){
				$token = unserialize(urldecode64($_REQUEST['t']));
				if($token['is_liked']!=true){
					sendRedirect($CONFIG['LANDING_BASE_DOMAIN']);
					die();
				}
				$_SESSION['login'] = true;
			}		
						
			if($_SESSION['user_id']==null){
				sendRedirect($CONFIG['LANDING_BASE_DOMAIN']);
				die();
			}	
			
			
			
			if( $this->Request->getParam('page') == 'registration' && $this->Request->getParam('act') == ''){
				
				$str = $this->run();
				
				$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
				$this->assign('mainContent',$str);
				$this->mainLayout(APPLICATION . '/master.html');
			
			}else if($this->Request->getParam("page")=="under-maintenance"){
				
				$this->mainLayout(APPLICATION . '/under-maintenance.html');
				
			}
			// else if($this->Request->getParam("page")==""){
				
				// $params = array("page"=>"home","act"=>"");
				// sendRedirect("index.php?".$this->Request->encrypt_params($params));
				// die();
			
			// }
			else{
			
				
				global $FB;
				$str = $this->run();
				
				//encrypt URL AJAX
				$this->assign('getUserData',$this->Request->encrypt_params(array("page"=>"contentDownload","actAjax"=>"getUserData")));
				$this->assign('purchaseContent',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"purchaseContent")));
				$this->assign('getVerificationCode',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"getVerificationCode")));
				$this->assign('validationCode',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"validationCode")));
				$this->assign('getTablePostingAndFoodPict',$this->Request->encrypt_params(array("page"=>"home","actAjax"=>"getTablePostingAndFoodPict")));
				$this->assign('getGiftData',$this->Request->encrypt_params(array("page"=>"gift","actAjax"=>"getGiftData")));
				$this->assign('userGetGift',$this->Request->encrypt_params(array("page"=>"gift","actAjax"=>"userGetGift")));
				$this->assign('cekUnlikeLogout',$this->Request->encrypt_params(array("page"=>"home","actAjax"=>"cekUnlikeLogout")));
				
				$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
				$this->assign('header',$this->View->toString(APPLICATION . "/header.html"));
				$this->assign('footer',$this->View->toString(APPLICATION . "/footer.html"));
				$this->assign('mainContent',$str);
				$this->assign('appID', $FB['appID']);
				$this->mainLayout(APPLICATION . '/master.html');
			}		
		}
	}
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act				(nama method)
	 *	- optional		(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	function run(){
		global $APP_PATH;
		
		if($this->Request->getParam("req")) $this->Request->decrypt_params($this->Request->getParam("req"));
			
		$page = $this->Request->getParam('page');
		$act = $this->Request->getParam('act');
		
		if( $page != '' ){
			if( !is_file( $APP_PATH . APPLICATION . '/modules/'. $page . '.php' ) ){
				
				if( is_file( '../templates/'. APPLICATION . '/'. $page . '.html' ) ){
					
					return $this->View->toString(APPLICATION.'/'.$page.'.html');
				
				}else{
					
					sendRedirect("index.php");
					die();
				
				}
			
			}else{
				
				require_once 'modules/'. $page.'.php';
				$content = new $page($this->Request);
				
				if( $act != '' ){
					if( method_exists($content, $act) ){
						
						return $content->$act();
					
					}else{
						
						return $content->main();
					
					}
				}else{
					
					return $content->main();
				
				}
			
			}
		}else{
			
			require_once 'modules/home.php';
			
			$content = new home($this->Request);
			
			return $content->main();
		
		}
	}
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($year,$month,$day) = explode("-",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
	
	function is_valid_email($email) {
	  $result = TRUE;
	  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
		$result = FALSE;
	  }
	  return $result;
	}
	
	function is_email_available($email){
		//VALIDATION EMAIL TO DB (cari di table smac_registration,smac_agency & smac_user adakah yang sama?)
		$sql = "SELECT a.email FROM
						(
						SELECT r.agency_email AS email FROM smac_web.smac_registration r WHERE n_status IN ('0','1') 
						UNION
						SELECT agency_email AS email FROM smac_web.smac_agency 
						UNION
						SELECT email FROM smac_web.smac_user
						) a
						WHERE
						a.email='".mysql_escape_string(strtolower($email))."';";
		
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();		
		if($rs['email'] != ''){
			return false;
		}
		
		return true;
		
	}
	
	function is_admin(){
	
		$sql = "SELECT count(*) as total 
			FROM tbl_front_admin
			WHERE
			user_id='".mysql_escape_string(intval($_SESSION['user_id']))."' 
			AND fb_id='".mysql_escape_string(intval($_SESSION['user_login_id']))."'
			LIMIT 1
			;";
		
		$this->open(0);
		$checkAdmin = $this->fetch($sql);
		$this->close();	
		// print_r($sql);			
		if($checkAdmin) {
		$is_admin = ($checkAdmin['total']>=1) ? true : false ;
		}else $is_admin = false;
		
		return $is_admin;
	}
	
	function objectToArray($object) {
		//print_r($object);exit;
		
		 if (is_object($object)) {
		    foreach ($object as $key => $value) {
		        $array[$key] = $value;
		    }
		}
		else {
		    $array = $object;
		}
		return $array;
		
	}
	
}
?>