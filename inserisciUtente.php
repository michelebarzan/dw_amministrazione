<?php

	include "connessione.php";
	include "Session.php";
	
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
	$password=sha1('password');
	
	
	$queryOperatore="SELECT * FROM utenti WHERE username='$username'";
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
			$query2="INSERT INTO utenti (nome,cognome,username,password) VALUES ('$nome','$cognome','$username','$password')";	
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