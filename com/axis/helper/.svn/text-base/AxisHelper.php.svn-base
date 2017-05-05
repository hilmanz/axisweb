<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
class AxisHelper extends Application{
	var $user;
	/**
	 * initialized everything before doing operation using these helper.
	 * @param unknown_type $user_id
	 * @param unknown_type $reconnect
	 */
	function init($user_id,$reconnect=false){
		global $MCP;
		$this->force_connect($reconnect);
		$sql = "SELECT id,no_hp,verified FROM axis.axis_member WHERE id={$user_id} LIMIT 1";
		$this->user = $this->fetch($sql);
		
	}
	/**
	 * check apakah nomor hp user sudah verified apa belum
	 * @param $reconnect
	 */
	function isVerified(){
		if($this->user['verified']==1){
			return true;	
		}
	}
	/**
	 * validate the verification code. returns true if valid.
	 * @param string $code
	 * @return boolean  true if success
	 */
	function validateCode($code,$reconnect=false){
		if(strlen($code)>6){
			$code = cleanXSS($code);
			$code = mysql_escape_string($code);
			$user_id = $this->user['id'];
			$no_hp = $this->user['no_hp'];
			
			$sql = "SELECT user_id,no_hp,verify_code FROM axis.axis_verify WHERE
					user_id={$user_id} AND no_hp='{$no_hp}' LIMIT 1";
			
			$this->force_connect($reconnect);
			$rs = $this->fetch($sql);	
			if(strcmp($rs['verify_code'],$code)==0){
				$sql = "UPDATE axis.axis_member SET verified=1 WHERE id={$user_id} AND no_hp='{$no_hp}'";
				$q = $this->query($sql,$reconnect);
				if($q){
					return true;
				}
			}
		}
	}
	
