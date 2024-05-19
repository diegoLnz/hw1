<?php
require_once 'Extensions/SessionManager.php';

SessionManager::startSession();
SessionManager::delete('user');
SessionManager::endSession();

header('Location: ../Login.php');
exit;