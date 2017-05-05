<?php
include_once "../engines/Gummy.php";
include_once "../engines/functions.php";
include_once '../com/Application.php';

class newsHelper extends Application{
	
	function sendGlobalMail($to,$from,$msg){
		
		// $to =  'bummi@kana.co.id';
		$from = 'noreply@tangkapberkahaxis.com';
		
		
		// $flag='<br />Kalau kamu ada kendala atau pertanyaan, kamu bisa email ke : hemat@axisworld.co.id';
	
		GLOBAL $ENGINE_PATH;
		require_once $ENGINE_PATH."Utility/Mailer.php";
		// if(file_exists($ENGINE_PATH."Utility/Mailer.php"))	print_r('ada');exit;		
		$mail = new Mailer();
		$mail->setRecipient($to);	
		$mail->setSubject('Notification AXIS');
		$mail->setMessage($msg);
		$result = $mail->send();
	
		if($result) return array('message'=>'success send mail');
		else return array('message'=>'error mail setting');
	}
	
	
	
}