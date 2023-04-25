<?php 
require_once __DIR__ . "/database/database.php";
$authDB = require_once __DIR__ . "/database/security.php";
$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";

$currentUser = $authDB->isloggedin();
$currentUserArticles = $articleDB->fetchUserArticles($currentUser["id"]);

if(!$currentUser){
  header('Location: /');
}

$displayConfirmationMessage = false;

if($_POST) {
  $displayConfirmationMessage = true;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "includes/head.php"?>
  <link rel="stylesheet" href="public/css/index.css">
  <title>Mon compte</title>
</head>
<body>
  <div class="app-container">
    <?php require_once "includes/header.php"?>
    <div class="profile-container">
      <div id="confirmationPopup" style="display:none">
       <div class="confirmationMessageContainer">
        <div class="confirmationMessage">
          <p>Êtes-vous sûr de vouloir supprimer votre compte ?</p>
          <div class="confirmationMessageAction">
            <a href="/delete-account.php" class="btn btn-danger">Oui</a>
            <a href="/auth-profile.php" class="btn btn-primary">Non</a>
          </div>
        </div>
      </div> 
      </div>
      
      <div class="profile-sub-container">
        <div class="profile-sub-container-title">
          <h2>Mon compte</h2>
        </div>
        <ul>
          <li>Nom : <?= $currentUser["firstname"] ?></li>
          <li>Prénom : <?= $currentUser["lastname"] ?></li>
          <li>Email : <?= $currentUser["email"] ?></li>
        </ul>
        <div class="action-account">
          <button class="btn btn-danger btn-small" onclick="displayConfirmationMessage()">Supprimer le compte</button>
        <p class="small text-danger">La suppression du compte entraînera la suppression de vos articles</p>
      </div>
        
      </div>
      <div class="profile-sub-container">
        <div class="profile-sub-container-title">
          <h2>Mes articles</h2>
        <a href="/delete-all-user-articles.php">
          <i class="fa-solid fa-trash text-danger"></i>
        </a>
        </div>
            <ul>
              <?php foreach($currentUserArticles as $a): ?>
                <li>
                  <p>
                    <?= $a["title"] ?>
                  </p>
                  <div class="action-articles">
                    <a href="/show-article.php?id=<?=$a["id"]?>">
                      <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="/form-article.php?id=<?=$a["id"]?>">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="/delete-user-article.php?id=<?=$a["id"]?>">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                    
                  </div>
                </li>
                <?php endforeach; ?>
            </ul>
      </div>  
    </div>
    <?php require_once "includes/footer.php"?> 
  </div>
  <script>
    function displayConfirmationMessage() {
      document.getElementById("confirmationPopup").style.display = "block";
    }

  </script>
</body>
</html>
