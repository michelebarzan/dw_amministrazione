<?php
session_start();
if(!isset($_SESSION['Username']))
{
	$server=  $_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT'];
	echo '<script>location.href="http://'.$server.'/dw_login/login.php";</script>';
}
?>