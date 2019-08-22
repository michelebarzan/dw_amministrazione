
<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione commesse";
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
		<script src="editableTable/editableTable.js"></script>
		<link rel="stylesheet" href="editableTable/editableTable.css" />
		<script src="jquery.table2excel.js"></script>
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="spinner.js"></script>
		<link rel="stylesheet" href="css/spinner.css" />
		<script>
			function getTable(table,orderBy,orderType)
			{
				if(table=="commesse")
				{
					getEditableTable
					({
						table:'commesse',
						readOnlyColumns:['id_commessa'],
						noInsertColumns:['id_commessa'],
						container:'containerGestioneCommesse',
						orderBy:orderBy,
						orderType:orderType
					});
				}
			}
			function editableTableLoad()
			{
			
			}
		</script>
	</head>
	<body onload="getTable('commesse')">
		<?php include('struttura.php'); ?>
		<div class="absoluteActionBar">
			<div class="absoluteActionBarElement">Righe: <span id="rowsNumEditableTable"></span></div>
			<button class="absoluteActionBarButton" onclick="excelExport('containerGestioneCommesse')">Esporta <i style="margin-left:5px;color:green" class="far fa-file-excel"></i></button>
			<button class="absoluteActionBarButton" onclick="resetFilters();getTable(selectetTable)">Ripristina <i style="margin-left:5px" class="fal fa-filter"></i></button>
		</div>
		<div id="containerGestioneCommesse"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>


