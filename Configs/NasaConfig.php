<?php

class NasaConfig 
{
    private static string $apiKey = "";

    public static function getApiKey(): string
    {
        return self::$apiKey;
    }
}