<?php
require_once '../../Configs/Extensions/QueryBuilder.php';
require_once '../../Configs/Extensions/DbConnection.php';
require_once '../../Configs/Extensions/ApiExtensions.php';
require_once '../../Configs/Extensions/GenericExtensions.php';
require_once '../../Configs/Extensions/Repository.php';
require_once '../../Configs/Extensions/Diagnostics.php';
require_once '../../Configs/Models/ApiResult.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET')
{
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

$user = ApiExtensions::getQueryParam("user", $conn);
$post = ApiExtensions::getQueryParam("post", $conn);

if (GenericExtensions::isNullOrEmptyString($user) || GenericExtensions::isNullOrEmptyString($post))
{
    $response = setResponse("KO", "Parametri mancanti", 400);
    echo $response->toJson();
    exit();
}

$queryBuilder = new QueryBuilder($conn, "likes");
$result = $queryBuilder
->where("user_id", SqlOperators::EQUALS, $user)
->where("post_id", SqlOperators::EQUALS, $post)
->getQuery();

if (mysqli_num_rows($result) == 1)
{
    $queryBuilder = new QueryBuilder($conn, "likes");
    $deleted = $queryBuilder
    ->delete()
    ->where("user_id", SqlOperators::EQUALS, $user)
    ->where("post_id", SqlOperators::EQUALS, $post)
    ->execute();

    if(!$deleted)
    {
        $response = setResponse("KO", "Errore", 400);
        echo $response->toJson();
        exit();
    }

    $response = setResponse("OK", "", 200);
    echo $response->toJson();
    exit();
}

$likeData = [
    "user_id" => $user,
    "post_id" => $post
];

$queryBuilder = new QueryBuilder($conn, "likes");
if (!$queryBuilder->insert($likeData)) {
    $response = setResponse("KO", "Errore", 400);
    echo $response->toJson();
    exit();
}

$response = setResponse("OK", "", 200);
echo $response->toJson();
exit();

function setResponse(string $message, string $error, int $code): ApiResult
{
    $response = new ApiResult(["message" => $message], [$error]);
    http_response_code($code);
    return $response;
}