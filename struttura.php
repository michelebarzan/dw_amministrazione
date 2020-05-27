	<div id="header" class="header" >
		<input type="button" id="nascondi" value="" onclick="nascondi()" data-toggle='tooltip' title='Apri menu' />
		<div id="pageName" class="pageName"><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></div>
		<div id="user" class="user">
			<div id="username"><?php echo $_SESSION['Username']; ?></div>
			<input type="button" value="" id="btnUser">
			<input type="button" value="" onclick="apriNotifiche()" id="btnNotifica">
			<input type="button" id="btnNuovaNotifica" value="" >
			<div id="notifichePadding"></div>
			<div id="notifiche">
				<script>
					document.getElementById("user").addEventListener("mouseover", function()
					{
						if(document.getElementById('notifiche').style.display=="inline-block")
							apriNotifiche();	
						if(document.getElementById('notifichePadding').style.display=="inline-block")
							apriNotifiche();	
					});
				
					document.getElementById("notifiche").addEventListener("mouseover", function()
					{
						apriNotifiche();							
					});
					document.getElementById("notifiche").addEventListener("mouseout", function()
					{
						chiudiNotifiche();							
					});
					document.getElementById("notifichePadding").addEventListener("mouseover", function()
					{
						apriNotifiche();							
					});
				</script>
				<div id="userSettingsRow1">
					<div id="titoloUserSettings">Notifiche</div>
					<input type="button" value="" id="btnChiudiUserSettings" onclick="chiudiNotifiche()">
				</div>
				<div id="containerNotifiche">
					<div id="nessunaNotifica">
						Nessuna notifica
					</div>
				</div>
			</div>
			
			<input type="button" value="" onclick="apriUserSettings()" id="btnUserSettings">
			<div id="userSettingsPadding"></div>
			<div id="userSettings">
				<script>
					document.getElementById("user").addEventListener("mouseover", function() 
					{
						if(document.getElementById('userSettings').style.display=="inline-block")
							apriUserSettings();	
						if(document.getElementById('userSettingsPadding').style.display=="inline-block")
							apriUserSettings();	
					});
				
					document.getElementById("userSettings").addEventListener("mouseover", function()
					{
						apriUserSettings();							
					});
					document.getElementById("userSettings").addEventListener("mouseout", function()
					{
						chiudiUserSettings();							
					});
					document.getElementById("userSettingsPadding").addEventListener("mouseover", function()
					{
						apriUserSettings();							
					});
					setInterval(function()
					{
						if(document.getElementById('btnUserSettings').offsetWidth!="24")
						{
							chiudiUserSettings();
							chiudiNotifiche();	
						}
					}, 100);
				</script>
				<div id="userSettingsRow1">
					<div id="titoloUserSettings">Impostazioni</div>
					<input type="button" value="" id="btnChiudiUserSettings" onclick="chiudiUserSettings()">
				</div>
				<div id="userSettingsRow2">
					<?php getNomeCognome($conn,$_SESSION['Username']); ?>
				</div>
				<?php
				$server=$_SERVER['SERVER_NAME'];
				echo '<div id="userSettingsRow2">';
					echo '<a id="userSettingsCambiaPassword" href="http://'.$server.'/dw_login/cambiaPassword.php">Cambia password</a>';
				echo '</div>';
				?>
				<div id="permessiUserSettings">
					<div id="userSettingsRow3">
						Hai accesso alle pagine:
					</div>
					<?php getPermessi($conn,$_SESSION['Username'],$appName); ?>
				</div>
			</div>
			<input type="button" value="Logout" id="btnLogout" onclick="logoutB()">
		</div>
	</div>

	<div id="navBar">
		<input type="button" id="nascondi2" value="ME" onclick="nascondi()" data-toggle='tooltip' title='Chiudi menu' />
		<input type="button" id="nascondi3" value="NU" onclick="nascondi()" data-toggle='tooltip' title='Chiudi menu' />
		<input type="hidden" id="stato" value="Chiuso" />
		<input type="button" value="Homepage" data-toggle='tooltip' title='Homepage' class="btnGoToPath" onclick="goToPath('index.php')" />
		<input type="button" value="Gestione utenti" data-toggle='tooltip' title='Gestione utenti' class="btnGoToPath" onclick="goToPath('adminCenter.php')" />
		<input type="button" value="Gestione general numbering" data-toggle='tooltip' title='Gestione general numbering' class="btnGoToPath" onclick="goToPath('gestioneGeneralNumbering.php')" />
		<input type="button" value="Importazione general numbering" data-toggle='tooltip' title='Importazione general numbering' class="btnGoToPath" onclick="goToPath('importazioneGeneralNumbering.php')" />
		<input type="button" value="Importazione database txt" data-toggle='tooltip' title='Importazione database txt' class="btnGoToPath" onclick="goToPath('importazioneDatabaseTxt.php')" />
		<input type="button" value="Gestione commesse" data-toggle='tooltip' title='Gestione commesse' class="btnGoToPath" onclick="goToPath('gestioneCommesse.php')" />
	</div>
	
	<?php
		$id_utente=getIdUtente($conn,$_SESSION['Username']);
		if(!checkPermessi($conn,$id_utente,$pageName))
		{
			echo "<div style='width:100%;height:200px;line-height:200px;text-align:center;font-weight:bold;color:red;font-family:".htmlspecialchars(json_encode('Montserrat')).",sans-serif'>Accesso alla pagina non consentito</div>";
			echo '<div id="footer">';
				echo '<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201';
			echo '</div>';
			echo '</body>';
			echo '</html>';
			die();
		}
	
		function checkPermessi($conn,$id_utente,$pageName) 
		{
			$q="SELECT permesso FROM permessi_pagine,elenco_pagine WHERE permessi_pagine.pagina=elenco_pagine.id_pagina AND utente=$id_utente AND nomePagina='$pageName'";
			$r=sqlsrv_query($conn,$q);
			if($r==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				$rows = sqlsrv_has_rows( $r );
				if ($rows === true)
				{
					while($row=sqlsrv_fetch_array($r))
					{
						if($row['permesso']=='true')
							return true;
						else
							return false;
					}
				}
				else
					return false;
			}
		}
		function getIdUtente($conn,$username) 
		{
			$q="SELECT id_utente FROM utenti WHERE username='$username'";
			$r=sqlsrv_query($conn,$q);
			if($r==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($row=sqlsrv_fetch_array($r))
				{
					return $row['id_utente'];
				}
			}
		}
		function getNomeCognome($conn,$username) 
		{
			$q="SELECT nome,cognome FROM utenti WHERE username='$username'";
			$r=sqlsrv_query($conn,$q);
			if($r==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($row=sqlsrv_fetch_array($r))
				{
					echo $row['nome']." ".$row['cognome'];
				}
			}
		}
		
		function getPermessi($conn,$username,$appName) 
		{
			$q="SELECT nomePagina FROM permessi_pagine,elenco_pagine WHERE permessi_pagine.pagina=elenco_pagine.id_pagina AND applicazione='$appName' AND permesso='true' AND utente=".getIdUtente($conn,$username);
			$r=sqlsrv_query($conn,$q);
			if($r==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($row=sqlsrv_fetch_array($r))
				{
					echo $row['nomePagina']."<br>";
				}
			}
		}
	?>