<?php
require_once '../../Configs/Models/ApiResult.php';
require_once '../../Configs/Extensions/GenericExtensions.php';
require_once '../../Configs/Extensions/QueryBuilder.php';
require_once '../../Configs/Extensions/CurlHelper.php';
require_once '../../Configs/Extensions/DbConnection.php';
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

$pic = [
    'date' => $jsonResp->date,
    'explanation' => $jsonResp->explanation,
    'url' => $jsonResp->url
];

$picId = SavePicIfNotExists($pic, $conn);
if ($picId == 0)
{
    $response = setResponse("KO", "Errore durante il salvataggio della APOD", 400);
    echo $response->toJson();
    exit();
}

$picWithId = [
    'date' => $jsonResp->date,
    'explanation' => $jsonResp->explanation,
    'url' => $jsonResp->url,
    'post_id' => $picId
];

header('Content-Type: application/json');
$jsonPic = json_encode($picWithId);
echo $jsonPic;

function setResponse(string $message, string $error, int $code): ApiResult
{
    $response = new ApiResult(["message" => $message], [$error]);
    http_response_code($code);
    return $response;
}

function SavePicIfNotExists($picData, bool|mysqli $conn): int
{
    $realExplanation = mysqli_real_escape_string($conn, $picData['explanation']);
    $queryBuilder = new QueryBuilder($conn, "nasaposts");
    $result = $queryBuilder
    ->join("nasaimages", "nasaposts.image_id = nasaimages.id")
    ->select("nasaposts.id Id")
    ->where("nasaimages.network_path", SqlOperators::EQUALS, $picData['url'])
    ->getQuery();

    if (mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);
        $esito = $row['Id'];
        return $esito;
    }

    $imgId = SaveImage($picData['url'], $conn);
    if($imgId == 0){
        return 0;
    }

    $picToSave = [
        "post_description" => $realExplanation,
        "publish_date" => $picData['date'],
        "image_id" => $imgId
    ];

    $queryBuilder = new QueryBuilder($conn, "nasaposts");
    return $queryBuilder->insertAndGetLastId($picToSave);
}

function SaveImage($path, bool|mysqli $conn): int
{
    $img = [
        "network_path" => $path
    ];

    $queryBuilder = new QueryBuilder($conn, "nasaimages");
    return $queryBuilder->insertAndGetLastId($img);
}