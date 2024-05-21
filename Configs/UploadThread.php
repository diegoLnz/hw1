<?php
require_once 'Extensions/DbConnection.php';
require_once 'Extensions/Repository.php';
require_once 'Extensions/DbMonad.php';
require_once 'Extensions/Diagnostics.php';
require_once 'Extensions/ImageUploader.php';
require_once 'Extensions/ApiExtensions.php';
require_once 'Extensions/SessionManager.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    header("Location: ../index.php?error=upload");
    exit;
}

SessionManager::startSession();
if(!SessionManager::has('user'))
{
    header("Location: ../Login.php");
    exit;
}

$user = $_SESSION['user'];
$imagePath = null;

$targetDirectory = "../Images/Posts/$user";
$imageUploader = new ImageUploader($targetDirectory);

if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE)
{
    $uploadResult = $imageUploader->uploadFile('file');
    if ($uploadResult === ImageValidationResult::OK->value)
    {
        $imagePath = $targetDirectory . '/' . basename($_FILES['file']['name']);
    } 
    else
    {
        header("Location: ../index.php?error=upload");
        exit;
    }
}

$savePost = SavePost($conn, $user, $imagePath);
if($savePost == false)
{
    header("Location: ../index.php?error=upload");
    exit;
}
header("Location: ../index.php?upload=success");
exit;

function SavePost(bool|mysqli $conn, string $username, string $imagePath): bool|int
{
    $description = ApiExtensions::getPostParam('description');

    try
    {
        $image = new Image($_FILES['file']);
        $imageId = SaveImage($conn, $image, $imagePath);

        $userRes = Repository::getAll(
            $conn, 
            'users', 
            [new WhereCondition('LOWER(username)', SqlOperators::EQUALS, strtolower($username))]
        );

        if(mysqli_num_rows($userRes) <= 0)
            throw new Exception("User non esistente, error.");

        $user = mysqli_fetch_assoc($userRes);

        $postToSave = new stdClass();
        $postToSave->post_description = $description;
        $postToSave->publish_date = date('Y-m-d H:i:s');
        $postToSave->user_id = $user['id'];
        $postToSave->image_id = $imageId;

        $res = Repository::saveOrUpdate($conn, 'posts', $postToSave);
        if($res == false)
            throw new Exception("Errore nel salvataggio del post");
    } catch (Exception $e)
    {
        Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
        return false;
    }

    return $res;
}

function SaveImage(bool|mysqli $conn, Image $image, string $imagePath): bool|int
{
    try
    {
        $imageToSave = new stdClass();
        $imageToSave->file_name = $image->name;
        $imageToSave->file_extension = $image->extension;
        $imageToSave->file_path = $imagePath;
    
        $res = Repository::saveOrUpdate($conn, 'images', $imageToSave);
        if($res == false)
            throw new Exception("Errore nel salvataggio dell' immagine");
    } catch (Exception $e)
    {
        Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
        return false;
    }

    return $res;
}