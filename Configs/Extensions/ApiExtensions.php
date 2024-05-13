<?php

class ApiExtensions
{
    public static function getQueryParam(string $paramName, bool|mysqli $conn): mixed
    {
        return isset($_GET[$paramName]) || empty($_GET[$paramName]) 
            ? mysqli_real_escape_string($conn, $_GET[$paramName]) 
            : null;
    }
}