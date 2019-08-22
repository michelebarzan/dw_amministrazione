<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione general numbering";
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
		<script src="spinner.js"></script>
		<link rel="stylesheet" href="css/spinner.css" />
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<link rel="stylesheet" href="editableTable/editableTable.css" />
		<script src="editableTable/editableTable.js"></script>
		<script src="jquery.table2excel.js"></script>
		<script>
			function getTable(table,orderBy,orderType)
			{
				if(table=="general_numbering")
				{
					getEditableTable
					({
						table:'general_numbering',
						readOnlyColumns:['id_gn','commessa'],
						noInsertColumns:['id_gn'],
						container:'containerGestioneGeneralNumbering',
						foreignKeys:[['commessa','commesse','id_commessa','commessa']],
						orderBy:orderBy,
						orderType:orderType
					});
				}
				if(table=="tip_cab_6287")
				{
					getEditableTable
					({
						table:'tip_cab_6287',
						readOnlyColumns:['commessa'],
						primaryKey:'numero_cabina',
						container:'containerGestioneGeneralNumbering',
						foreignKeys:[['commessa','commesse','id_commessa','commessa']],
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
	<body onload="document.getElementById('absoluteActionBarButtongn').click();">
		<?php include('struttura.php'); ?>
		<div class="absoluteActionBar">
			<button class="absoluteActionBarButton" id="absoluteActionBarButtongn" onclick="document.getElementById('absoluteActionBarButtontc').style.color='';document.getElementById('absoluteActionBarButtongn').style.color='blue';getTable('general_numbering')">General Numbering</button>
			<button class="absoluteActionBarButton" id="absoluteActionBarButtontc" onclick="document.getElementById('absoluteActionBarButtongn').style.color='';document.getElementById('absoluteActionBarButtontc').style.color='blue';getTable('tip_cab_6287')">General Numbering 6287 (provvisorio)</button>
			<div class="absoluteActionBarElement">Righe: <span id="rowsNumEditableTable"></span></div>
			<button class="absoluteActionBarButton" onclick="excelExport('containerGestioneGeneralNumbering')">Esporta <i style="margin-left:5px;color:green" class="far fa-file-excel"></i></button>
			<button class="absoluteActionBarButton" onclick="resetFilters();getTable(selectetTable)">Ripristina <i style="margin-left:5px" class="fal fa-filter"></i></button>
		</div>
		<div id="containerGestioneGeneralNumbering"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>


