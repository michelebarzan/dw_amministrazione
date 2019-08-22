<?php

	include "connessione.php";
	include "Session.php";
	
	$id_utente=$_REQUEST['id'];
	$nome=$_REQUEST['nome'];
	$nome= str_replace("<div>","",$nome);
	$nome= str_replace("</div>","",$nome);
	$nome= str_replace("<br>","",$nome);
	$cognome=$_REQUEST['cognome'];
	$cognome= str_replace("<div>","",$cognome);
	$cognome= str_replace("</div>","",$cognome);
	$cognome= str_replace("<br>","",$cognome);
	$username=$_REQUEST['username'];
	$username= str_replace("<div>","",$username);
	$username= str_replace("</div>","",$username);
	$username= str_replace("<br>","",$username);
	
	$queryOperatore="SELECT * FROM utenti WHERE username='$username' AND id_utente<>$id_utente";
	$resultOperatore=sqlsrv_query($conn,$queryOperatore);
	if($resultOperatore==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryOperatore."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$rows = sqlsrv_has_rows( $resultOperatore );  
		if ($rows === true)  
			echo "Username gia in uso";  
		else 
		{			
			$query2="UPDATE utenti SET nome='$nome', cognome='$cognome', username='$username' WHERE id_utente=$id_utente";	
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
		}
	}
?>