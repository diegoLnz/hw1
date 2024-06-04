<?php
require_once '../../Configs/Extensions/QueryBuilder.php';
require_once '../../Configs/Extensions/DbConnection.php';
require_once '../../Configs/Extensions/ApiExtensions.php';
require_once '../../Configs/Extensions/GenericExtensions.php';
require_once '../../Configs/Extensions/Repository.php';
require_once '../../Configs/Extensions/Diagnostics.php';
require_once '../../Configs/Models/ApiResult.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

$userToFollow = ApiExtensions::getQueryParam("follow", $conn);
$user = ApiExtensions::getQueryParam("user", $conn);

if (GenericExtensions::isNullOrEmptyString($userToFollow) || GenericExtensions::isNullOrEmptyString($user))
{
    $response = setResponse("KO", "Parametri mancanti", 400);
    echo $response->toJson();
    exit();
}

$queryBuilder = new QueryBuilder($conn, "follows f");
$result = $queryBuilder
->select("f.follower_id follower, f.followed_user_id followed")
->join("users u1", "f.follower_id = u1.id")
->join("users u2", "f.followed_user_id = u2.id")
->where("LOWER(u1.username)", SqlOperators::EQUALS, strtolower($user))
->where("LOWER(u2.username)", SqlOperators::EQUALS, strtolower($userToFollow))
->getQuery();

if (mysqli_num_rows($result) == 1)
{
    $row = mysqli_fetch_assoc($result);
    $unset = unsetFollow($row["follower"], $row["followed"], $conn);

    if($unset)
    {
        $response = setResponse("OK", "", 200);
        $responseData = $response->toJson();
        echo $responseData;
        exit();
    }
}

$userQb = new QueryBuilder($conn, "users");
$result = $userQb
->select("id")
->where("LOWER(username)", SqlOperators::EQUALS, strtolower($user))
->getQuery();

if (mysqli_num_rows($result) != 1)
{
    $response = setResponse("KO", "Parametri errati", 400);
    echo $response->toJson();
    exit();
}

$row = mysqli_fetch_assoc($result);

$followerId = $row["id"];

$userQb = new QueryBuilder($conn, "users");
$result = $userQb
->select("id")
->where("LOWER(username)", SqlOperators::EQUALS, strtolower($userToFollow))
->getQuery();

if (mysqli_num_rows($result) != 1)
{
    $response = setResponse("KO", "Parametri errati", 400);
    echo $response->toJson();
    exit();
}

$row = mysqli_fetch_assoc($result);

$followedUserId = $row["id"];

if(!setFollow($followerId, $followedUserId, $conn))
{
    $response = setResponse("KO", "Errore", 400);
    echo $response->toJson();
    exit();
}

$response = setResponse("OK", "", 200);
$responseData = $response->toJson();
echo $responseData;

function setFollow($follower, $followedUser, $conn): bool
{
    $follow = new stdClass();
    $follow->follower_id = $follower;
    $follow->followed_user_id = $followedUser;

    try
    {
        return Repository::saveOrUpdate($conn, "follows", $follow);
    } 
    catch (Exception $ex)
    {
        Diagnostics::traceMessage($ex->getMessage(), TraceLevel::Error, __METHOD__);
        return false;
    }
}

function unsetFollow($follower, $followedUser, $conn): bool
{
    try
    {
        $queryBuilder = new QueryBuilder($conn, "follows");
        $result = $queryBuilder
        ->delete()
        ->where("follower_id", SqlOperators::EQUALS, $follower)
        ->where("followed_user_id", SqlOperators::EQUALS, $followedUser)
        ->execute();

        return $result;
    } 
    catch (Exception $ex)
    {
        Diagnostics::traceMessage($ex->getMessage(), TraceLevel::Error, __METHOD__);
        return false;
    }
}

function setResponse(string $message, string $error, int $code): ApiResult
{
    $response = new ApiResult(["message" => $message], [$error]);
    http_response_code($code);
    return $response;
}