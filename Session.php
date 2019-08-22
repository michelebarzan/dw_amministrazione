<?php
session_start();
if(!isset($_SESSION['Username']))
{
	$server=  $_SERVER['SERVER_NAME'];
	echo '<script>location.href="http://'.$server.'/dw_login/login.php";</script>';
}
?>