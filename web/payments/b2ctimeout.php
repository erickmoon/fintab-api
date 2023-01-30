<?php
$postData = file_get_contents('php://input');
$filePath = "output.txt";
$file = fopen($filePath,"a");
fwrite($file, $postData);