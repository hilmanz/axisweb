<?php
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
include_once $APP_PATH.BERKAHAPPLICATION."/helper/TwitterHelper.php";
class home extends App{
	var $Request;
	var $View;
	var $tw;

	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->tw = new TwitterHelper();
		$this->setVar();
		$this->run();
	
	}
	
	function run(){
		global $CONFIG;
		$userID = $_SESSION['user_id'];
		// if($this->Request->getParam('act')=='loadProfile'){
			// $this->loadProfile($userID);
		// }else if($this->Request->getParam('act')=='updateProfile'){
			// $this->updateProfile($userID);
		// }else if($this->Request->getParam('act')=='deleteTweet'){
			// $this->deleteTweet($userID);
		// }else if($this->Request->getParam('act')=='deleteMsgNotification'){
			// $this->tweetNotification();
		// }else{
			// var_dump($userID);exit;
			// $this->main($userID);
		// }
	}
	function main(){
		global $CONFIG;
		
		$userID = $_SESSION['user_id'];
		if($_SESSION['twit_id']==null){
		
			sendRedirect($CONFIG['REGISTRATION_DOMAIN_BAROKAH']."?fb_id={$_SESSION['user_login_id']}&t={$_REQUEST['t']}");
			die();
		}
		if($this->Request->getParam('reg') == 'success'){
			$_SESSION['register'] = false;
		}
		// var_dump($_SESSION['register']);exit;
		//$_SESSION['register'] = true;
		$this->open(0);
		$sql = "SELECT id,fb_id,no_hp,n_status FROM axis.axis_member WHERE id = {$_SESSION['user_id']} LIMIT 1";
		$check = $this->fetch($sql);
		
		
		if($check['fb_id']==$_SESSION['user_login_id']&&strlen($check['fb_id'])>0){
			
			if($check['n_status']==0&&strlen($check['no_hp'])==0){
				
				$_SESSION['register'] = true;
			}
		}
		$this->close();
		
		if($_SESSION['register']){
			//butuh registrasi nomor handphone dulu
			
			return $this->user_registration();
		}else{
			// var_dump($_SESSION['register']);exit;
			return $this->home();
		}
		
	}
	/**
	 * registrasi nomor HP
	 */
	function user_registration(){
		
		if($this->Request->getPost("nohp")){
			$this->registerNoHP($this->Request->getPost("nohp"));
		}else{
			
		}
		
		//return $this->View->toString(BERKAHAPPLICATION.'/berkah_register_confirm.html');
	}
	function registerNoHP($nomorHP){
		$fb = new FacebookHelper();
		
		$user_id = $_SESSION['user_id'];
		$twit_id = $_SESSION['twit_id'];
		
		//validasi nomor
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
		/*check Nomor Axis*/
		$noAxis = substr($nomorHP,0,5);
		if($noAxis == '62838' || $noAxis == '62831'){
			$nomorAXIS = true;
		}else{
			$nomorAXIS = false;
		}
		$data = array("user_id"=>$user_id,"nickname"=>$twit_id,"no_hp"=>$nomorHP);
		$fb->open(0);
		$registration = $fb->registrationUser($data);
		$fb->close();
		
		if($registration) {
			$msg ="berhasil registrasi, selamat bergabung";
			//$_SESSION['register'] = false;
			$_SESSION['login'] = true;
			$this->View->assign("ifAxis",$nomorAXIS);
			
			//$landing ="axis/register-messages.html";
		}else{
			$msg ="gagal registrasi, bisa di coba lagi nanti ya";
		}
		return $this->View->showMessage($msg);
	}
	/**
	 * home - daftar kata ada disini.
	 */
	function home(){
		global $CONFIG;
		$twitID = $_SESSION['twit_id'];
		// var_dump($twitID);exit;
		//tweetlist
		$this->open(0);
			//check apakah tweet2nya uda ditarik semua ?
			$sql = "SELECT twitter_id,n_status FROM axis.job_twitter WHERE twitter_id='{$twitID}' AND n_status=2";
			$c = $this->fetch($sql);
			//-->
			$uID = "SELECT a.name, b.twitter_id FROM axis_member a
					LEFT JOIN tbl_user_twitter b
					ON a.id = b.user_id
					WHERE b.twitter_id = '{$twitID}'
					LIMIT 1";
			$userID	= $this->fetch($uID,1);
					
			$sql = "SELECT a.*, c.name FROM tbl_twitter a
					LEFT JOIN tbl_user_twitter b
					ON b.twitter_id = a.twitter_id
					LEFT JOIN axis_member c
					ON c.id = b.user_id
					WHERE a.twitter_id = '{$twitID}'
					AND a.flag = 1 AND a.n_status = 0					
					ORDER BY created_at DESC LIMIT 0,20";
			$tweetList = $this->fetch($sql, 1);
			
			foreach($tweetList as $n=>$tweet){
				$tweetList[$n]['txt'] = $this->attachBadWords($tweet['txt']);
			}
		$this->close();
		
		//$rs = @json_decode(file_get_contents("https://api.twitter.com/1/users/show.json?screen_name={$twitID}"),true);
		//if(is_array($rs)){
			//$pic = $rs['profile_image_url'];
		$this->View->assign("pic",$this->pic);
			
		//}
		
		//Sisa Kata Kotor
		$sisaKotor = $this->tw->count_bad_tweets($_SESSION['twit_id']) - $this->tw->count_flagged_tweets($_SESSION['twit_id']);
		
		
		$this->View->assign('sisaKotor', $sisaKotor);
		$this->View->assign('uID', $userID);
		$this->View->assign('checkTweet', $c['n_status']);
		$this->View->assign('twitList', $tweetList);
		$this->View->assign("landing_barokah",$CONFIG['LANDING_BASE_DOMAIN']);
		$this->View->assign('getUserData',$this->Request->encrypt_params(array("page"=>"contentDownload","actAjax"=>"getUserData")));
		$this->View->assign('purchaseContent',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"purchaseContent")));
		$this->View->assign('getVerificationCode',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"getVerificationCode")));
		$this->View->assign('validationCode',$this->Request->encrypt_params(array("page"=>"mcp","actAjax"=>"validationCode")));
		return $this->View->toString(BERKAHAPPLICATION.'/home.html');
	}
	function attachBadWords($txt){
		$wordlist = extract_words($txt);
		$str= "";
		$this->open(0);
		foreach($wordlist as $n=>$w){
			if($n>0){
				$str.=",";
			}
			$w = mysql_escape_string(trim($w));
			$str.="'{$w}'";
		}
		$sql = "SELECT keyword FROM axis.tbl_bad_words WHERE keyword IN ({$str})";
		$rs = $this->fetch($sql,1);
		foreach($rs as $k){
			$txt = str_replace($k['keyword'],"<strong>{$k['keyword']}</strong>",$txt);
		}
		unset($rs);
		return $txt;
	}
	function deleteTweet(){
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
			 // print_r($feed);die();
		}
		
		//$_SESSION['twityangdihapus'] = sizeof($feed);
		
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
		}
		
		$this->close();
		if($q){
			//hapus twitter asli
			$tw = new TwitterHelper($this->Request);
			
			foreach($feed as $r){
				$tw->delete_tweets_from_twitter($_SESSION['user_id'],$_SESSION['twit_id'],str_replace("'","",$r));
			}
			//-->
			$this->log('hapus_twitter',$_SESSION['user_id']);
			$rs = urlencode64(serialize(array("twityangdihapus"=>sizeof($feed))));
			$arr = array("status"=>1,"rs"=>$rs);
		}else{
			$arr = array("status"=>0);
		}
		print json_encode($arr);
		die();
	}
	
	function loadProfile(){
	$userID = $_SESSION['user_id'];
		$this->open(0);
		$sql = "SELECT a.no_hp, b.twitter_id
				FROM axis_member a
				LEFT JOIN tbl_user_twitter b
				ON b.user_id = a.id
				WHERE a.id=".$userID."";
		$q = $this->fetch($sql);
		$this->close();
		
		echo json_encode($q);exit;
	}
	
	function updateProfile(){
		$userID = $_SESSION['user_id'];
		$nomorHP = $this->Request->getPost('hp');
		
		
		$this->open(0);
		$qr = "SELECT no_hp 
				FROM axis_member
				WHERE id=".$userID."";
		$checkNumber = $this->fetch($qr);
		$this->close();
		
		if ($nomorHP != $checkNumber['no_hp']){
			/*helper=> Check nomor HP*/
			$zero = substr($nomorHP,0,1);
			$null = $nomorHP;
			$enamDuaNol = substr($nomorHP,0,3);
			$enamDuaPlus = substr($nomorHP,0,3);
			if($zero == '0'){
				$nomorHP = '62'.substr($nomorHP,1);
			}else if($enamDuaPlus == '+62'){
				$nomorHP = '62'.substr($nomorHP,3);
			}else if($enamDuaNol == '620'){
				$nomorHP = '62'.substr($nomorHP,3);
			}
			
			if($nomorHP == '62'){
				$nomorHP = '';
			}
			
			/*check Nomor Axis*/
			if ($nomorHP != null || $nomorHP != ""){
				$noAxis = substr($nomorHP,0,5);
			}
			
			if($noAxis == '62838' || $noAxis == '62831'){
				$nomorAXIS = true;
			}else{
				$nomorAXIS = false;
			}
			
			$this->open(0);
			
				$sql = "UPDATE axis_member 
					SET no_hp = '".$nomorHP."',verified=0
					WHERE id=".$userID."";
			
			
			$q = $this->query($sql);
			$this->close();
			if ($q){
				if($nomorAXIS){
					$msg = "Kamu telah sukses update data.<br>Oh ya pastiin nomor AXIS kamu aktif dan valid ya karena AXIS bakal bagi-bagi Barokah.";
				}else{
					$msg = "Kamu telah sukses update data.<br>Kalau bisa nanti Bebeb edit pakai nomor AXIS ya biar gampang nyamber hadiah Barokah dari AXIS.";
				}
				
			}else{
				$msg = "Update data gagal, silahkan coba lagi Beb.";
			}
		}else if ($nomorHP == $checkNumber['no_hp']){
			$msg = "Data kamu sudah tersimpan Beb";
		}
		print_r($msg);exit;
	}
	
	function tweetNotification(){
		if($this->tw->count_flagged_tweets($_SESSION['twit_id']) < $this->tw->count_bad_tweets($_SESSION['twit_id'])){
			//extract the secret parameter
			$r = unserialize(urldecode64($this->Request->getParam('r')));
			$jmlYgDihapus =$r['twityangdihapus'];
			$msg[0] = '<h1>Mantap, Beb!</h1><h2>Kamu sudah menghapus <span class="orange">'.$jmlYgDihapus.' tweet</span> berisi kata-kata khilaf. Dikit lagi twitter kamu kembali fitri nih!  Balik lagi untuk bersihin sisanya ya.</h2>';
			$msg[1] = '<h1>Gak nyangka deh,</h1><h2>Bebeb tulus bersihin <span class="orange">'.$jmlYgDihapus.' tweet</span> yang berisi kata-kata khilaf. Balik lagi yuk untuk berisihin sisanya supaya twitter Bebeb beneran fitri</h2>';
			$msg[2] = '<h1>OK banget, Beb!</h1><h2>Sebanyak <span class="orange">'.$jmlYgDihapus.' tweet</span>  kamu berisi kata-kata khilaf udah kehapus. Jangan lupa balik lagi bersihin sisanya ya..  Biar twitter kamu kembali fitri total!</h2>';
		}else if($this->tw->count_bad_tweets($_SESSION['twit_id']) == $this->tw->count_flagged_tweets($_SESSION['twit_id'])){
			$jmlYgDihapus = $this->tw->count_flagged_tweets($_SESSION['twit_id']);
			$msg[0] = '<h1> Akhirnyaaa si Bebeb insyaf juga!</h1><h2>Sebanyak <span class="orange">'.$jmlYgDihapus.' tweet</span> berisi kata-kata khilaf udah terhapus. Sekarang twitter kamu kembali fitri. Selamaaatt!!</h2>';
			$msg[1] = '<h1>Bebeb hebaaaat!</h1><h2>Berhasil menghapus <span class="orange">'.$jmlYgDihapus.' tweet</span> yang isinya kata-kata khilaf. Indah kan Beb kalo twitter nya beneran fitri.</h2>';
			$msg[2] = '<h1>Senangnyaaa...</h1><h2>twitter Bebeb bersih dari <span class="orange">'.$jmlYgDihapus.' tweet</span> yang berisi kata-kata khilaf. Bener-bener fitri deh twitter nya. <br />Selamat ya!</h2>';
		}
		$rand = rand(0,2);
		
		$this->View->assign('msg', $msg[$rand]);
		return $this->View->toString(BERKAHAPPLICATION.'/berkah_notification.html');
	}
	
	function moreConversation(){
		global $CONFIG;
		$start = intval($this->Request->getPost('startID'));
		$twitID = $_SESSION['twit_id'];
		$this->open(0);
			$sql = "SELECT twitter_id,n_status FROM axis.job_twitter 
					WHERE twitter_id='{$twitID}' AND n_status=2";
			$c = $this->fetch($sql);
			$sql = "SELECT a.*, c.name FROM tbl_twitter a
					LEFT JOIN tbl_user_twitter b
					ON b.twitter_id = a.twitter_id
					LEFT JOIN axis_member c
					ON c.id = b.user_id
					WHERE a.twitter_id = '{$twitID}'
					AND a.flag = 1 AND a.n_status = 0					
					ORDER BY created_at DESC LIMIT ".$start.",20";
			$tweetList = $this->fetch($sql, 1);
			foreach($tweetList as $n=>$t){
				$tweetList[$n]['pic'] = $this->pic;
				$tweetList[$n]['txt'] = $this->attachBadWords($t['txt']);
			}		
		$this->close();
		
		//Sisa Kata Kotor
		$sisaKotor = $this->tw->count_bad_tweets($_SESSION['twit_id']) - $this->tw->count_flagged_tweets($_SESSION['twit_id']);
		
		print json_encode(array("status"=>intval($c['n_status']),"tweet"=>$tweetList,"bad_words"=>$sisaKotor)); die();
	}
}
