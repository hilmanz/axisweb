<?php
global $APP_PATH;
include_once $APP_PATH.'axis/helper/dateHelper.php';
include_once $APP_PATH.BERKAHAPPLICATION."/helper/TwitterHelper.php";
class home extends App{
	var $Request;
	var $View;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->dateHelper = new dateHelper();
		$this->setVar();
	}
	
	function main(){
		$twitID = $_SESSION['twit_id'];
		$page=$this->Request->getParam('p');
		$post = $this->twitList($page);				
		// print_r('<pre>');
		// print_r($post);exit;
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
		
		$sql = "SELECT * 
						FROM tbl_admin_posting
						WHERE 1 ORDER BY posting_date DESC LIMIT 1
						";
		$this->open(0);
		$adminHAP = $this->fetch($sql); 
		$this->assign('admin',$adminHAP['posting']);
		$this->close();
		

		$this->View->assign('msg', "Wah.. Twitter Kamu sudah bersih dari kata-kata khilaf");
	
		
		$this->View->assign('paging', $paging);
		$this->View->assign('twitList', $post['posting']);
		$this->log('page','mobile_twitter');
		return $this->View->toString(BERKAHAPPLICATION.'/'.MOBILEAPPLICATION .'/home.html');
	}
	
	function twitList($page=0,$total=5){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		$twitID = $_SESSION['twit_id'];
		 // print_r($userID);exit;
		
		$page = $page*$total-$total;
		if($page<=1)$page = 0;
		//tweetlist
		$this->open(0);
			$sql = "SELECT a.*, c.name FROM tbl_twitter a
					LEFT JOIN tbl_user_twitter b
					ON b.twitter_id = a.twitter_id
					LEFT JOIN axis_member c
					ON c.id = b.user_id
					WHERE a.twitter_id = '{$twitID}'
					AND a.flag = 1 AND a.n_status = 0					
					ORDER BY created_at DESC";
			$sql2 = "SELECT a.*, c.name FROM tbl_twitter a
					LEFT JOIN tbl_user_twitter b
					ON b.twitter_id = a.twitter_id
					LEFT JOIN axis_member c
					ON c.id = b.user_id
					WHERE a.twitter_id = '{$twitID}'
					AND a.flag = 1 AND a.n_status = 0					
					ORDER BY created_at DESC LIMIT {$page},{$total}";
			$tweetList = $this->fetch($sql2, 1);
			$total = $this->fetch($sql, 1);
		$this->close();
		$size = sizeof($total);
		
		$data['posting'] = $tweetList;
		$data['total'] = ceil($size/5);
		
		return $data;	
	}
	
	function deleteTwitter(){
		global $CONFIG;
		if($_POST['twitID'] != NULL){
			$userID = $_SESSION['user_id'];
			$raw = $_POST['twitID'];
			foreach($raw as $n=>$v){
				$raw[$n] = "'".trim($v)."'";
			}
			$twitList = implode(",",$raw);
			
			//untuk memastikan bahwa feed_id2 yg akan dihapus adalah milik si user yg bersangkutan.
			$this->open(0);	
			if (strlen($_SESSION['twit_id']) > 0){
				$ck = "SELECT feed_id_str
						FROM tbl_twitter a
						WHERE  a.twitter_id='{$_SESSION['twit_id']}'
						AND a.feed_id_str IN (".$twitList.")";
				$feed = $this->fetch($ck,1);
				 // print_r($feed);exit;
			}
			
			//jika feed_idnya cocok
			if($feed){
				foreach($feed as $k=>$val){
					$feed[$k] = "'".trim($feed[$k]['feed_id_str'])."'";
				}
				$twitList2 = implode(",",$feed);
				
				$sql = "UPDATE tbl_twitter 
						SET n_status = 1
						WHERE feed_id_str IN (".$twitList2.")";
				$q = $this->query($sql);
				
				if ($q){
					if($q){
						//hapus twitter asli
						$tw = new TwitterHelper($this->Request);
						
						foreach($feed as $r){
							$tw->delete_tweets_from_twitter($_SESSION['user_id'],$_SESSION['twit_id'],str_replace("'","",$r));
						}
						//-->
						$this->log('hapus_twitter',$_SESSION['user_id']);
						$arr = array("status"=>1);
					}else{
						$arr = array("status"=>0);
					}
					
					sendRedirect($CONFIG['BERKAH_DOMAIN_MOBILE']);
					$this->View->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
					print $this->View->toString("axisBerkah/loading_message.html");
					die();
				}
			}
		}else{
			sendRedirect($CONFIG['BERKAH_DOMAIN_MOBILE']);
			$this->View->assign("baseUrl",$CONFIG['BASE_DOMAIN']);
			print $this->View->toString("axisBerkah/loading_message.html");
			die();
		}
	}
	
}
