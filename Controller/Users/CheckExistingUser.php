<?php
require_once '../../Configs/Extensions/QueryBuilder.php';
require_once '../../Configs/Extensions/DbConnection.php';
require_once '../../Configs/Extensions/ApiExtensions.php';
require_once '../../Configs/Extensions/Diagnostics.php';
require_once '../../Configs/Models/ApiResult.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

if (!isset($conn)) {
    Diagnostics::traceMessage("Connessione al database non stabilita.", TraceLevel::Error);
    $response = self::setResponse("KO", "Errore interno del server", 500);
    echo $response->toJson();
    exit();
}

$username = ApiExtensions::getQueryParam("username", $conn);

if($username == null){
    $response = setResponse("KO", "Username mancante", 400);
    echo $response->toJson();
    exit();
}

try
{
    $queryOnDb = new QueryBuilder($conn, "users");
    $result = $queryOnDb
    ->select("username")
    ->where("LOWER(username)", SqlOperators::EQUALS, strtolower($username))
    ->getQuery();

    if(mysqli_num_rows($result) > 0)
    {
        $response = setResponse("KO", "Username già in uso", 200);
        echo $response->toJson();
        exit();
    }

    $response = setResponse("OK", "", 200);
    $responseData = $response->toJson();
    echo $responseData;

} catch (Exception $e)
{
    Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error);
    $response = setResponse("KO", "Errore interno del server", 500);
    $responseData = $response->toJson();
    echo $responseData;
}

function setResponse(string $message, string $error, int $code): ApiResult
{
    $response = new ApiResult(["message" => $message], [$error]);
    http_response_code($code);
    return $response;
}