<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "mysocialbook_dev";
$port = 3307;

$conn = mysqli_connect($host, $user, $password, $db, $port) 
    or die("Connessione al server non riuscita.");

mysqli_query($conn, "set names 'utf8'");


    
    