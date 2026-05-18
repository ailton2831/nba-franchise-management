<?php
$servername = "localhost";
$database = "nbadb";
$username = "root";
$password = "";

try{
    $conexion = new PDO("mysql:host=$servername;port=3307;  dbname=$database",$username, $password );

} catch (Exception $ex) {
    echo $ex -> getMessage();

}

?>