<?php
/*
$servername = getenv('DB_HOST');
$username = getenv('DB_USER'); 
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');
*/
$servername = "127.0.0.1:3306";
$username = "root";
$password = "mariadb";
$dbname = "vehicle_renting";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
