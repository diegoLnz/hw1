<?php

class NasaConfig 
{
    private static string $apiKey = "pwkGmC0CilUfQDkxBNWA6mMcTDrl8C5K3oKtyVGG";

    public static function getApiKey(): string
    {
        return self::$apiKey;
    }
}