<?php

	include "connessione.php";
	include "Session.php";
	
	$query2="SELECT * FROM utenti";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableGestioneUtenti'>";
			echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>Nome</th>";
				echo "<th>Cognome</th>";
				echo "<th>Username</th>";
				echo "<th class='colonnaPassword'>Password</th>";
				echo "<th>Amministrazione</th>";
				echo "<th>Produzione</th>";
				echo "<th>Cantiere</th>";
				echo "<th>Programmazione</th>";
				echo "<th></th>";
				echo "<th></th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr id='rigaUtente".$row2['id_utente']."'>";
				echo "<td>".$row2['id_utente']."</td>";
				echo '<td id="nomeUtente'.$row2["id_utente"].'" onkeyup="process(event, this)" contenteditable>'.$row2["nome"].'</td>';
				echo '<td id="cognomeUtente'.$row2["id_utente"].'" onkeyup="process(event, this)" contenteditable>'.$row2["cognome"].'</td>';
				echo '<td id="usernameUtente'.$row2["id_utente"].'" onkeyup="process(event, this)" contenteditable>'.$row2["username"].'</td>';
				echo '<td class="colonnaPassword">';
					echo '<input type="button" value="Cambia password" class="inputPasswordGestioneUtenti" id="btnCambiaPassword'.$row2["id_utente"].'" onclick="mostraInputPassword('.$row2["id_utente"].')"/>';
					echo '<input type="password" placeholder="Nuova password" id="passwordUtente'.$row2["id_utente"].'" class="inputPasswordConfermaGestioneUtenti"/>';
					echo '<input type="password" placeholder="Conferma password" id="confermaPasswordUtente'.$row2["id_utente"].'" class="inputPasswordConfermaGestioneUtenti"/>';
					echo '<button id="btnConfermaPassword'.$row2["id_utente"].'" class="btnConfermaPassword" onclick="confermaPassword('.$row2["id_utente"].')" style="display:none;float:left;margin-right:10px;" >Modifica<i style="margin-right:10px;float:right;margin-top:7px" class="fas fa-check"></i></button>';
				echo '</td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoPermessi($conn,$row2['id_utente'],"amministrazione");
					getListaPermessi($conn,$row2['id_utente'],"amministrazione");
				echo '</td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoPermessi($conn,$row2['id_utente'],"produzione");
					getListaPermessi($conn,$row2['id_utente'],"produzione");
				echo '</td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoPermessi($conn,$row2['id_utente'],"cantiere");
					getListaPermessi($conn,$row2['id_utente'],"cantiere");
				echo '</td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoPermessi($conn,$row2['id_utente'],"programmazione");
					getListaPermessi($conn,$row2['id_utente'],"programmazione");
				echo '</td>';
				echo '<td>';
					echo '<button class="btnModificaGestioneUtenti" onclick="modificaUtente('.$row2["id_utente"].')">Salva<i class="fas fa-save btnAzioneUtenti"></i></button>';
				echo '</td>';
				echo '<td id="risultato'.$row2["id_utente"].'" style="width:50px;text-align:right"></td>';
			echo "</tr>";
		}
			echo "<tr id='rigaNuovoUtente".$row2['id_utente']."'>";
				echo "<td></td>";
				echo '<td id="nomeNuovoUtente" onkeyup="process(event, this)" contenteditable></td>';
				echo '<td id="cognomeNuovoUtente" onkeyup="process(event, this)" contenteditable></td>';
				echo '<td id="usernameNuovoUtente" onkeyup="process(event, this)" contenteditable></td>';
				echo '<td title="Potrai cambiare la password in seguito oppure al primo accesso">password</td>';
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
				echo '<td>';
					echo '<button class="btnInserisciGestioneUtenti" onclick="inserisciUtente()">Inserisci<i class="fas fa-plus btnAzioneUtenti"></i></button>';
				echo '</td>';
				echo '<td id="risultatoNuovoUtente" style="width:50px;text-align:right"></td>';
			echo "</tr>";
		echo "</table>";
	}
	
	function getElencoPermessi($conn,$utente,$applicazione)
	{
		$queryOperatore="SELECT * FROM elenco_pagine WHERE applicazione='$applicazione'";
		$resultOperatore=sqlsrv_query($conn,$queryOperatore);
		if($resultOperatore==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryOperatore."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			echo "<select class='selectPermessi' onchange='aggiungiPermesso(".$utente.",this.value,".htmlspecialchars(json_encode($applicazione)).")' >";
				echo "<option value='' disabled selected>Pagine $applicazione</option>";
				echo "<option value='tutte' >Tutte le pagine</option>";
				while($rowOperatore=sqlsrv_fetch_array($resultOperatore))
				{
					echo "<option value='".$rowOperatore['id_pagina']."|".$rowOperatore['nomePagina']."'>".$rowOperatore['nomePagina']."</option>";
				}
			echo "</select>";
		}
	}
	function getListaPermessi($conn,$utente,$applicazione)
	{
		echo '<ul class="listaPermessi" id="listaPermessi'.$utente.$applicazione.'">';
		$queryColonne="SELECT * FROM permessi_pagine,elenco_pagine WHERE permessi_pagine.pagina=elenco_pagine.id_pagina AND applicazione='$applicazione' AND permessi_pagine.utente=".$utente;
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
					echo '<li class="liListaPermessi" id="liItemPermessi'.$rowColonne["id_permesso"].'"><span class="nomePaginaContainer">'.$rowColonne["nomePagina"].'</span><button class="btnEliminaPermesso" value="" onclick="eliminaPermesso('.$rowColonne["id_permesso"].','.$utente.')" title="Elimina permesso" ><i class="far fa-trash"></i></button></li>';
				}
			}
		echo '</ul>';
	}
?>