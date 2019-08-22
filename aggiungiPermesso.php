<?php

	include "connessione.php";
	include "Session.php";
	
	$id_utente=$_REQUEST['id_utente'];
	$id_pagina=$_REQUEST['id_pagina'];
	$applicazione=$_REQUEST['applicazione'];	
	
	$query2="INSERT INTO permessi_pagine (utente,pagina,permesso) VALUES ($id_utente,$id_pagina,'true')";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$queryColonne="SELECT MAX(id_permesso) AS id_permesso FROM permessi_pagine";
		$resultColonne=sqlsrv_query($conn,$queryColonne);
		if($resultColonne==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryColonne."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowColonne=sqlsrv_fetch_array($resultColonne))
			{
				echo $rowColonne["id_permesso"];
			}
		}
	}
	
?>