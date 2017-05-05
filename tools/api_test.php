<?php
include_once "../config/config.inc.php";
include_once "../engines/functions.php";

$access_token = get_access_token(1,$CONFIG['SERVICE_KEY']);
print $access_token;
?>