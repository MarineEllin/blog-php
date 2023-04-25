<?php 

require __DIR__ . "/database/database.php";
$authDB = require __DIR__ . "/database/security.php";

$currentUser = $authDB->isloggedin();
$articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";
$articles = $articleDB->fetchAll();
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCategory = $_GET["cat"] ?? "";

if(count($articles)) {
  $cattmp = array_map(fn($a) => $a["category"], $articles);
  $categories = array_reduce($cattmp, function ($acc, $cat) {
    if(isset($acc[$cat])) {
      $acc[$cat]++;
    } else {
      $acc[$cat] = 1;
    }
    return $acc;
  }, []);
  $articlesPerCategories = array_reduce($articles, function($acc, $article) {
    if(isset($acc[$article["category"]])){
      $acc[$article["category"]] = [...$acc[$article["category"]], $article];
    } else {
      $acc[$article["category"]] = [$article];
    }
    return $acc;
  }, []);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "includes/head.php"?>
  <link rel="stylesheet" href="public/css/index.css">
  <title>BLOG</title>
</head>
<body>
  <div class="app-container">
    <?php require_once "includes/header.php"?>
    <div class="articlesAndCategoriesMenuContainer">
      <ul class="menuCategories">
        <li class="<?= $selectedCategory ? "" : "cat-active" ?>"> <a href="/"> Tous les articles <span class="small bold">(<?= count($articles) ?>)</span></a></li>
        <?php foreach($categories as $catName => $catNum): ?>
          <li class=<?= $selectedCategory === $catName ? "cat-active" : "" ?>><a href="/?cat=<?= $catName ?>"><?= $catName ?> <span class="small">(<?= $catNum ?>)</span></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="articles-container">
        <div class="categories-container">
          <?php if(!$selectedCategory) : ?>
          <?php foreach($categories as $cat => $num) : ?>
            <h2><?= $cat ?></h2>
            <div class="content-articles">
            <?php foreach ($articlesPerCategories[$cat] as $a) : ?>
            <a href="/show-article.php?id=<?= $a["id"] ?>" class="article box">
              <div class="overflow-hidden">
                <div class="img-container" style="background-image:url(<?= $a["image"] ?>)">
                </div>
              </div> 
              <h3><?= $a["title"]?></h3>
              <?php if($a["author"]) : ?>
                <div class="article-author">
                  <p>
                    <?= $a["firstname"] . " " . $a["lastname"] ?>
                  </p>
                </div>
              <?php endif; ?>
            </a>
            <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <?php else : ?>
          <h2><?= $selectedCategory ?></h2>
          <div class="content-articles">
          <?php foreach ($articlesPerCategories[$selectedCategory] as $a) : ?>
            <a href="/show-article.php?id=<?= $a["id"] ?>" class="article box">
              <div class="overflow-hidden">
                <div class="img-container" style="background-image:url(<?= $a["image"] ?>)">
                </div>
              </div> 
              <h3><?= $a["title"]?></h3>
              <?php if($a["author"]) : ?>
                <div class="article-author">
                  <p>
                    <?= $a["firstname"] . " " . $a["lastname"] ?>
                  </p>
                </div>
              <?php endif; ?>
          </a>
          <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>      
    </div>
    <?php require_once "includes/footer.php"?> 
  </div>
</body>
</html>