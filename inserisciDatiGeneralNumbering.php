<?php

	include "connessione.php";
	include "Session.php";
	
	//$intestazioniDicolonna=$_REQUEST['intestazioniDicolonna'];
	$inserimentoRecord=$_REQUEST['inserimentoRecord'];
	$aggiornamentoRecord=$_REQUEST['aggiornamentoRecord'];
	$data=json_decode($_REQUEST['JSONdata']);
	$columns=json_decode($_REQUEST['JSONcolumns']);
	
	$columnsTable=[];
	$columnsRequired=[];
	$columnsMissing=[];
	$columnsError=[];
	
	set_time_limit(480);
	
	if(in_array("id_gn",$columns) && $inserimentoRecord=='true')
		die("idinserimento");
	
	$query1="SELECT *
			FROM dw_dati.INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = N'general_numbering'";	
	$result1=sqlsrv_query($conn,$query1);
	if($result1==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query1."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row1=sqlsrv_fetch_array($result1))
		{
			array_push($columnsTable,$row1["COLUMN_NAME"]);
		}
	}
	
	if(count($columns)>count($columnsTable))
		die("extracolumns");
	
	$query2="SELECT COLUMN_NAME, IS_NULLABLE
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE (TABLE_NAME = N'general_numbering') AND (IS_NULLABLE = 'NO')";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row2=sqlsrv_fetch_array($result2))
		{
			array_push($columnsRequired,$row2["COLUMN_NAME"]);
		}
	}
	
	$columnsMissing=array_diff($columnsTable,$columns);
	
	$i=0;
	foreach($columnsMissing as $column)
	{
		if(in_array($column,$columnsRequired))
			array_push($columnsError,$column);
		$i++;
	}
	
	if(count($columnsError)!=0)
	{
		if(count($columnsError)==1 && $columnsError[0]=='id_gn' && $inserimentoRecord=='true')
		{
			
		}
		else
		{
			echo "columns";
			echo "|";
			echo implode(",",$columnsError);
			die();
		}
	}
	
	$commesseArray=[];
	foreach ($data as $key => $value)
	{
		$row = json_decode(json_encode($value), True);
		array_push($commesseArray,$row['commessa']);
	}
	$commesseArray = array_unique($commesseArray);
	if(count($commesseArray)!=1)
		die("commesse");
	
	if(!commessaEsistente($conn,$commesseArray[0]))
		die("commessainesistente");
	
	//$commessaKey = array_search('commessa', $columns);
	$id_commessa=getIdCommessa($conn,$commesseArray[0]);
	$rows=[];
	foreach ($data as $key => $value)
	{
		$row = json_decode(json_encode($value), True);
		$row["commessa"]=$id_commessa;
		array_push($rows,$row);
	}
	if($inserimentoRecord=='true')
	{
		$query="INSERT INTO general_numbering ([".implode("],[",$columns)."])";
		foreach ($rows as $row)
		{
			$query.=" SELECT '".implode("','",$row)."' UNION ALL";
		}
		$query=substr($query, 0, -10);
		
		disableTrigger($conn);
		
		$result=sqlsrv_query($conn,$query);
		if($result==FALSE)
		{
			enableTrigger($conn);
			die ("error");
		}
		else
		{
			enableTrigger($conn);
			runFakeQuery($conn);
			echo "ok";
		}
	}
	if($aggiornamentoRecord=='true')
	{
		disableTrigger($conn);
		foreach ($rows as $row)
		{
			$query="UPDATE general_numbering SET ";
			foreach ($row as $key => $value)
			{
				if($key!="id_gn")
					$query.=$key."='".$value."',";
			}
			$query=substr($query, 0, -1);
			$query.=" WHERE id_gn=".$row["id_gn"];
			$result=sqlsrv_query($conn,$query);
			if($result==FALSE)
			{
				enableTrigger($conn);
				die("error");
			}
		}
		enableTrigger($conn);
		runFakeQuery($conn);
		echo "ok";
	}
	
	
	function runFakeQuery($conn)
	{
		$query2="update general_numbering set commessa=null where id_gn=null";	
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			die("error");
		}
	}
	function enableTrigger($conn)
	{
		/*$query2="ALTER TABLE general_numbering ENABLE TRIGGER aggiornaLotti";	
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			die("error");
		}
		$query3="ALTER TABLE general_numbering ENABLE TRIGGER aggiornaLotti_bf";	
		$result3=sqlsrv_query($conn,$query3);
		if($result3==FALSE)
		{
			die("error");
		}*/
		$query4="ALTER TABLE general_numbering ENABLE TRIGGER pulisciVuoti";	
		$result4=sqlsrv_query($conn,$query4);
		if($result4==FALSE)
		{
			die("error");
		}
	}
	function disableTrigger($conn)
	{
		/*$query2="ALTER TABLE general_numbering DISABLE TRIGGER aggiornaLotti";	
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			die("error");
		}
		$query3="ALTER TABLE general_numbering DISABLE TRIGGER aggiornaLotti_bf";	
		$result3=sqlsrv_query($conn,$query3);
		if($result3==FALSE)
		{
			die("error");
		}*/
		$query4="ALTER TABLE general_numbering DISABLE TRIGGER pulisciVuoti";	
		$result4=sqlsrv_query($conn,$query4);
		if($result4==FALSE)
		{
			die("error");
		}
	}
	function commessaEsistente($conn,$commessa)
	{
		$query2="SELECT * FROM commesse WHERE commessa='$commessa'";	
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			$rows = sqlsrv_has_rows( $result2 );
			return $rows;
		}
	}
	
	function getIdCommessa($conn,$commessa)
	{
		$query2="SELECT id_commessa FROM commesse WHERE commessa='$commessa'";	
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($row2=sqlsrv_fetch_array($result2))
			{
				return $row2["id_commessa"];
			}
		}
	}
	
?>