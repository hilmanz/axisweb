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
		require_once $APP_PATH.BERKAHAPPLICATION."/helper/activityReportHelper.php";
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
			$this->assign('meta',$this->View->toString(BERKAHAPPLICATION . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(BERKAHAPPLICATION . '/under-maintenance.html'));
			$this->mainLayout(BERKAHAPPLICATION . '/master.html');
		}else{
			
				$_SESSION['inMobile'] = true;
				// print_r($_SESSION);exit;
				// if($_SESSION['login']==false){
					// $_SESSION['login'] = true;
				// }
				
				if($_SESSION['user_id']==null){
						sendRedirect($CONFIG['BOTH_LANDING_PAGE']);
						die();
				}
				$str = $this->run();
				// print_r('<pre>');print_r('asd');exit;
				global $CONFIG;
				
				$this->assign('username',$_SESSION['twit_id']);
				
				
				$this->assign('meta',$this->View->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION . "/meta.html"));
				$this->assign('header',$this->View->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION . "/header.html"));
				$this->assign('footer',$this->View->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION . "/footer.html"));
				// print_r($str);exit;
				$this->assign('mainContent',$str);
				
				$this->mainLayout(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION . '/master.html');	
		}
	}
	

	function run(){
		global $APP_PATH;
		
		$page = $this->Request->getParam('page');
		$act = $this->Request->getParam('act');
				
		if( $page != '' ){
			if( !is_file( $APP_PATH . MOBILEAPPLICATIONBERKAH . '/modules/'. $page . '.php' ) ){
				
				if( is_file( '../templates/'. BERKAHAPPLICATION.'/'.MOBILEAPPLICATION . '/'. $page . '.html' ) ){
					
					return $this->View->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION.'/'.$page.'.html');
				
				}else{
					
					sendRedirect("index.php");
					die();
				
				}
			
			}else{
				
				require_once  $APP_PATH . MOBILEAPPLICATIONBERKAH .'/modules/'. $page.'.php';
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
			
			
			require_once  $APP_PATH . MOBILEAPPLICATIONBERKAH .'/modules/home.php';
			// if(file_exists($APP_PATH . MOBILEAPPLICATION .'/modules/home.php')) echo 'ada';
		
			$content = new home($this->Request);
				
			return $content->main();
		
		}
	}

	
}
?>