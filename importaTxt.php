
<?php

    ini_set('memory_limit', '-1');
    set_time_limit(6000);

    include "connessione.php";

    $databases=json_decode($_REQUEST["JSONdatabases"]);

    $start = microtime(true);

    $missingTables=[];
    $skipTables=[];

    $errorMessages=[];

    $righeInserite=0;
    $righeNonInserite=0;
    foreach($databases as $database)
    {
        if($database=="Monofacciale")
        {
            $tipo="mf";
            $suffisso="";
            $tables=["cabine","cabpan","cesoiati","dibces","dibldr","dibpan","dibpas","dibrin","dibsvi","mater","pannelli","pannellil","sviluppi","tabrinf"];
        }
        if($database=="Bifacciale")
        {
            $tipo="bf";
            $suffisso="_bf2";
            $tables=["cabine","cabpan","cesoiati","dibces","dibldr","dibpan","dibpas","dibrin","dibsvi","mater","pannelli","pannellil","sviluppi","tabrinf","cesoiati_r","sviluppi_r","pannellil_r","dibces_r","dibpan_r","dibsvi_r","profili"];
        }
        if($database=="Monobifacciale")
        {
            $tipo="mb";
            $suffisso="_mb";
            $tables=["cabine","cabpan","cesoiati","dibces","dibldr","dibpan","dibpas","dibrin","dibsvi","mater","pannelli","pannellil","sviluppi","tabrinf","cesoiati_r","sviluppi_r","pannellil_r","dibces_r","dibpan_r","dibsvi_r","profili"];
        }

        foreach($tables as $table)
        {
            if (in_array($table, $skipTables))
            {
                //array_push($errorMessages,"TABELLA $table NON TROVATA<br>");
            }
            else
            {
                $columns=[];
                $data_types=[];
                $q7="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'$table".$suffisso."' AND COLUMN_NAME != 'UTENTE' AND COLUMN_NAME != 'tmp' AND COLUMN_NAME != 'id'";
                $r7=sqlsrv_query($conn,$q7);
                if($r7==FALSE)
                {
                    die("error1: ".$q7);
                }
                else
                {
                    while($row7=sqlsrv_fetch_array($r7))
                    {
                        array_push($columns,$row7["COLUMN_NAME"]);
                        array_push($data_types,$row7["DATA_TYPE"]);
                    }
                }
                $columns_string="[".implode("],[",$columns)."]";

                $fileName=$table.".txt";
        
                $rows=[];
        
                $rowN=0;               

                $codiciDaInserire=[];
                $queries=[];

                $columns0array=[];
                $columns0file=[];

                $column0=$columns[0];
                $q8="SELECT DISTINCT [$column0] FROM $table$suffisso";
                $r8=sqlsrv_query($conn,$q8);
                if($r8==FALSE)
                {
                    die("error1: ".$q8);
                }
                else
                {
                    while($row8=sqlsrv_fetch_array($r8))
                    {
						$queries[$row8[$columns[0]]]=[];
                        array_push($columns0array,$row8[$columns[0]]);
                    }
                }
                
                $file = fopen("files/txt/$tipo/regdef/$fileName", "r") or die("error");
                while(!feof($file))
                {
                    $rowString=fgets($file);
                    $rowString=substr($rowString,0,strlen($rowString)-2);
                    $rigaCheck=strtolower($rowString);
                    $colonnaCheck=strtolower($columns[0]);
					//controllo che la riga non sia l'intestazione
                    if (strpos($rigaCheck, $colonnaCheck) === false) 
                    {
                        $rowString=str_replace(chr(34),"'",$rowString);
                        $rowArray=explode(chr(9),$rowString);
						//controllo di non aver piu colonne nella tabella che nella rigab del txt
                        if(sizeof($columns)>sizeof($rowArray))
                        {
                            $columnsL=sizeof($columns);
                            $rowArrayL=sizeof($rowArray);

                            $diff=$columnsL-$rowArrayL;
                            for ($x=0; $x < $diff; $x++) 
                            { 
                                array_push($rowArray,"NULL");
                            }
                        }
						//controllo di non aver piu colonne nella riga del txt che nella tabella 
                        if(sizeof($rowArray)<sizeof($columns))
                        {
                            $columnsL=sizeof($columns);
                            $rowArrayL=sizeof($rowArray);

                            $diff=$rowArrayL-$columnsL;
                            array_splice($array, count($array) - $diff, $diff);
                        }
						//se il numero di colonnne e uguale
                        if(sizeof($rowArray)===sizeof($columns))
                        {        
							//controllo i valori dell' array, se sono nulli o altri caratteri li sistemo
                            for ($y=0; $y < sizeof($rowArray); $y++)
                            {
                                $item=$rowArray[$y];
                                if($item=="" || $item==" " || $item==null || strlen($item)==0 || ord($item)==13)
                                {
                                    if(($data_types[$y]=="decimal" || $data_types[$y]=="int" || $data_types[$y]=="real" || $data_types[$y]=="smallint"))
                                        $rowArray[$y]=0;
                                    else
                                        $rowArray[$y]="''";
                                }
                            }

                            $valoreColonna0=str_replace("'","",$rowArray[0]);
                            array_push($columns0file,$valoreColonna0);
							
							//$queries è un array di array contenenti query, è un array associativo con indice il valore della prima colonna
							if(!isset($queries[$valoreColonna0]) || $queries[$valoreColonna0]==null)
								$queries[$valoreColonna0]=[];
							array_push($queries[$valoreColonna0],"INSERT INTO [".$table.$suffisso."] ($columns_string) VALUES (".implode(',',$rowArray).")");
                        }
                    }
                    $rowN++;
                }
                fclose($file);

                $codiciDaInserire=array_diff($columns0file,$columns0array);
				$codiciDaInserire=array_unique($codiciDaInserire);
				
                foreach($codiciDaInserire as $codice)
                {
                    $queries_list=$queries[$codice];
					foreach($queries_list as $q2)
					{
						$r2=sqlsrv_query($conn,$q2);
						if($r2==FALSE)
						{
							array_push($errorMessages,"<b>Tabella: </b>$database.$table<br><b>Query: </b>".$q2."<br>");
							$righeNonInserite++;
						}
						else
						{
							$righeInserite++;
						}
					}
                }
            }
        }
    }
    $time_elapsed_secs = microtime(true) - $start;
    $time_elapsed_secs = number_format($time_elapsed_secs,1);

    $arrayResponse["righeInserite"]=$righeInserite;
    $arrayResponse["righeNonInserite"]=$righeNonInserite;
    $arrayResponse["errorMessages"]=$errorMessages;
    $arrayResponse["time_elapsed_secs"]=$time_elapsed_secs;
	
	//$arrayResponse["codiciDaInserire"]=$codiciDaInserire;
	$arrayResponse["queries"]=$queries;

    echo json_encode($arrayResponse);

?>