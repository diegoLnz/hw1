<?php
require_once "Configs/Extensions/SessionManager.php";

SessionManager::startSession();

if (!SessionManager::has("user")) {
    header("Location: Login.php");
    exit;
}
?>

<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/Modal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Radio+Canada:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <script src="Scripts/Index.js"></script>

    <!--===FAVICON===-->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>MySocialBook</title>
</head>
<body class="radio-canada-normal">

    <header>

        <nav class="nav">

            <div class="logo">
                <img id="logo" src="images/logo.png"></img>
            </div>
      
            <div class="nav-menu">
                <div class="nav-item" id="home">
                    <svg class="nav-icon selected" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                    </svg>
                </div>

                <div class="nav-item" id="search">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div>

                <div class="nav-item" id="new-thread">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </div>

                <div class="nav-item" id="activity">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                    </svg>
                </div>

                <div class="nav-item" id="personal-info" onclick="window.location.href = 'user.php'">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                    </svg>
                </div>
            </div>
            
            <div id="options">
                <svg class="menu-icon" viewBox="0 0 30 20" width="30" height="20">
                    <rect x="0" y="0" width="30" height="3"></rect>
                    <rect x="10" y="8" width="20" height="3"></rect>
                </svg>
                <div id="dropdown-menu" class="dropdown-menu d-none">
                    <div class="dropdown-item" id="logout">Esci</div>
                </div>
            </div>
            
        </nav>

        <div class="nav-background"></div>
            
    </header>

    <!-- Modal -->
    <form action="Configs/UploadThread.php" method="POST" enctype="multipart/form-data">
        <div id="new-thread-modal" class="modal d-none">
            <div id="new-thread-text-div">
                <p id="new-thread-text">Nuovo thread</p>
            </div>
            <div class="modal-content">
                <div class="modal-form-div">
                    <div class="user-info">
                        <div class="user-image"></div>
                        <div class="user-section-content">
                            <div class="main-username">
                                <a class="userlink" href="#"><?php echo SessionManager::get('user'); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="new-thread-inputs">
                        <textarea id="new-thread-input-text" maxlength="500" name="description" placeholder="Avvia un thread..."></textarea>
                        
                        <!-- Image upload -->
                        <div class="image-upload-div">
                            <button type="button" id="upload-button" class="upload-btn d-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                                </svg>
                            </button>

                            <input type="file" id="file-input" name="file" accept="image/jpeg, image/png" class="d-none">
                            <img id="image-preview" class="d-none" alt="Anteprima immagine">
                            
                            <button type="button" id="remove-image-button" class="d-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" id="submit-thread" class="publish-btn btn-disabled" value="Pubblica">
                </div>
            </div>
        </div>
    </form>
    
    <section class="content" id="post-container">

        <div class="start-thread">
            <div class="user-image"></div>
            <div class="start-thread-label">Avvia un thread...</div>
            <div class="publish-btn btn-disabled">Pubblica</div>
        </div>

        <!--STRUTTURA POST-->
        <!--<div class="single-post">

            <div class="post-header">
                <div class="user-info">
                    <div class="user-image"></div>
                    <div class="main-username">
                        <a class="userlink" href="#">generic_username1</a>
                    </div>
                </div>
                <div class="actions-and-date">
                    <p class="upload-date">9 m</p>
                    <div class="action-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="post-content">
                <p class="post-text">Che paesaggio mozzafiato.</p>
                <div class="post-image">
                    <img src="images/Posts/mountain.jpg">
                </div>
                <div class="actions-menu">
                    <div class="action-item">
                        <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                        </svg>
                    </div>
                    <div class="action-item">
                        <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
                        </svg>
                    </div>
                    <div class="action-item">
                        <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="post-footer pointed">
                <div class="users-footer-images">
                    <div class="user-footer-image"> ==USATA COME SINGOLA SOLO PER ESEMPIO== </div> 
                </div>
                <div class="replies-number">10 Risposte</div>
                <div>&nbsp;·&nbsp;</div>
                <div class="view-activities-action hover-underlined">Visualizza attività</div>
            </div>

        </div>-->

        

    </section>

    <footer>



    </footer>

</body>
</html>