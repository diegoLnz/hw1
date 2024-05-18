<?php
require_once 'Extensions/SessionManager.php';

SessionManager::startSession();
SessionManager::delete('username');
SessionManager::endSession();

header('Location: ../Login.php');
exit;