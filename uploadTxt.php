<?php
    
    $database=$_REQUEST["database"];        
    $file=$_FILES["file"];

    if($database=="Monofacciale")
        $db="mf";
    if($database=="Bifacciale")
        $db="bf";
    if($database=="Monobifacciale")
        $db="mb";
    
    define ('SITE_ROOT', realpath(dirname(__FILE__)));
    //define ('SITE_ROOT', 'C:\\xampp\\htdocs\\');

    $target_dir="files\\txt\\$db\\Regdef\\";
    $target_file = $target_dir.basename($file["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    if (!move_uploaded_file($file["tmp_name"], SITE_ROOT.'\\'.$target_file)) 
        die("error");

?>