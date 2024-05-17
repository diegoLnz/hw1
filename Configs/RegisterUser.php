<?php
require_once 'Extensions/DbConnection.php';
require_once 'Extensions/Repository.php';
require_once 'Extensions/DbMonad.php';
require_once 'Extensions/Diagnostics.php';
require_once 'Extensions/SessionManager.php';

try
{
    //Data fetch
    $email = $_POST["email"];
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password-confirm"];

    // Validazione dati
    if(empty($email) || empty($name) || empty($username) || empty($password) || empty($password_confirm)) {
        throw new Exception("Tutti i campi sono obbligatori");
    }

    if($password !== $password_confirm) {
        throw new Exception("Le password non corrispondono");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Formato dell'email non valido");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $userdataId = saveUserData([$conn, $name, $email]);
    if($userdataId === false)
        throw new Exception("Errore durante la registrazione dei dati utente");
    
    $userId = saveUser([$conn, $username, $hashed_password, $userdataId]);
    if($userId === false)
        throw new Exception("Errore durante la registrazione dell'utente");

    SessionManager::startSession();
    SessionManager::set("user", $username);

    header("Location: ../index.php");

} catch (Exception $e) {
    Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
}

function saveUser($params): bool|int
{
    list($conn, $username, $password, $userdataId) = $params;

    //Dati utente
    $user = new stdClass();
    $user->username = $username;
    $user->password = $password;
    $user->userdata_id = $userdataId;

    return Repository::saveOrUpdate($conn, "users", $user);
}

function saveUserData($params): bool|int
{
    list($conn, $name_surname, $email) = $params;

    //Dati splittati
    $anagrafica = new stdClass();
    $anagrafica->name_surname = $name_surname;
    $anagrafica->email = $email;

    return Repository::saveOrUpdate($conn, "userdata", $anagrafica);
}
