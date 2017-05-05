<?php
//$data = array("name"=>"foo","designation"=>"bar");
//$rs = post_curl("http://localhost/axis/axis/trunk/web/tools/mcp_dummy_server.php",$data);

$xml = '<message type="dr">
<adn>123</adn>
<msisdn>9820202020</msisdn>
<tid>1234567890</tid>
<ccode>123SMSPULL2000</ccode>
<status>1</status>
<tdate>2012-02-21 12:31:20</tdate>
</message>';
//$rs = post_xml("http://103.3.221.154:10000/mt/mvas/send/",$xml);
$rs = post_xml("http://localhost/axis/trunk/web/tools/callback.php",$xml);
var_dump($rs);


function post_json($url,$data){
	$data_string = json_encode($data);
	$ch = curl_init($url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);                                                                                                                   
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return array("response"=>$result,"info"=>$info);
}
function post_xml($url,$xml_string){
	$ch = curl_init($url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/xml',                                                                                
	    'Content-Length: ' . strlen($xml_string))                                                                       
	);                                                                                                                   
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return array("response"=>$result,"info"=>$info);
}
?>