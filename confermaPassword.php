<?php

	include "connessione.php";
	include "Session.php";
	
	$id_utente=$_REQUEST['id'];
	$password=$_REQUEST['password'];
	$password=sha1($password);
	
	$query2="UPDATE utenti SET password='$password' WHERE id_utente=$id_utente";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "ok";
	}
?>