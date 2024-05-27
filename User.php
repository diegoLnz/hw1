<?php
require_once "Configs/Extensions/SessionManager.php";
require_once 'Configs/Extensions/Repository.php';
require_once 'Configs/Extensions/DbConnection.php';

SessionManager::startSession();

if (!SessionManager::has("user"))
{
    header("Location: Login.php");
    exit;
}

$result = Repository::getAll(
    $conn, 
    'users', 
    [new WhereCondition('LOWER(username)', SqlOperators::EQUALS, SessionManager::get('user'))]
);

if(mysqli_num_rows($result) != 1)
{
    header("Location: Login.php");
    exit;
}

$row = mysqli_fetch_assoc($result);
$userId = $row['id'];
$userdataId = $row['userdata_id'];

$result = Repository::getAll(
    $conn, 
    'follows', 
    [
        new WhereCondition('follower_id', SqlOperators::EQUALS, $row['id'], LogicalOperators::LOGICAL_OR),
        new WhereCondition('followed_user_id', SqlOperators::EQUALS, $row['id'])
    ]
);

if(mysqli_num_rows($result) < 0)
{
    header("Location: Login.php");
    exit;
}
$followersNum = mysqli_num_rows($result);

$result = Repository::getAll(
    $conn, 
    'userdata', 
    [new WhereCondition('id', SqlOperators::EQUALS, $userdataId)]
);

if(mysqli_num_rows($result) != 1)
{
    header("Location: Login.php");
    exit;
}

$row = mysqli_fetch_assoc($result);

$userInfo = new stdClass();
$userInfo->id = $userId;
$userInfo->username = SessionManager::get('user');
$userInfo->name = $row['name_surname'];
$userInfo->followersNum = $followersNum;
?>

<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/Modal.css">
    <link rel="stylesheet" href="Styles/User.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Radio+Canada:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <script src="Scripts/DoLogout.js"></script>
    <script src="Scripts/UserPosts.js"></script>
    <script src="Scripts/PostGeneration.js" defer></script>

    <!--===FAVICON===-->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title><?php echo $userInfo->name . " (@" . $userInfo->username . ")";?> - MySocialBook</title>
</head>
<body class="radio-canada-normal">

    <header>

        <nav class="nav">

            <div class="logo">
                <img id="logo" src="images/logo.png"></img>
            </div>
      
            <div class="nav-menu">
                <div class="nav-item" id="home" onclick="window.location.href = 'index.php'">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
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

                <div class="nav-item" id="personal-info">
                    <svg class="nav-icon selected" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
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

    <div class="main">
        <div id="user-info">
            <div id="user-desc">
                <input id="user-id" type="hidden" value="<?php echo $userInfo->id; ?>">
                <span id="username">
                    <?php echo $userInfo->name; ?>
                </span>
                <span id="user-name">
                    <?php echo SessionManager::get('user'); ?>
                </span>
                <a href="#" id="num-followers">
                    Followers: <?php echo $userInfo->followersNum; ?>
                </a>
            </div>
            <img id="profile-image" src="images/generic_user.png" alt="Immagine profilo">
        </div>

        <div class="threads-list-div">
            <span id="threads-label">Threads</span>
            <div id="post-container">

            </div>
        </div>
    </div>

</body>
</html>