<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Homepage";
	$appName="Amministrazione";
?>
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV13.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="struttura.js"></script>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<!--<div id="actionList">
					<div class="linkList" onclick="gotopath('adminCenter.php')" >Gestisci utenti, password e accesso alle pagine<input type="button" class="link" value=" " onclick="gotopath('adminCenter.php')"/></div>
				</div>-->
				<div class="homepageLinkContainer">
					<div class="homepageLink" title="Gestisci utenti, password e accesso alle pagine" onclick="gotopath('adminCenter.php')">
						<i class="fal fa-users-cog fa-2x"></i>
						<span>Gestione<br>utenti</span>
					</div>
					<div class="homepageLink" title="Gestisci la tabella general numbering" onclick="gotopath('gestioneGeneralNumbering.php')">
						<i class="fal fa-th fa-2x"></i>
						<span>Gestione general<br>numbering</span>
					</div>
					<div class="homepageLink" title="Importa un nuovo general numbering o modifica i dati esistenti" onclick="gotopath('importazioneGeneralNumbering.php')">
						<i class="fal fa-file-upload fa-2x"></i>
						<span>Importazione<br>general numbering</span>
					</div>
					<div class="homepageLink" title="Importa un nuovo general numbering o modifica i dati esistenti" onclick="gotopath('importazioneDatabaseTxt.php')">
						<i class="fal fa-file-import fa-2x"></i>
						<span>Importazione<br>database txt</span>
					</div>
					<div class="homepageLink" title="Gestisci le anagrafiche delle commesse" onclick="gotopath('gestioneCommesse.php')">
						<i class="fal fa-ship fa-2x"></i>
						<span>Gestione<br>commesse</span>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>

