<?php
include_once "../config/config.inc.php";
include_once "../engines/functions.php";

session_start();
// if($_REQUEST['user_id']!=null){
	// $_SESSION['user_id'] = $_REQUEST['user_id'];
// }
// if($_SESSION['user_id']==null){
	// print "<a href='twitter.php?user_id='>twitter.php?user_id=[isi_user_id]</a>";
	// die();
// }

// $conn = mysql_connect("202.80.113.52","root","coppermine");
	// $sql = "INSERT IGNORE INTO axis.tbl_user_twitter(user_id)
			// VALUES({$_SESSION['user_id']})";
	// mysql_query($sql,$conn);
	// mysql_close($conn);
$service_url = $CONFIG['SERVICE_URL'];
print "user : ".$_SESSION['user_id'];
$access_token = get_access_token($_SESSION['user_id'],$CONFIG['SERVICE_KEY']);
var_dump($_SESSION['login']);
// var_dump($_REQUEST);
if($_REQUEST['oauth_verifier']!=null){
	$url = "{$service_url}?service=twitter&m=authorize&c={$_SESSION['c']}&oauth_token={$_REQUEST['oauth_token']}&oauth_verifier={$_REQUEST['oauth_verifier']}&access_token={$access_token}";
	$resp = file_get_contents($url);
}
if($_REQUEST['delete']==1){
	$url = "{$service_url}?service=twitter&m=remove_tweet&id={$_REQUEST['id']}&access_token={$access_token}";
	$resp = file_get_contents($url);
	print $resp."<br/>";
}
?>
<html>
<head>
	<title>Test Twitter</title>
</head>
<body>
<?php
if($_SESSION['login']){
	$call = $service_url."?service=twitter&m=login&access_token={$access_token}";
	$response = json_decode(file_get_contents($call));
	if($response->status==1){
		print "Hello {$response->data->user}<br/>";
		$url = $service_url."?service=twitter&m=get_tweets&page=1&access_token={$access_token}";
		$content = json_decode(file_get_contents($url));
		
		// print('<pre>');
		// print_r($content);exit;
		$data = $content->data;
		
?>
<hr size="1"/>
<?php foreach($data as $d):?>
<?=$d->created_at?> - <?=$d->txt?> [<a href='?delete=1&id=<?=$d->feed_id_str?>'>remove</a>]<br/>
<hr size="1"/>
<?php endforeach;?>

<?php	
	}else{
		$_SESSION['c'] = $response->data->c;
		print "<a href='{$response->data->link}'>Login with Twitter</a><br/>";
	}
} 
?>
</body>
</html>