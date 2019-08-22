<?php

	include "connessione.php";
	include "Session.php";
	
	$applicazione=$_REQUEST['applicazione'];	
	$id_utente=$_REQUEST['id_utente'];	
	
	$query1="DELETE permessi_pagine FROM permessi_pagine, elenco_pagine WHERE permessi_pagine.pagina=elenco_pagine.id_pagina AND applicazione='$applicazione' AND utente=$id_utente";	
	$result1=sqlsrv_query($conn,$query1);
	if($result1==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query1."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$query2="SELECT * FROM elenco_pagine WHERE applicazione='$applicazione'";	
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($row=sqlsrv_fetch_array($result2))
			{
				echo $row["id_pagina"].'|'.$row["nomePagina"].'#';
			}
			echo "endofresponse";
		}
	}
	
?>