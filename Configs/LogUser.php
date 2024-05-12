<?php
require 'Extensions/DbConnection.php';
require 'Extensions/Repository.php';
require 'Extensions/DbMonad.php';

$username = strtolower($_POST["username"]);
$password = $_POST["password"];

$monad = DbMonad::unit([$conn, $username, $password])
    ->bind(fn($params) => findUser($params));

try
{
    if($monad->hasErrors())
        throw new Exception("Errori durante la ricerca di un utente: " . implode(", ", $monad->getErrors()));

    $success = $monad->getValue();

    if(!$success)
        echo "Username o password errati.";

    session_start();
    $_SESSION["user"] = $username;
    header("Location: ../index.php");

} catch (Exception $e) {
    Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
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

