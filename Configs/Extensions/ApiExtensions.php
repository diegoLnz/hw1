<?php

class ApiExtensions
{
    public static function getQueryParam(string $paramName, bool|mysqli $conn = null): mixed
    {
        if (!isset($_GET[$paramName]) || empty($_GET[$paramName]))
            return null;

        return $conn != null 
                ? mysqli_real_escape_string($conn, $_GET[$paramName]) 
                : $_GET[$paramName];
    }

    public static function getPostParam(string $paramName, bool|mysqli $conn = null): mixed
    {
        if (!isset($_POST[$paramName]) || empty($_POST[$paramName]))
            return null;

        return $conn != null 
                ? mysqli_real_escape_string($conn, $_POST[$paramName]) 
                : $_POST[$paramName];
    }
}