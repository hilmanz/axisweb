<?php
/**
 * script for mcp call back (to accept DR parameters from mcp)
 */
/*
 * database configuration
 */
$host = "202.80.113.52";
$user = "root";
$pass = "coppermine";
$db = "axis";

//All parameters are mandatory, any invalid parameter will cause NOK reponse.
//Sample XML
//<message type="dr">
//<adn>123</adn>
//<msisdn>9820202020</msisdn>
//<tid>1234567890</tid>
//<ccode>123SMSPULL2000</ccode>
//<status>1</status>
//<tdate>2012-02-21 12:31:20</tdate>
//</message>

$body = file_get_contents('php://input');
$xml = simplexml_load_string($body);
//CP shortcode
$adn = $xml->adn;
//Handset number
$msisdn = $xml->msisdn;
//Transaction Id
$tid = $xml->tid;
//Charging code
$ccode = $xml->ccode;
//DR status
$status = $xml->status;
//Transaction date
$tdate = $xml->tdate;

function update_status($tid){
	global $host,$user,$pass;
	$conn = mysql_connect($host,$user,$pass);
	$tid = mysql_escape_string($tid);
	$sql = "UPDATE axis.mcp_request SET n_status=1 WHERE transaction_id='{$tid}'";
	$q = mysql_query($sql);
	/*
	$sql = "SELECT * FROM axis.mcp_request WHERE transaction_id = '{$tid}' LIMIT 1";
	$q = mysql_query($sql,$conn);
	$rs = mysql_fetch_assoc($q);
	mysql_free_result($q);
	
	//check transaction
	switch($rs['request_type']){
		case 1:
			//verification code request
			
		break;
		default:
			//download content request
		break;
	}
	*/
	
	mysql_close($conn);
}
function process_dr($adn,$msisdn,$tid,$ccode,$status,$tdate){
    $final_status = "";
    if(empty($adn)){
        log_it("Invalid adn: {$tid} {$msisdn} {$tdate}");
        return "NOK";
    }
    if(empty($msisdn)){
        log_it("Invalid msisdn: {$tid} {$msisdn} {$tdate}");
        return "NOK";
    }
    if(empty($tid)){
        log_it("Invalid tid: {$tid} {$msisdn} {$tdate}");
        return "NOK";
    }
    if(empty($ccode)){
        log_it("Invalid ccode: {$tid} {$msisdn} {$tdate}");
        return "NOK";
    }
    if(empty($status)){
        log_it("Invalid status: {$tid} {$msisdn} {$tdate}");
        return "NOK";
    }
    if(empty($tdate)){
        log_it("Invalid tdate: {$tid} {$msisdn} {$tdate}");
        return "NOK";
    }
    
    switch($status){
        case 1  : //MT Status Delivery Succes
            $final_status = "OK";
            $info = "MT Status Delivery Success";
            update_status($tid);
        break;
        case -1 : // MT Status Failed to Charge
            $final_status = "NOK";
            $info = "MT Status Failed to Charge";
        break;
        case -2 : // MT Status SMSC Failed
            $final_status = "NOK";
            $info = "MT Status SMSC Failed";
        break;
        case -3 : // MT Status Subscriber Not Found
            $final_status = "NOK";
            $info = "MT Status Subscriber Not Found";
        break;
        case -4 : // MT Status Invalid Transaction ID
            $final_status = "NOK";
            $info = "MT Status Invalid Transaction ID";
        break;
        case -5 : // MT Status Invalid Service ID
            $final_status = "NOK";
            $info = "MT Status Invalid Service ID";
        break;
        case -6 : // MT Status Invalid Charging ID
            $final_status = "NOK";
            $info = "MT Status Invalid Charging ID";
        break;
        case -7 : // MT Status ADN Push limit Exceeded
            $final_status = "NOK";
            $info = "MT Status ADN Push limit Exceeded";
        break;
        case -8 : // MT Status User Push limit Exceeded
            $final_status = "NOK";
            $info = "MT Status User Push limit Exceeded";
        break;
        case -9 : // MT Status MSISDN doesn't have enough balance
            $final_status = "NOK";
            $info = "MT Status MSISDN doesn't have enough balance";
        break;
        case -10:  // MT Status Internal Error
            $final_status = "NOK";
            $info = "MT Status Internal Error";
        break;
        default:
            $info = "Unknown status: {$status}";
        
    }
    
   log_it("{$info}: {$tid} {$msisdn} {$tdate}");
//   echo "{$info}: {$tid} {$msisdn} {$tdate} ";
   return $final_status;
}

//Log file will created daily
function log_it($content){
    $log_file = "../logs/dr_client.log";
    $fh = fopen($log_file, 'a+') or die("can't open file");
    $stringData = "{$content}".PHP_EOL;
    fwrite($fh, $stringData);
    fclose($fh);
}


$final_status = process_dr($adn, $msisdn, $tid, $ccode, $status, $tdate);

echo $final_status;
?>