<?php
require_once '../../Configs/Extensions/DbConnection.php';
require_once '../../Configs/Extensions/GenericExtensions.php';
require_once '../../Configs/Extensions/ApiExtensions.php';
require_once '../../Configs/Extensions/Repository.php';
require_once '../../Configs/Extensions/QueryBuilder.php';
require_once '../../Configs/Extensions/SessionManager.php';
require_once '../../Configs/Models/ApiResult.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

$search = ApiExtensions::getQueryParam("search", $conn);
$user = ApiExtensions::getQueryParam("user", $conn);
$search = strtolower($search);

if (empty($search)) {
    $response = setResponse("KO", "Parametro di ricerca mancante", 400);
    echo $response->toJson();
    exit();
}

if(GenericExtensions::isNullOrEmptyString($user))
{
    $response = setResponse("KO", "Non sei loggato", 400);
    echo $response->toJson();
    exit();
}

$qb = new QueryBuilder($conn, "users");
$qb->join("userdata", "users.userdata_id = userdata.id")
->beginWhereGroup()
    ->where("LOWER(users.username)", SqlOperators::LIKE, "%$search%")
    ->where("LOWER(userdata.name_surname)", SqlOperators::LIKE, "%$search%", logicalOperators::LOGICAL_OR)
->endWhereGroup()
->where("LOWER(users.username)", SqlOperators::NOT_EQUAL, strtolower($user), logicalOperators::LOGICAL_AND);

$result = $qb->getQuery();

if (!$result) {
    $response = setResponse("KO", "Errore nella query", 500);
    echo $response->toJson();
    exit();
}

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = [
        'username' => $row['username'],
        'name_surname' => $row['name_surname']
    ];
}

header('Content-Type: application/json');
$response = json_encode($users);
echo $response;

function setResponse(string $message, string $error, int $code): ApiResult
{
    $response = new ApiResult(["message" => $message], [$error]);
    http_response_code($code);
    return $response;
}


