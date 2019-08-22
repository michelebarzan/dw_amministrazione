
<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Importazione general numbering";
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
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="spinner.js"></script>
		<link rel="stylesheet" href="css/spinner.css" />
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/infragistics.core.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/infragistics.lob.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_core.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_collections.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_text.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_io.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_ui.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.documents.core_core.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_collectionsextended.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.excel_core.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_threading.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.ext_web.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.xml.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.documents.core_openxml.js"></script>
		<script src="http://cdn-na.infragistics.com/igniteui/latest/js/modules/infragistics.excel_serialization_openxml.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script>
		var data = [];
		var columns = [];
		var inserimentoRecord=true;
		var aggiornamentoRecord=false;
		
		$(function () {
            $("#inputScegliFileGeneralNumbering").on("change", function () {
				newCircleSpinner("Caricamento in corso...");
				document.getElementById("btnImportaGeneralNumbering").disabled = false;
                var excelFile,
                    fileReader = new FileReader();

                //$("#resultImportazionePannelloBifacciale").hide();

                fileReader.onload = function (e) {
                    var buffer = new Uint8Array(fileReader.result);

                    $.ig.excel.Workbook.load(buffer, function (workbook) {
                        var column, row, newRow, cellValue, columnIndex, i,
                            worksheet = workbook.worksheets(0),
                            columnsNumber = 0,
							gridColumns = [],
                            worksheetRowsCount;
						
						data = [];
						columns = [];

                        // Both the columns and rows in the worksheet are lazily created and because of this most of the time worksheet.columns().count() will return 0
                        // So to get the number of columns we read the values in the first row and count. When value is null we stop counting columns:
                        while (worksheet.rows(0).getCellValue(columnsNumber)) {
                            columnsNumber++;
                        }

                        // Iterating through cells in first row and use the cell text as key and header text for the grid columns
                        for (columnIndex = 0; columnIndex < columnsNumber; columnIndex++) {
                            column = worksheet.rows(0).getCellText(columnIndex);
                            gridColumns.push({ headerText: column, key: column });
                        }

                        // We start iterating from 1, because we already read the first row to build the gridColumns array above
                        // We use each cell value and add it to json array, which will be used as dataSource for the grid
                        for (i = 1, worksheetRowsCount = worksheet.rows().count() ; i < worksheetRowsCount; i++) {
                            newRow = {};
                            row = worksheet.rows(i);

                            for (columnIndex = 0; columnIndex < columnsNumber; columnIndex++) {
                                cellValue = row.getCellText(columnIndex);
                                newRow[gridColumns[columnIndex].key] = cellValue;
                            }

                            data.push(newRow);
                        }
						function arrayRemove(arr, value) 
						{
						   return arr.filter(function(ele)
						   {
							   return ele != value;
						   });
						}
						data.forEach(function(value) 
						{
							var commessa=value["commessa"];
							if(commessa=="" || commessa==null)
								data=arrayRemove(data, value);
						});

						gridColumns.forEach(function(gridColumn) 
						{
							columns.push(gridColumn["key"]);
						});
						//console.log(data);

                        // we can also skip passing the gridColumns use autoGenerateColumns = true, or modify the gridColumns array
                        createGrid(data, gridColumns);
                    }, function (error) {
						removeCircleSpinner();
						Swal.fire({
							  type: 'error',
							  title: 'Errore',
							  text: 'File illeggibile'
							})
						document.getElementById("btnImportaGeneralNumbering").disabled = true;
                    });
                }

                if (this.files.length > 0) {
                    excelFile = this.files[0];
					var nomeFile=excelFile["name"];
                    if (excelFile.type === "application/vnd.ms-excel" || excelFile.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || (excelFile.type === "" && (excelFile.name.endsWith("xls") || excelFile.name.endsWith("xlsx")))) {
                        fileReader.readAsArrayBuffer(excelFile);
                    } else {
						removeCircleSpinner();
						Swal.fire({
					  type: 'error',
					  title: 'Errore',
					  text: 'Formato non supportato'
					})
						document.getElementById("btnImportaGeneralNumbering").disabled = true;
                    }
                }
				
				if(nomeFile=="" || nomeFile==null)
				{
					removeCircleSpinner();
					Swal.fire({
					  type: 'error',
					  title: 'Errore',
					  text: 'Impossibile recuperare il nome del file'
					})
					document.getElementById("btnImportaGeneralNumbering").disabled = true;
				}
				else
				{
					document.getElementById("nomeFileLabelGeneralNumbering").innerHTML="Nome file:";
					document.getElementById("nomeFileGeneralNumbering").innerHTML=nomeFile;
				}
				
            })
        });

        function createGrid(data, gridColumns) {
            if ($("#myTableImportazioneGeneralNumbering").data("igGrid") !== undefined) {
                $("#myTableImportazioneGeneralNumbering").igGrid("destroy");
            }

            $("#myTableImportazioneGeneralNumbering").igGrid({
                columns: gridColumns,
                //autoGenerateColumns: true,
                dataSource: data//,
                //width: "100%"
            });
			removeCircleSpinner();
		}
		function openSettings()
		{
			var ul=document.createElement("ul");
			ul.setAttribute("class","settingsListImportazioneGeneralNumbering");
			
			/*var li=document.createElement("li");
			var label=document.createElement("label");
			label.setAttribute("class","settingsImportazoneGeneralNumberingLabel");
			label.innerHTML="Intestazioni di colonna";
			var checkbox=document.createElement("input");
			checkbox.setAttribute("type","checkbox");
			checkbox.setAttribute("checked","checked");
			checkbox.setAttribute("id","checboxIntestazioniDiColonna");
			var checkmark=document.createElement("span");
			checkmark.setAttribute("class","settingsImportazoneGeneralNumberingCheckmark");
			label.appendChild(checkbox);
			label.appendChild(checkmark);
			li.appendChild(label);
			ul.appendChild(li);*/
			
			var li=document.createElement("li");
			var label=document.createElement("label");
			label.setAttribute("class","settingsImportazoneGeneralNumberingLabel");
			label.innerHTML="Inserimento record";
			var checkbox=document.createElement("input");
			checkbox.setAttribute("type","checkbox");
			if(inserimentoRecord)
				checkbox.setAttribute("checked","checked");
			checkbox.setAttribute("id","checboxInserimentoRecord");
			var checkmark=document.createElement("span");
			checkmark.setAttribute("class","settingsImportazoneGeneralNumberingCheckmark");
			label.appendChild(checkbox);
			label.appendChild(checkmark);
			li.appendChild(label);
			ul.appendChild(li);
			
			var li=document.createElement("li");
			var label=document.createElement("label");
			label.setAttribute("class","settingsImportazoneGeneralNumberingLabel");
			label.innerHTML="Aggiornamento record";
			var checkbox=document.createElement("input");
			checkbox.setAttribute("type","checkbox");
			if(aggiornamentoRecord)
				checkbox.setAttribute("checked","checked");
			checkbox.setAttribute("id","checboxAggiornamentoRecord");
			var checkmark=document.createElement("span");
			checkmark.setAttribute("class","settingsImportazoneGeneralNumberingCheckmark");
			label.appendChild(checkbox);
			label.appendChild(checkmark);
			li.appendChild(label);
			ul.appendChild(li);
			
			Swal.fire
			({
				title: 'Impostazioni',
				html: ul.outerHTML
			}).then((result) => 
			{
				inserimentoRecord=document.getElementById("checboxInserimentoRecord").checked;
				aggiornamentoRecord=document.getElementById("checboxAggiornamentoRecord").checked;
				swal.close();
			});
		}
		function inserisciDatiGeneralNumbering()
		{
			if(inserimentoRecord)
				newCircleSpinner("Inserimento in corso...");
			else
				newCircleSpinner("Aggiornamento in corso...");
			var JSONdata=JSON.stringify(data);
			var JSONcolumns=JSON.stringify(columns);
			
			var errore=false;
			
			if(inserimentoRecord==true && aggiornamentoRecord==true)
			{
				removeCircleSpinner();
				errore=true;
				Swal.fire
				({
					type:"error",
					title: 'Errore',
					text: "Impostazioni errate"
				});
			}
			if(inserimentoRecord==false && aggiornamentoRecord==false)
			{
				removeCircleSpinner();
				errore=true;
				Swal.fire
				({
					type:"error",
					title: 'Errore',
					text: "Impostazioni errate"
				});
			}
			if(errore==false)
			{
				$.post("inserisciDatiGeneralNumbering.php",
				{
					//intestazioniDicolonna,
					inserimentoRecord,
					aggiornamentoRecord,
					JSONcolumns,
					JSONdata
				},
				function(response, status)
				{
					if(status=="success")
					{
						console.log(response);
						removeCircleSpinner();
						if(response.indexOf("ok")>-1)
						{
							document.getElementById("btnImportaGeneralNumbering").disabled = true;
							if(inserimentoRecord)
							{
								Swal.fire
								({
									type:"success",
									title: 'Righe inserite'
								});
							}
							else
							{
								Swal.fire
								({
									type:"success",
									title: 'Righe aggiornate'
								});

							}
						}
						if(response.indexOf("extracolumns")>-1)
						{
							Swal.fire
							({
								type:"error",
								title: 'Errore',
								text: "Alcune delle colonne non sono presenti nel General Numbering"
							});
						}
						if(response.indexOf("error")>-1)
						{
							Swal.fire
							({
								type:"error",
								title: 'Errore',
								text: "Se il problema persiste contattare l' amministratore"
							});
						}
						if(response.indexOf("idinserimento")>-1)
						{
							Swal.fire
							({
								type:"error",
								title: 'Errore',
								text: "Impossibile inserire dati nella colonna id_gn"
							});
						}
						if(response.indexOf("columns")>-1)
						{
							var columnsError = response.split("|")[1].split(",");
							Swal.fire
							({
								type:"error",
								title: 'Errore',
								text: "Colonne mancanti ("+columnsError.toString()+")"
							});
						}
						if(response.indexOf("commesse")>-1)
						{
							Swal.fire
							({
								type:"error",
								title: 'Errore',
								text: "E possibile inserire o aggiornare solo una commessa alla volta"
							});
						}
						if(response.indexOf("commessainesistente")>-1)
						{
							Swal.fire
							({
								type:"error",
								title: 'Errore',
								text: "Commessa inesistente. Compila l' anagrafica della commessa e riprova"
							});
						}
					}
					else
						console.log(status);
				});
			}
		}
		</script>
		<style>
			.swal2-title
			{
				font-family:'Montserrat',sans-serif;
				font-size:18px;
			}
			.swal2-content
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
			.swal2-confirm,.swal2-cancel
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
		</style>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="immagineLogo" class="immagineLogo" style="width:1300px;margin-top:70px;"></div>
		<div class="absoluteActionBar2">
			<button id="btnScegliFileGeneralNumbering" onclick="document.getElementById('inputScegliFileGeneralNumbering').click()">Scegli Excel<i class="fal fa-file-excel" style="margin-left:15px"></i></button>
			<button id="btnImportaGeneralNumbering" disabled="true" onclick="inserisciDatiGeneralNumbering()">Conferma caricamento<i class="fal fa-upload" style="margin-left:15px"></i></button>
			<input type="file"  id="inputScegliFileGeneralNumbering" style="display:none" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"></input>
			<button id="btnSettingsGeneralNumbering" onclick="openSettings()" title="Impostazioni"><i class="far fa-cog"></i></button>
			<div id="nomeFileLabelGeneralNumbering"></div>
			<div id="nomeFileGeneralNumbering"></div>
		</div>
		<div class="absoluteContainer2" style="margin-top:100px">
			<table id="myTableImportazioneGeneralNumbering"></table>
		</div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>


