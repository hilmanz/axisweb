<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>AXIS BAROKAH - GSM YANG BAIK</title>
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="apple-touch-icon" href="img/favicon.png">
<link rel="stylesheet" type="text/css" href="css/axis.css" />
<link rel="stylesheet" type="text/css" href="js/jqueryui/css/smoothness/jquery-ui-1.7.2.custom.css">
<link rel="stylesheet" type="text/css" href="css/scrollbar.css">
<script type="text/javascript" src="js/jquery-1.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.js"></script>
<script type="text/javascript" src="js/jquery-custom-file-input.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="js/jScrollPane.js"></script>
<script type="text/javascript" src="js/jquery-custom-file-input.js"></script>
<script type="text/javascript" src="js/jquery-scroller-v1.min.js"></script>
<script type="text/javascript" src="js/scroll-startstop.events.jquery.js"></script>
<script type="text/javascript" src="js/axis.js"></script>
</head>

<body id="landingPage">
<div id="body" class="bgNew">
	<div id="top">
         <a style="margin:20px;" href="index.php" class="logoAxis">&nbsp;</a>
    </div><!-- end #top -->
    <div id="registerBox">
    	<form id="register_box" action="register.php" method="POST">
            <input type="text" class="inputNickname " name="inputNickname"/>
        	<input type="text" class="inputPhone" name="inputPhone" value="" maxlength="14"/>
			<input type="hidden" name="fb_id" value="{$fbid}"/>
			<input type="hidden" name="insert" value="1"/>
			<input type="submit" class="submitRegister" value="&nbsp;" />
            <span class="info">(Contoh: 083xxxxxxxxx)</span>
        </form>
        <p class="infoReg">Minta nomor HP kamu ya.. biarpun belum pakai nomor AXIS gapapa kok. Tenang aja, Data kamu aman sama AXIS</p>
    </div>
    <div id="footer">
    	<div class="footer">
        	<a class="facebook" href="#" target="_blank">&nbsp;</a>
            <a class="twitter" href="#" target="_blank">&nbsp;</a>
            <span class="copy">&copy; 2012 Axis. All rights reserved.</span>
        </div>
    </div><!-- end #footer -->
</div><!-- end #body -->
</body>
</html>