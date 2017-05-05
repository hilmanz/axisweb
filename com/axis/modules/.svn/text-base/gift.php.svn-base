<?php

global $APP_PATH;
include_once $APP_PATH.'axis/helper/newsHelper.php';
class gift extends App{
	var $Request;
	var $View;
	var $newsHelper;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->newsHelper = new newsHelper();
		$this->setVar();
		$this->run();
	}
	
	function run(){
		$act = $this->Request->getParam('actAjax');
		if($act!=''){
			$this->$act();
		}else{
			$this->main();
		}
	}
		
	function getGiftData(){
					$last_id = intval($this->Request->getPost("last_gift_id"));
					
					$this->open(0);
					$getSchedule = " SELECT voucher_id,id  from axis.tbl_schedule_gift WHERE   id > {$last_id} AND start_date < NOW() AND  end_date > NOW() AND n_status=0 LIMIT 1";
					$rsSchedule = $this->fetch($getSchedule);
					// print_r($getSchedule);exit;
					if($rsSchedule){
						
						$getCheckGiftUsed = "
								 SELECT s_id,gift_id FROM axis.tbl_voucher_used  WHERE s_id = {$rsSchedule['id']} LIMIT 1;
						";
						
						$rsCheckGiftUsed=$this->fetch($getCheckGiftUsed);
						
						if($rsCheckGiftUsed){
							$qry = "
									 SELECT id,n_status FROM axis.tbl_gift gift WHERE n_status=0 AND voucher_id = {$rsSchedule['voucher_id']} AND id={$rsCheckGiftUsed['gift_id']} LIMIT 1;
							";
							
							$rs=$this->fetch($qry,1);
						}else{
						
							$qry = "
									 SELECT id,n_status FROM axis.tbl_gift gift WHERE n_status=0 AND voucher_id = {$rsSchedule['voucher_id']} AND NOT EXISTS (SELECT 1 FROM axis.tbl_voucher_used WHERE gift_id=gift.id  )  LIMIT 1;
							";
							
							$rs=$this->fetch($qry,1);
							// print_r($qry);exit;						
							$qUsedGift = "
									 INSERT IGNORE INTO axis.tbl_voucher_used(gift_id,s_id) VALUES ({$rs[0]['id']},{$rsSchedule['id']});
							";
							
							$insertDataGiftUsed=$this->query($qUsedGift);
							
							if(!$insertDataGiftUsed) $rs = null;
						}
						
					}
					
					$sql = "SELECT * FROM axis.axis_items WHERE item_type=2 AND home_status=0 ORDER BY RAND() LIMIT 1 ";
					$popupSell = $this->fetch($sql,1);
									
					$this->close();
					// if($popupSell){
						$data['response'] = 1;
						$data['rows'] = $rs;
						$data['last_id'] = $rsSchedule['id'];
						$data['freeContent'] = $popupSell;
					// }else $data['response'] = 0;
					header('Content-type: application/json');
					print_r(json_encode($data));exit;
	
	
	}
	
	function userGetGift(){
				global $LOCALE;
				
					// print_r();exit;
					$user_id = intval($_SESSION['user_id']);
					$gift_id = intval($this->Request->getPost('gift_id'));
					$s_id = intval($this->Request->getPost('s_id'));
					// $gift_id=826;
					
					if($user_id!=0 && $gift_id!=0){
						$hasGiftThisDay = $this->checkUserHasRedeemThisDay($user_id);
						
						$this->open(0);
							$qry = "
								SELECT * FROM axis.axis_member WHERE id={$user_id} AND n_status=1 LIMIT 1
							";
							
							$userAxis=$this->fetch($qry);
							$prefixUserNumber = preg_match("/838/i", substr($userAxis['no_hp'],0,5));
							$prefixUserNumber2 = preg_match("/831/i", substr($userAxis['no_hp'],0,5));
							
							if($prefixUserNumber||$prefixUserNumber2)$axis=true;
							else $axis=false;

							// print_r($prefixUserNumber);exit;
							if($axis){
								
								if($userAxis['verified']==1){								
												
										if(!$hasGiftThisDay){
											//get schedule n stat 0
											$getSchedule = " select voucher_id,id  from axis.tbl_schedule_gift WHERE  id={$s_id} AND start_date < NOW() AND  end_date > NOW() AND n_status=0 LIMIT 1";
											$rsSchedule = $this->fetch($getSchedule);
											//check ke table used voucher
											$getVoucherUsed = " select count(*) as total  from axis.tbl_voucher_used WHERE s_id={$s_id} AND gift_id={$gift_id} LIMIT 1";
											$rsVoucherUsed = $this->fetch($getVoucherUsed);
											if($rsVoucherUsed['total']>0)
											{
											if($rsSchedule){
												//change stat where id on schedule
												$removeSchedule = " UPDATE axis.tbl_schedule_gift set n_status = 1 WHERE id={$rsSchedule['id']} LIMIT 1";
												$rsRemoveSchedule = $this->query($removeSchedule);
													if($rsRemoveSchedule){	
															$qry = "
																SELECT * FROM axis.tbl_gift WHERE n_status = 0 AND id={$gift_id} AND voucher_id ={$rsSchedule['voucher_id']}  LIMIT 1
															";
															
															$gift=$this->fetch($qry);
																if($gift){
																	$qry = "
																		SELECT count(*) as total FROM axis.tbl_user_gift WHERE gift_id={$gift_id}
																		";
																			
																	$claim=$this->fetch($qry,1);
																	if($claim['total']>0) $claim=true;
																	else $claim =false;
																	
																	
																	if(!$claim){
																		$qry = "
																				INSERT INTO axis.tbl_user_gift (user_id,gift_id,date_time) VALUES ({$user_id},{$gift_id},NOW()) 
																		";
																		
																		$getGift=$this->query($qry);									
																			if($getGift){
																				$qry = "
																					UPDATE axis.tbl_gift set n_status=1 WHERE id = {$gift_id} LIMIT 1
																				";															
																				$updateGiftToUnused=$this->query($qry);
																				
																						$qry = "
																							select * from axis.tbl_type_voucher WHERE id={$gift['voucher_id']} ;
																						";

																						$popupMessage=$this->fetch($qry);
																				
																				$data['response'] = 1;
																				$code_voucher = $gift['code_voucher'];
																				$data['status'] = str_replace('code_voucher',$code_voucher,$popupMessage['copy_text']);
																				// blibli -> 6
																				// zelora ->  7 | Voucher Zalora 
																				// LivingSocial ->  8 | Voucher LivingSocial   
																				// Facebook ->  1 | Voucher Facebook  
																				// voucher ->  2 - 5 | Voucher Zalora 
																				//2 50, 3 20, 4 10, 5 5
																				
																				$kode = $gift['code_voucher']; 															
																				if($gift['voucher_id']==2) $rupiah =  'Rp . 50.000';
																				if($gift['voucher_id']==3) $rupiah =  'Rp . 20.000';
																				if($gift['voucher_id']==4) $rupiah =  'Rp . 10.000';
																				if($gift['voucher_id']==5) $rupiah =  'Rp . 5.000';
																				$serial_number = $gift['serial_number'];
																				
																				include_once "../config/email.php";
																				
																				$msg = $EMAIL['voucher'];
																				if($gift['voucher_id']==6) $msg =$EMAIL['blibli'];
																				if($gift['voucher_id']==7) $msg =$EMAIL['zelora'];
																				if($gift['voucher_id']==8) $msg =$EMAIL['LivingSocial'];
																				if($gift['voucher_id']==1) $msg =$EMAIL['facebook'];
																				
																				// $msg=$msg.'<br />'.$data['status'];
																				
																				$this->newsHelper->sendGlobalMail($userAxis['email'],$from,$msg);
																				
																				$this->log('gift', $userAxis['id'], true);
																				// print_r($status);exit;
																				//$data['data'] = $code_voucher;
																			}else{
																				$data['response'] =0;
																				$data['status'] = 'wah hadiah ini sedang tidak dapat di ambil , coba lagi ya';									
																			}
																	}else{
																			$data['response'] =0;
																			$data['status'] = 'hadiah ini sudah terambil oleh teman kamu';		
																	}							
																	
																}else {
																	$data['response'] = 0;
																	$data['status'] = 'hadiah ini sudah terambil oleh teman kamu ya..';
																}
														}else {
																$data['response'] = 0;
																$data['status'] = 'wah.. hadiah ini sudah terambil oleh teman kamu';
															}													
												}else{
															$data['response'] =0;
															$data['status'] = 'hadiah ini sudah terambil oleh teman kamu bep..';		
												}	
													
											}else{
															$data['response'] =0;
															$data['status'] = 'hadiah ini sudah terambil oleh teman kamu';		
												}			
													$this->close();
										}else{
											$data['response'] = 0;
											$data['status'] = 'Wah, usut punya usut...ternyata kamu sudah dapat Barokah ya barusan? Maaf ya, Beb, berhubung harus berbagi barokah dengan sesama, 1 orang hanya bisa dapat 1 Barokah ';										
										}
											
									
								}else{
									$data['response'] = 3;
									$data['status'] = 'Maaf Nomor kamu belum terverifikasi, silahkan verifikasi dulu ya';								
								}
							}else{
								$popUpMsg = round(rand(0,3));
								$data['response'] = 0;
								$data['status'] = $LOCALE['FAIL_GET_CONTENT'][$popUpMsg];
							}
					}else {
								$data['response'] = 0;
								$data['status'] = 'Maaf hadiah tidak ditemukan';
					}
					header('Content-type: application/json');
					print_r(json_encode($data));exit;
	}
	
	function checkUserHasRedeemThisDay($user_id){
	
			$qry = "
					SELECT count(*) as total FROM axis.tbl_user_gift WHERE user_id={$user_id} AND DATE(date_time) = DATE(NOW())
					";
			$this->open(0);					
			$hasGiftThisDay=$this->fetch($qry);
			
			$this->close();
			// print_r($hasGiftThisDay);exit;
			if($hasGiftThisDay['total']) return true;
			else return false;
	
	}


}
