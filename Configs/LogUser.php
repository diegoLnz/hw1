<?php
require_once 'Extensions/DbConnection.php';
require_once 'Extensions/Repository.php';
require_once 'Extensions/DbMonad.php';
require_once 'Extensions/SessionManager.php';

$username = strtolower($_POST["username"]);
$password = $_POST["password"];

$monad = DbMonad::unit([$conn, $username, $password])
    ->bind(fn($params) => findUser($params));

try
{
    if($monad->hasErrors())
        throw new Exception("Errori durante la ricerca di un utente: " . implode(", ", $monad->getErrors()));

    $success = $monad->getValue();

    if(!$success){
        header("Location: ../Login.php?error=invalid_credentials");
        exit;
    }

    SessionManager::startSession();
    SessionManager::set("user", $username);

    header("Location: ../index.php");
    exit;

} catch (Exception $e) {
    Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
    SessionManager::endSession();
    header("Location: ../Login.php?error=generic_error");
    exit;
}

function findUser($params): bool
{
    list($conn, $username, $pass) = $params;

    $result = Repository::getAll($conn, "users", [new WhereCondition("LOWER(username)", "=", $username)]);
    
    if(mysqli_num_rows($result) <= 0)
        return false;

    $user = mysqli_fetch_assoc($result);

    return password_verify($pass, $user["password"]);
}

