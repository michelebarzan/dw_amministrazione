
<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione utenti";
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
		<script>
			function getTabellaUtenti()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('containerGestioneUtenti').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getTabellaUtenti.php?", true);
				xmlhttp.send();
			}
			function eliminaUtente(id)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
							getTabellaUtenti();
						else
							window.alert(this.responseText);
					}
				};
				xmlhttp.open("POST", "eliminaUtente.php?id="+id, true);
				xmlhttp.send();
			}
			function modificaUtente(id)
			{
				document.getElementById('risultato'+id).innerHTML='';
				var nome=document.getElementById('nomeUtente'+id).innerHTML;
				var cognome=document.getElementById('cognomeUtente'+id).innerHTML;
				var username=document.getElementById('usernameUtente'+id).innerHTML;
				if(nome=='' || cognome=='' || username=='')
					document.getElementById('risultato'+id).innerHTML='<i title="Tutti i campi sono obbligatori" class="fas fa-exclamation-triangle"></i>';
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								document.getElementById('risultato'+id).innerHTML='<i title="Utente modificato" class="fas fa-check"></i>';
								setTimeout(function(){ document.getElementById('risultato'+id).innerHTML='';}, 5000);
							}
							else
							{
								document.getElementById('risultato'+id).innerHTML='<i title="'+this.responseText+'" class="fas fa-exclamation-triangle"></i>';
								setTimeout(function(){ document.getElementById('risultato'+id).innerHTML='';}, 10000);
							}
						}
					};
					xmlhttp.open("POST", "modificaUtente.php?id="+id+"&nome="+nome+"&cognome="+cognome+"&username="+username, true);
					xmlhttp.send();
				}
			}
			function inserisciUtente()
			{
				document.getElementById('risultatoNuovoUtente').innerHTML='';
				var nome=document.getElementById('nomeNuovoUtente').innerHTML;
				var cognome=document.getElementById('cognomeNuovoUtente').innerHTML;
				var username=document.getElementById('usernameNuovoUtente').innerHTML;
				if(nome=='' || cognome=='' || username=='')
					document.getElementById('risultatoNuovoUtente').innerHTML='<i title="Tutti i campi sono obbligatori" class="fas fa-exclamation-triangle"></i>';
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								getTabellaUtenti();
							}
							else
							{
								document.getElementById('risultatoNuovoUtente').innerHTML='<i title="'+this.responseText+'" class="fas fa-exclamation-triangle"></i>';
							}
						}
					};
					xmlhttp.open("POST", "inserisciUtente.php?nome="+nome+"&cognome="+cognome+"&username="+username, true);
					xmlhttp.send();
				}
			}
			function mostraInputPassword(id)
			{
				/*var all = document.getElementsByClassName("colonnaPassword");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.width = '450px';
				}*/
				document.getElementById('btnCambiaPassword'+id).style.display="none";
				document.getElementById('passwordUtente'+id).style.display="inline-block";
				document.getElementById('confermaPasswordUtente'+id).style.display="inline-block";
				document.getElementById('btnConfermaPassword'+id).style.display="inline-block";
			}
			function ripristinaInputPassword(id)
			{
				document.getElementById('btnCambiaPassword'+id).style.display="inline-block";
				document.getElementById('passwordUtente'+id).style.display="none";
				document.getElementById('confermaPasswordUtente'+id).style.display="none";
				document.getElementById('passwordUtente'+id).value="";
				document.getElementById('confermaPasswordUtente'+id).value="";
				document.getElementById('btnConfermaPassword'+id).style.display="none";
				var all = document.getElementsByClassName("colonnaPassword");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.width = '';
				}
			}
			function confermaPassword(id)
			{
				var password=document.getElementById('passwordUtente'+id).value;
				var confermaPassword=document.getElementById('confermaPasswordUtente'+id).value;
				
				if(password=='' || confermaPassword=='' || password!=confermaPassword)
					document.getElementById('risultato'+id).innerHTML='<i title="Le password non corrispondono" class="fas fa-exclamation-triangle"></i>';
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								ripristinaInputPassword(id);
								document.getElementById('risultato'+id).innerHTML='<i title="Password modificata" class="fas fa-check"></i>';
								setTimeout(function(){ document.getElementById('risultato'+id).innerHTML='';}, 5000);
							}
							else
							{
								document.getElementById('risultato'+id).innerHTML='<i title="'+this.responseText+'" class="fas fa-exclamation-triangle"></i>';
							}
						}
					};
					xmlhttp.open("POST", "confermaPassword.php?password="+password+"&id="+id, true);
					xmlhttp.send();
				}
			}
			function process(e) 
			{
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) 
				{
					document.getElementById('containerGestioneUtenti').focus();
				}
				if (code == 27) 
				{
					//window.alert("esc");
				}
				if (code == 32) 
				{
					//window.alert("spazio");
				}
				if (code == 08) 
				{
					//window.alert("bksp");
				}
				if (code == 18) 
				{
					//window.alert("alt");
				}
				if (code == 17) 
				{
					//window.alert("ctrl");
				}
				if (code == 115) 
				{
					//window.alert("f4");
					//window.open('http://www.google.com');
				}
				if (code == 46) 
				{
					//window.alert("canc");
				}
			}
			function aggiungiPermesso(id_utente,pagina,applicazione)
			{
				if(pagina=="tutte")
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							{
								window.alert("Errore: "+this.responseText);
							}
							else
							{
								console.log(this.responseText);
								var res=this.responseText.split("#");
								//res.splice(-1,1)
								for (var i = 0; i < res.length; i++) 
								{
									if(res[i]=="endofresponse")
									{
										setTimeout(function(){ location.reload(); }, 500);
										//location.reload();
									}
									else
									{
										var res2=res[i].split("|");
										var id_pagina=res2[0];
										var nomePagina=res2[1];
										var xmlhttp = new XMLHttpRequest();
										xmlhttp.onreadystatechange = function() 
										{
											if (this.readyState == 4 && this.status == 200) 
											{
												if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
												{
													window.alert("Errore: "+this.responseText);
												}
												else
												{
													
												}
											}
										};
										xmlhttp.open("POST", "aggiungiPermesso.php?id_utente="+id_utente+"&id_pagina="+id_pagina+"&applicazione="+applicazione, true);
										xmlhttp.send();
									}
								}
							}
						}
					};
					xmlhttp.open("POST", "getTuttePagine.php?applicazione="+applicazione+"&id_utente="+id_utente, true);
					xmlhttp.send();
				}
				else
				{
					var res=pagina.split("|");
					var id_pagina=res[0];
					var nomePagina=res[1];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							{
								window.alert("Errore: "+this.responseText);
							}
							else
							{
								var ul = document.getElementById("listaPermessi"+id_utente+applicazione);
								var li = document.createElement("li");
								var btn=document.createElement("button");
								btn.setAttribute("class","btnEliminaPermesso");
								btn.setAttribute("title","Elimina permesso");
								btn.innerHTML='<i class="far fa-trash"></i>';
								btn.setAttribute("onclick","eliminaPermesso("+this.responseText+","+id_utente+")");
								li.setAttribute("class","liListaPermessi");
								li.setAttribute("id","liItemPermessi"+this.responseText);
								li.appendChild(document.createTextNode(nomePagina));
								li.appendChild(btn);
								ul.appendChild(li);
								document.getElementById('risultato'+id_utente).innerHTML='<i title="Permesso aggiunto" class="fas fa-check"></i>';
								setTimeout(function(){ document.getElementById('risultato'+id_utente).innerHTML='';}, 5000);
							}
						}
					};
					xmlhttp.open("POST", "aggiungiPermesso.php?id_utente="+id_utente+"&id_pagina="+id_pagina+"&applicazione="+applicazione, true);
					xmlhttp.send();
				}
			}
			function eliminaPermesso(id_permesso,id_utente)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
						{
							document.getElementById("liItemPermessi"+id_permesso).remove();
							document.getElementById('risultato'+id_utente).innerHTML='<i title="Permesso eliminato" class="fas fa-check"></i>';
							setTimeout(function(){ document.getElementById('risultato'+id_utente).innerHTML='';}, 5000);
						}
						else
							window.alert("Errore: "+this.responseText);
					}
				};
				xmlhttp.open("POST", "eliminaPermesso.php?id_permesso="+id_permesso, true);
				xmlhttp.send();
			}
		</script>
	</head>
	<body onload="getTabellaUtenti()">
		<?php include('struttura.php'); ?>
		<div style="position: absolute; left: 50%;top:90px;width:100%">
			<div id="containerGestioneUtenti"></div>
		</div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>


