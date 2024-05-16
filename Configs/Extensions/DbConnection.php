<?php

$dbconfig = [
    'host'     => 'localhost',
    'name'     => 'hw1',
    'user'     => 'root',
    'password' => ''
];
$port = 3307;

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name'], $port) 
    or die("Connessione al server non riuscita.");

mysqli_query($conn, "set names 'utf8'");


    
    