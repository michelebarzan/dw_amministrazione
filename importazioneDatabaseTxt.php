
<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Importazione database txt";
	$appName="Amministrazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV13.css" />
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="struttura.js"></script>
		<script src="js/importazioneDatabaseTxt.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
		<link rel="stylesheet" href="css/importazioneDatabaseTxt.css" />
		<script src="https://kit.fontawesome.com/10281adad2.js" crossorigin="anonymous"></script>
	</head>
	<body onload="getElencoLogImportazioni()" onresize="fixTable()">
		<?php include('struttura.php'); ?>
		<div class="top-action-bar" id="importaDatiActionBar">
			<!--<button class="action-bar-text-icon-button" id="bntImportaTutto" style="margin-right:5px" onclick="importaTutto(this)"><span>Importa tutti i database</span><i class="fad fa-upload"></i></button>-->
			<button class="action-bar-text-icon-button" id="bntCaricaFile" style="margin-right:5px" onclick="getPopupScegliTipoFile(this)"><span>Carica file txt</span><i class="fad fa-file-upload"></i></button>
			<input type="file" style="display:none" id="inputCaricaFile" accept=".txt" onchange="getFiles(this)" multiple>
			<button class="action-bar-text-icon-button" id="bntImportaSingoloDatabase" style="margin-right:0px" onclick="getPopupScegliDatabase(this)"><span>Importa database</span><i class="fad fa-upload"></i></button>
		</div>
		<div id="importaDatiContainer"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>


