<?php
	session_start();
	$_SESSION=array();
	session_destroy();
	$hour = time() + 3600 * 24 * 30;
	setcookie('username',"no", $hour);
	setcookie('password', "no", $hour);
	$server=  $_SERVER['SERVER_NAME'];
	echo '<script>location.href="http://'.$server.'/dw_login/login.php";</script>';
?>