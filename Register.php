<?php
if(isset($_SESSION["username"]))
    header("Location: 'Index.php'");

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/Register.css">
    <script src="Scripts/Register.js"></script>

    <!--===FAVICON===-->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>MySocialBook - Registrati</title>
</head>
<body class="radio-canada-normal">

    <section class="register-section">
        
        <form id="register-form" action="Configs/RegisterUser.php" method="POST"> 
            
            <div class="register-box">

                <div class="register-box-header">
                    <img src="images/logo.png" alt="Logo image" onclick="window.location.href='Index.php'">
                </div>

                <div class="register-box-content">
                    <span>Iscriviti per vedere le foto dei tuoi amici.</span>

                    <div class="register-box-content-inputs">

                        <input type="email" name="email" id="email" placeholder="Indirizzo e-mail">
                        <input type="text" name="name" id="name" placeholder="Nome e cognome">
                        <input type="text" name="username" id="username" placeholder="Nome utente">

                        <div class="password-container">
                            <input type="password" name="password" id="password" placeholder="Password">
                            <button type="button" class="password-toggle" onclick="togglePassword(this, 'password')">Mostra</button>
                        </div>

                        <div class="password-container">
                            <input type="password" name="password-confirm" id="password-confirm" placeholder="Conferma password">
                            <button type="button" class="password-toggle" onclick="togglePassword(this, 'password-confirm')">Mostra</button>
                        </div>

                    </div>

                    <div class="register-submit-box">
                        <input type="submit" class="register-submit-btn" value="Iscriviti">
                    </div>

                    <div class="login-redirect">
                        <span>Hai già un account?</span>
                        <a href="Login.php">Accedi</a>
                    </div>
                </div>

            </div>

        </form>

    </section>
    
</body>
</html>