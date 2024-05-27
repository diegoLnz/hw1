<?php
require_once '../../Configs/Extensions/DbConnection.php';
require_once '../../Configs/Extensions/Repository.php';
require_once '../../Configs/Extensions/ApiExtensions.php';
require_once '../../Configs/Extensions/Diagnostics.php';
require_once '../../Configs/Models/ApiResult.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') 
{
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

$userId = ApiExtensions::getQueryParam("id", $conn);

$result = Repository::getAll(
    $conn,
    "posts",
    [new WhereCondition("user_id", SqlOperators::EQUALS, $userId)]
);

$posts = [];

while ($row = mysqli_fetch_assoc($result)) 
{
    $postId = $row['id'];
    $imageId = $row['image_id'];
    $userId = $row['user_id'];

    // Fetch image details
    $imgResult = Repository::getAll(
        $conn,
        "images",
        [new WhereCondition("id", SqlOperators::EQUALS, $imageId)]
    );

    if (mysqli_num_rows($imgResult) != 1) 
    {
        $response = setResponse("KO", "Errore generico", 400);
        echo $response->toJson();
        exit();
    }

    $imgRow = mysqli_fetch_assoc($imgResult);

    // Fetch user details
    $userResult = Repository::getAll(
        $conn,
        "users",
        [new WhereCondition("id", SqlOperators::EQUALS, $userId)]
    );

    if (mysqli_num_rows($userResult) != 1) 
    {
        $response = setResponse("KO", "Errore generico", 400);
        echo $response->toJson();
        exit();
    }

    $userRow = mysqli_fetch_assoc($userResult);

    // Fetch userdata details
    $userDataResult = Repository::getAll(
        $conn,
        "userdata",
        [new WhereCondition("id", SqlOperators::EQUALS, $userRow['userdata_id'])]
    );

    if (mysqli_num_rows($userDataResult) != 1) 
    {
        $response = setResponse("KO", "Errore generico", 400);
        echo $response->toJson();
        exit();
    }

    $userDataRow = mysqli_fetch_assoc($userDataResult);

    $posts[] = [
        'post_id' => $postId,
        'post_description' => $row['post_description'],
        'publish_date' => $row['publish_date'],
        'image' => [
            'file_name' => $imgRow['file_name'],
            'file_extension' => $imgRow['file_extension'],
            'file_path' => $imgRow['file_path']
        ],
        'user' => [
            'username' => $userRow['username'],
            'name_surname' => $userDataRow['name_surname'],
            'email' => $userDataRow['email'],
            'profile_pic' => getImagePath($conn, $userRow['profile_pic_id'])
        ]
    ];
}

header('Content-Type: application/json');
$response = json_encode($posts);
echo $response;

function setResponse(string $message, string $error, int $code): ApiResult
{
    $response = new ApiResult(["message" => $message], [$error]);
    http_response_code($code);
    return $response;
}

function getImagePath($conn, $imageId)
{
    $imgResult = Repository::getAll(
        $conn,
        "images",
        [new WhereCondition("id", SqlOperators::EQUALS, $imageId)]
    );

    if (mysqli_num_rows($imgResult) == 1) {
        $imgRow = mysqli_fetch_assoc($imgResult);
        return [
            'file_name' => $imgRow['file_name'],
            'file_extension' => $imgRow['file_extension'],
            'file_path' => $imgRow['file_path']
        ];
    }
    return null;
}