<?php
date_default_timezone_set("Africa/Nairobi");
$db_hostname = "localhost";
$db_password = "TvHln5s}J!gb";
$db_database = "shukloco_fintab";
$db_username = "shukloco_fintab";
$conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
if ($conn->connect_error) {
    die("CONNECTION FAILED:" . $conn->connect_error);
}
?>
