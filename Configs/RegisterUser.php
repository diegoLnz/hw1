<?php
require_once 'Extensions/DbConnection.php';
require_once 'Extensions/Repository.php';
require_once 'Extensions/DbMonad.php';
require_once 'Extensions/Diagnostics.php';

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

    $userId = saveUser([$conn, $username, $hashed_password]);
    if($userId === false)
        throw new Exception("Errore durante la registrazione dell'utente");

    $anagraficaId = saveAnagrafica([$conn, $name, $userId]);
    if($anagraficaId === false)
        throw new Exception("Errore durante la registrazione dell' anagrafica utente");

    $monad = DbMonad::unit([$conn, $userId, $anagraficaId])
        ->bind(fn($params) => updateUserAnagraficaId($params));

    if($monad->hasErrors())
        throw new Exception("Errori durante il salvataggio: " . implode(", ", $monad->getErrors()));

} catch (Exception $e) {
    Diagnostics::traceMessage($e->getMessage(), TraceLevel::Error, __METHOD__);
}

function saveUser($params): bool|int
{
    list($conn, $username, $password) = $params;

    //Dati utente
    $user = new stdClass();
    $user->username = $username;
    $user->password = $password;

    return Repository::saveOrUpdate($conn, "users", $user);
}

function saveAnagrafica($params): bool|int
{
    list($conn, $name, $userId) = $params;

    $name_parts = explode(" ", $name);
    $name = $name_parts[0];
    $surname = $name_parts[1];

    //Dati anagrafica splittati
    $anagrafica = new stdClass();
    $anagrafica->nome = $name;
    $anagrafica->cognome = $surname;
    $anagrafica->user_id = $userId;

    return Repository::saveOrUpdate($conn, "anagrafiche", $anagrafica);
}

function updateUserAnagraficaId($params): int
{
    list($conn, $userId, $anagraficaId) = $params;

    $updateQuery = new QueryBuilder($conn, "users");
    $updateQuery
        ->where('id', SqlOperators::EQUALS, $userId)
        ->update(['anagrafica_id' => $anagraficaId]);

    $updateQuery->execute();

    return $userId;
}
