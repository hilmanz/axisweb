<?php
include_once "common.php";
print $_REQUEST['req'].":\n\n";
var_dump(unserialize(base64_decode($_REQUEST['req'])));
?>