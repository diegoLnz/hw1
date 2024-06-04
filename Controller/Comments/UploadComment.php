<?php
require_once '../../Configs/Extensions/QueryBuilder.php';
require_once '../../Configs/Extensions/DbConnection.php';
require_once '../../Configs/Extensions/ApiExtensions.php';
require_once '../../Configs/Extensions/GenericExtensions.php';
require_once '../../Configs/Extensions/Repository.php';
require_once '../../Configs/Extensions/Diagnostics.php';
require_once '../../Configs/Models/ApiResult.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    $response = setResponse("KO", "Metodo non consentito", 405);
    echo $response->toJson();
    exit();
}

$userId = ApiExtensions::getPostParam("user", $conn);
$postId = ApiExtensions::getPostParam("post", $conn);
$commentContent = ApiExtensions::getPostParam("comment_content", $conn);

if (GenericExtensions::isNullOrEmptyString($userId) || GenericExtensions::isNullOrEmptyString($postId) || GenericExtensions::isNullOrEmptyString($commentContent)) {
    header('Location: ../../index.php?error=comment_error');
    exit();
}

$commentData = [
    "content" => $commentContent,
    "created_at" => date('Y-m-d H:i:s'),
    "user_id" => $userId,
    "post_id" => $postId
];

$queryBuilder = new QueryBuilder($conn, "comments");
if (!$queryBuilder->insert($commentData)) {
    header('Location: ../../index.php?error=comment_error');
    exit();
}

header('Location: ../../index.php');
exit();