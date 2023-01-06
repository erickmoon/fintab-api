<?php
function writeFile($name, $string) {
    $filename = $name.".txt"; 
    $fp = fopen($filename,"a+");  
    fputs($fp,$string); 
    fclose($fp);  
}

?>