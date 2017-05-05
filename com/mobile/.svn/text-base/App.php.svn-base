<?php
global $APP_PATH;
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";

class App extends Application{
	
	var $Request;
	var $View;
	var $_mainLayout=""; 
	var $user = array();
	var $fb;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function setVar(){		
	
		$fb = new FacebookHelper($req);
		// print_r();
		// print_r($fb->isLiked($_SESSION['user_login_id']));exit;
	}
	
	/*set Log Activity*/
	function log($param=NULL,$id=NULL,$expLog=FALSE){
		global $APP_PATH;
		require_once $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
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
			
			$_SESSION['inMobile'] = true;
			// print_r($_SESSION);exit;
		
			if($_SESSION['login']==false){
				sendRedirect($CONFIG['BOTH_LANDING_PAGE']);
			}else{
				$this->View->assign("mobileUrl",$CONFIG['MOBILE_DOMAIN']);
				
				$this->open(0);				
				$sql = "SELECT is_like 
						FROM tbl_fb_like
						WHERE fb_id={$_SESSION['user_login_id']} LIMIT 1
						";
				$isLike = $this->fetch($sql);			
				
				if(!$isLike['is_like']){
					echo "<script>console.log(".$isLike['is_like'].$_SESSION['user_login_id'].")</script>";
					print $this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION . '/mobile_dislike.html');
					die();
				}
			
				$q = "SELECT nickname FROM axis_member WHERE id=".$_SESSION['user_id']."";
			
				$nick = $this->fetch($q);
								
				$sql = "SELECT * 
						FROM tbl_admin_posting
						WHERE 1 ORDER BY posting_date DESC LIMIT 1
						";
				$adminHAP = $this->fetch($sql);				
				
				$this->close();
				$this->View->assign('adminHAP', $adminHAP);
				$this->assign('username',$nick['nickname']);
				
			
				$str = $this->run();
				// print_r('<pre>');print_r('asd');exit;
				global $CONFIG;
				
				
				$this->assign('meta',$this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION . "/meta.html"));
				$this->assign('header',$this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION . "/header.html"));
				$resttimefrom = 100000;
				$resttimeto = 190000;

				$currentTime = (int) date('His');
				if ($currentTime > $resttimefrom && $currentTime < $resttimeto )
				{
					$this->assign('updatestatus',$this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION . "/updatestatus.html"));
				}
				$this->assign('footer',$this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION . "/footer.html"));
				// print_r($str);exit;
				$this->assign('mainContent',$str);
				
				$this->mainLayout(APPLICATION.'/'.MOBILEAPPLICATION . '/master.html');
			}		
		}
	}
	

	function run(){
		global $APP_PATH;
		
		$page = $this->Request->getParam('page');
		$act = $this->Request->getParam('act');
				
		if( $page != '' ){
			if( !is_file( $APP_PATH . MOBILEAPPLICATION . '/modules/'. $page . '.php' ) ){
				
				if( is_file( '../templates/'. APPLICATION.'/'.MOBILEAPPLICATION . '/'. $page . '.html' ) ){
					
					return $this->View->toString(APPLICATION.'/'.MOBILEAPPLICATION.'/'.$page.'.html');
				
				}else{
					
					sendRedirect("index.php");
					die();
				
				}
			
			}else{
				
				require_once  $APP_PATH . MOBILEAPPLICATION .'/modules/'. $page.'.php';
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
			
			
			require_once  $APP_PATH . MOBILEAPPLICATION .'/modules/home.php';
			// if(file_exists($APP_PATH . MOBILEAPPLICATION .'/modules/home.php')) echo 'ada';
		
			$content = new home($this->Request);
				
			return $content->main();
		
		}
	}

	
}
?>