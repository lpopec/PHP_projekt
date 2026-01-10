<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "php_projekt";

$dbc = mysqli_connect($servername, $username, $password, $dbname);

if (!$dbc) {
    die("Povezivanje nije uspjelo: " . mysqli_connect_error());
}

mysqli_set_charset($dbc, "utf8");
?>