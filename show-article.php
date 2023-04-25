<?php 

require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';
$currentUser = $authDB->isLoggedin();
$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";

  $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $id = $_GET["id"] ?? "";

  if(!$id) {
    header("Location: /");
  } else {
    $article = $articleDB->fetchOne($id);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "includes/head.php"?>
  <link rel="stylesheet" href="public/css/show-article.css">
  <title>Article</title>
</head>
<body>
  <div class="app-container">
    <?php require_once "includes/header.php"?>
    <div class="content">
      <div class="show-article">
        <a href="/" class="retour">Retour à la liste des articles</a>
        <div class="img-container" style="background-image:url(<?= $article["image"] ?>)"></div>
        <h2><?= $article["title"] ?></h2>
        <div class="article-content">
          <?= $article["content"] ?>
        </div>
        <p class="article-author">
          <?= $article["firstname"] . " " . $article["lastname"] ?>
        </p>
        <?php if($currentUser && $currentUser["id"] === $article["author"]) : ?>
        <div class="action">
          <a href="/delete-article.php?id=<?= $article["id"] ?>" class="btn btn-danger mr-10">Supprimer</button>
          <a href="/form-article.php?id=<?= $article["id"] ?>" class="btn btn-dark-light">Modifier</a>
        </div>
        <?php endif; ?>
      </div>
  </div>
  <?php require_once "includes/footer.php"?> 
</body>
</html>