<?php
require_once '../../Configs/Models/ApiResult.php';
require_once '../../Configs/Extensions/GenericExtensions.php';
require_once '../../Configs/Extensions/CurlHelper.php';
require_once '../../Configs/NasaConfig.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET')
{
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

$apiKey = NasaConfig::getApiKey();
$curlHelper = new CurlHelper();
$response = $curlHelper->get("https://api.nasa.gov/planetary/apod?api_key=$apiKey");
$jsonResp = json_decode($response);

