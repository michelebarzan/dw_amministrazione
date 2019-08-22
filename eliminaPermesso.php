<?php

	include "connessione.php";
	include "Session.php";
	
	$id_permesso=$_REQUEST['id_permesso'];
	
	$query2="DELETE permessi_pagine FROM permessi_pagine WHERE id_permesso=$id_permesso";	
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