	/**
	 * check if the item is available or not
	 * use these function for items which only can be redeemed / purchased once.
	 * @param $item_id
	 * @param $reconnect
	 * @return boolean
	 */
	function isItemAvailable($item_id,$reconnect=false){
		$sql = "SELECT a.id as item_id,b.transaction_id,b.n_status FROM axis.axis_items a
				INNER JOIN axis.mcp_request b
				ON a.id = b.item_id
				WHERE a.id={$item_id} LIMIT 1";
		$this->force_connect($reconnect);
		$rs = $this->fetch($sql);
		if($rs['n_status']!=1){
			return true;
		}
	}
	/**
	 * check if the item is available or not
	 * use these function for items which only can be redeemed / purchased once.
	 * @param $item_id
	 * @param $reconnect
	 * @return boolean
	 */
	function isItemRedeemedByUser($user_id,$item_id,$reconnect=false){
		$user_id = intval($user_id);
		$item_id = intval($item_id);
		$sql = "SELECT COUNT(a.id) as total 
				FROM axis.axis_items a
				INNER JOIN axis.mcp_request b
				ON a.id = b.item_id
				WHERE a.id={$item_id} AND b.user_id={$user_id}";
		
		$this->force_connect($reconnect);
		$rs = $this->fetch($sql);
		if($rs['total']>0){
			return true;
		}
	}
	function getItemDetail($item_id,$reconnect=false){
		$item_id = intval($item_id);
		$sql = "SELECT * FROM axis.axis_items WHERE id={$item_id} LIMIT 1";
		$this->force_connect($reconnect);
		return $this->fetch($sql);
	}
	/*
	 * method to purchase content
	 */
	function purchase_content($user_id,$item_id,$ccode,$scode,$reconnect=false){
		$user_id = intval($user_id);
		if($this->isVerified()){
			$no_hp = $this->user['no_hp'];
			return $this->mcp_content($user_id,$item_id,$no_hp,$ccode,$scode,$reconnect);
		}else{
			return false;
		}
	}
	/**
	 * MCP call for download content
	 */
	function mcp_content($user_id,$item_id,$no_hp,$ccode,$scode,$reconnect=false){
		global $MCP;
		
		$user_id = intval($user_id);
		$item_id = intval($item_id);
		$itemsDetail = $this->getItemDetail($item_id,true);
		$itemUrl = $itemsDetail['download_link'];
		$itemsDetail =false;
		$xml_str = "<message type=\"mtpush\">
					<sms><![CDATA[(SMS ini Rp.0) Untuk mendownload konten dari AXIS Barokah/Berkah silahkan klik link url berikut:{$itemUrl} ]]></sms>
					<msisdn>{$no_hp}</msisdn>
					<ccode>{$ccode}</ccode>
					<scode>{$scode}</scode>
					<cpid>{$MCP['cpid']}</cpid>
					<cppwd>{$MCP['cppwd']}</cppwd>
					</message>";
		
		$rs = post_xml($MCP['api_url'],$xml_str);
		$response = $rs['response'];
		
		$xml = simplexml_load_string($response);
		if($xml->status=="0"){
			$tid = $xml->tid;
			$tid = mysql_escape_string($tid);
			$sms = mysql_escape_string("(SMS ini Rp.0) Untuk mendownload konten dari AXIS Barokah/Berkah silahkan klik link url berikut:{$itemUrl}");
			$sql = "INSERT INTO axis.mcp_request 
					(user_id, item_id, transaction_id, msisdn, ccode, scode, sms, send_date, request_type, n_status)
					VALUES
					({$user_id}, {$item_id}, '{$tid}', '{$no_hp}', '{$ccode}', 
					'{$scode}', '{$sms}', NOW(), 2, 0)";
			
			$q = $this->query($sql,$reconnect);
			if($q){
				return true;
			}
		}
	}
	/**
	 * @return boolean true if success, false if failed
	 */
	function get_verification_code($user_id,$no_hp,$reconnect=false){
		$this->init($user_id,$reconnect);
		$a = array('A','B','C','D','E','F','G','H','I','J','K',
					'L','M','N','O','P','Q','R','S','T','U','V',
					'W','X','Y','Z','1','2','3','4','5','6','7',
					'8','9','0');
		$verification_code = $a[rand(0,sizeof($a)-1)].$a[rand(0,sizeof($a)-1)].$a[rand(0,sizeof($a)-1)].
							 $a[rand(0,sizeof($a)-1)].$a[rand(0,sizeof($a)-1)].$a[rand(0,sizeof($a)-1)].
							 $a[rand(0,sizeof($a)-1)].$a[rand(0,sizeof($a)-1)].$a[rand(0,sizeof($a)-1)];
		
		$sql = "INSERT INTO axis.axis_verify
				(user_id,no_hp,verify_code)
				VALUES
				({$user_id},
				'{$no_hp}',
				'{$verification_code}')
				ON DUPLICATE KEY
				UPDATE
				verify_code = VALUES(verify_code)";
				
		$q = $this->query($sql,$reconnect);
		if($q){
			$sql = "SELECT id,verify_code FROM axis.axis_verify WHERE user_id={$user_id} AND no_hp='{$no_hp}'";
		
			$this->force_connect($reconnect);
			$rs = $this->fetch($sql);
			if(strlen($rs['verify_code'])>0){
			
				//mcp call
				if($this->mcp_verify($rs['id'],$user_id,$no_hp,$rs['verify_code'],$reconnect)){
					print text('SMS_VERIFICATION_CODE',array('code'=>$rs['verify_code']));
					return true;	
				}
			}
		}
		
	}
	/**
	 * mcp api call for handphone number (msisdn) verification
	 * @param $user_id
	 * @param $no_hp
	 * @param $verify_code
	 * @param $reconnect
	 */
	function mcp_verify($verify_id,$user_id,$no_hp,$verify_code,$reconnect=false){
		global $MCP;
		
		$xml_str = "
					<message type=\"mtpush\">
					<msisdn>{$no_hp}</msisdn>
					<sms><![CDATA[(SMS ini Rp.0) Kode validasi : {$verify_code}, Ketik kode di atas dalam kolom di AXIS BAROKAH/BERKAH lalu klik confirm. ]]></sms>
					<ccode>{$MCP['verify_ccode']}</ccode>
					<scode>{$MCP['verify_scode']}</scode>
					<cpid>{$MCP['cpid']}</cpid>
					<cppwd>{$MCP['cppwd']}</cppwd>
					</message>";
		
	
		$rs = post_xml($MCP['api_url'],$xml_str);
		// var_dump($rs);
		$response = $rs['response'];
		
		$xml = simplexml_load_string($response);
		// print_r($xml);
		if($xml->status=="0"){
			$tid = $xml->tid;
			$tid = mysql_escape_string($tid);
			$sms = mysql_escape_string(text('SMS_VERIFICATION_CODE',array('code'=>$verify_code)));
			$sql = "INSERT INTO axis.mcp_request 
					(user_id, item_id, transaction_id, msisdn, ccode, scode, sms, send_date, request_type, n_status)
					VALUES
					({$user_id}, 0, '{$tid}', '{$no_hp}', '{$MCP['verify_ccode']}', 
					'{$MCP['verify_scode']}', '{$sms}', NOW(), 1, 0)";
			
			$q = $this->query($sql,$reconnect);
			$request_id = $this->lastInsertId;
			$this->query("UPDATE axis.axis_verify SET request_id = {$request_id} WHERE id = {$verify_id}",$reconnect);
			if($q){
				return true;
			}
		}
	}

}
?>
