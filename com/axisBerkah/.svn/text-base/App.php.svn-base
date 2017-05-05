<?php
global $APP_PATH;
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";


class App extends Application{
	
	var $Request;
	var $View;
	var $_mainLayout=""; 
	var $user = array();
	var $pic;
	var $user_realname;
	var $fb;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function setVar(){

		$fb = new FacebookHelper($req);
		$this->open(0);
		if($_SESSION['twit_id']!=null){
			$sql = "SELECT * FROM axis.axis_twit_pic WHERE author_id='{$_SESSION['twit_id']}'";
			$cache = $this->fetch($sql);
			if($cache['pic']!=""){
				$this->pic = $cache['pic'];
			}else{
				$rs = @json_decode(file_get_contents("https://api.twitter.com/1/users/show.json?screen_name={$_SESSION['twit_id']}"),true);
				$this->pic = $rs['profile_image_url_https'];
				$sql = "INSERT IGNORE INTO axis.axis_twit_pic(author_id,pic)
						VALUES('{$_SESSION['twit_id']}','{$rs['profile_image_url_https']}')";
				$this->query($sql);
			}
		}
		if($_SESSION['user_id']!=null){
			
			$sql = "SELECT nickname as name FROM axis.axis_member WHERE id={$_SESSION['user_id']} LIMIT 1";
			$rs = $this->fetch($sql);
			$this->user_realname = $rs['name'];
			
		}
		$this->close();
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
		global $APP_PATH;
		$_SESSION['inWeb'] = true;
		// $_SESSION['login'] = true;
		// print_r('<pre>');print_r($_SESSION);exit;
		
		//By Pass
		//$_SESSION['register'] = true;
		//$_SESSION['twit_id'] = 'dufronte';
		//$_SESSION['user_id'] = '2894';
		//$_SESSION['login'] = true;
		//$_SESSION['is_liked'] = true;
		//--> end of bypass
	
		if($_SESSION['login']==false){
			$token = unserialize(urldecode64($_REQUEST['t']));
			
			if($token['is_liked']!=true){
				sendRedirect($CONFIG['LANDING_BERKAH_DOMAIN']);
				die();
			}
			$_SESSION['login'] = true;

		}
		
		if($_SESSION['user_id']==null){
				sendRedirect($CONFIG['LANDING_BERKAH_DOMAIN']);
				die();
		}
		
		
		$this->View->assign("barokahUrl",$CONFIG['BAROKAH_SOURCES']);	
		$this->View->assign("berkahUrl",$CONFIG['BASE_DOMAIN_BAROKAH']);	
		
		$str = $this->run();
		
		//News Ticker
			
		include_once $APP_PATH . APPLICATION .'/widgets/newsTicker.php';
		
		$newsTicker = new newsTicker($this->Request);
		
		$this->assign('newsTicker', $newsTicker->newsTick());
			
		$this->assign('meta',$this->View->toString(BERKAHAPPLICATION . "/meta.html"));
		
		$this->View->assign("pic",$this->pic);
		$this->View->assign("user_realname",$this->user_realname);
		$this->assign('header',$this->View->toString(BERKAHAPPLICATION . "/header.html"));
		$this->assign('footer',$this->View->toString(BERKAHAPPLICATION . "/footer.html"));
		$this->assign('mainContent',$str);
		
        $page = $this->Request->getParam('page');
		if( $page == '' ){
			if($this->Request->getParam('reg') == 'success'){
				$_SESSION['register'] = false;
			}
			
			if(!$this->Request->getPost('nohp')&&$_SESSION['register']){
				
				$this->assign('twitID', $_SESSION['twit_id']);
				$this->mainLayout(BERKAHAPPLICATION . '/berkah_register_confirm.html');
			}else if($this->Request->getPost('nohp')&&$_SESSION['register']){
				//check nomor axis
				//validasi nomor
				$nomorHP = $this->Request->getPost('nohp');
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
				
				$noAxis = substr($nomorHP,0,5);
				
				if($noAxis == '62838' || $noAxis == '62831'){
					$nomorAXIS = 1;
				}else{
					$nomorAXIS = 0;
				}
				// var_dump($nomorAXIS);exit;
				$this->assign('axis', $nomorAXIS);
				$this->mainLayout(BERKAHAPPLICATION . '/berkah_non_axis.html');
			}else{
				$this->mainLayout(BERKAHAPPLICATION . '/master.html');
			}
		}else{
		
			$this->mainLayout(BERKAHAPPLICATION . '/master.html');
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
			if( !is_file( $APP_PATH . BERKAHAPPLICATION . '/modules/'. $page . '.php' ) ){
				
				if( is_file( '../templates/'. BERKAHAPPLICATION . '/'. $page . '.html' ) ){
					
					return $this->View->toString(BERKAHAPPLICATION.'/'.$page.'.html');
				
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
}
?>