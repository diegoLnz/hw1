<?php
require_once "Configs/Extensions/SessionManager.php";

SessionManager::startSession();

if (SessionManager::has("user")) {
    header("Location: index.php");
    exit;
}
?>

<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/Login.css">

    <!--===FAVICON===-->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>MySocialBook - Login</title>
</head>
<body class="radio-canada-normal">

    <section class="login-section">
        
        <form id="login-form" action="Configs/LogUser.php" method="POST"> 
            
            <div class="login-form-div">
                <div class="access-label">Accedi con il tuo account MySocialBook</div>

                <div class="inputs">
                    <input type="text" class="login-input" id="username" name="username" placeholder="Nome utente o e-mail">
                    <input type="password" class="login-input" id="password" name="password" placeholder="Password">
                    <input type="submit" class="login-submit-btn" value="Accedi">
                </div>
                <a class="forgot-pass" href="#">Password dimenticata?</a>
                
                <div class="separer"><span>o</span></div>

                <div class="register-redirect-box" onclick="window.location.href = 'Register.php'">
                    <span>Registrati a MySocialBook</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="arrow-register" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                </div>
            </div>

        </form>

    </section>
    
</body>
</html>