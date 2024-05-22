<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libralog";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn) {
    die("Connection Failed");
}
?>
