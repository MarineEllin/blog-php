<?php 

  require_once __DIR__ . '/database/database.php';
  $authDB = require_once __DIR__ . '/database/security.php';
  $currentUser = $authDB->isLoggedin();


  $articleDB = require_once __DIR__ . "/database/models/ArticleDB.php";
  if(!$currentUser) {
    header('Location: /');
  }

  const ERROR_REQUIRED = "Renseigner ce champ";
  const ERROR_TITLE_TOO_SHORT = "Min. 5 caractères";
  const ERROR_TITLE_TOO_LONG = "Max. 80 caractères";
  const ERROR_CONTENT_TOO_SHORT = "Min. 20 caractères";
  const ERROR_IMAGE_URL = "Renseigner une url valide";
  
  $errors = [
    "title" => "",
    "image" => "",
    "category" => "",
    "content" => ""
  ];

  $category= "";

  $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $id = $_GET["id"] ?? "";

  if ($id) {
    $article = $articleDB->fetchOne($id);
    if($article["author"] !== $currentUser["id"]) {
      header('Location: /');
    }
    $title = $article["title"];
    $image = $article["image"];
    $category = $article["category"];
    $content = $article["content"];
  }
 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  
  $_POST = filter_input_array(INPUT_POST, [
    "title" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    "image" => FILTER_SANITIZE_URL,
    "category" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    "content" => [
      "filter" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "flags" => FILTER_FLAG_NO_ENCODE_QUOTES
    ]
  ]);

  $title = $_POST["title"] ?? "";
  $image = $_POST["image"] ?? "";
  $category = $_POST["category"] ?? "";
  $content = $_POST["content"] ?? "";

if(!$title) {
  $errors["title"] = ERROR_REQUIRED;
} else if(mb_strlen($title) < 5) {
  $errors["title"] = ERROR_TITLE_TOO_SHORT;
} else if(mb_strlen($title) > 80) {
  $errors["title"] = ERROR_TITLE_TOO_LONG;
}

if(!$image) {
  $errors["image"] = ERROR_REQUIRED;
} elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
  $errors["image"] = ERROR_IMAGE_URL;
}

if(!$category) {
  $errors["category"] = ERROR_REQUIRED;
}

if(!$content) {
  $errors["content"] = ERROR_REQUIRED;
} elseif (mb_strlen($content) < 20) {
  $errors["content"] = ERROR_CONTENT_TOO_SHORT;
}

if (!count(array_filter($errors, fn($e) => $e !== "" ))) {
  if ($id) {
     $article['title'] = $title;
      $article['image'] = $image;
      $article['category'] = $category;
      $article['content'] = $content;
      $article["author"] = $currentUser["id"];
      $articleDB->updateOne($article);
  } else {
    $articleDB->createOne([
      "title" => $title,
      "content" => $content,
      "category" => $category,
      "image" => $image,
      "author"=> $currentUser["id"]
    ]);
  } 
  header("Location: /");
}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "includes/head.php"?>
  <link rel="stylesheet" href="/public/css/style.css">
  <title><?= $id ? "Modifier" : "Ajouter" ?> un article</title>
</head>
<body>
  <div class="app-container">
    <?php require_once "includes/header.php"?>
    <div class="page-container">
      <div class="card">
        <h2><?= $id ? "Modifier l'article" : "Créer un nouvel article" ?></h1>
        <form action="/form-article.php<?= $id ? "?id=$id" : "" ?>", method="post">
          <div class="form-control">
            <input type="text" name="title" placeholder="Titre" id="title" value="<?= $title ?? "" ?>">
            <?php if($errors["title"]) : ?>
            <p class="text-error"><?= isset($errors["title"]) ? $errors["title"] : "" ?> </p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <input type="text" name="image" placeholder="Image" id="image" value="<?= $image ?? "" ?>">
            <?php if($errors["image"]) : ?>
            <p class="text-error"><?= $errors["image"] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <label for="category">Catégorie</label>
            <select name="category" id="category">
              <option <?= !$category || $category === "Technologie" ? "selected" : "" ?> value="Technologie">Technologie</option>
              <option <?= $category === "Nature" ? "selected" : "" ?> value="Nature">Nature</option>
              <option <?= $category === "Politique" ? "selected" : "" ?> value="Politique">Politique</option>
            </select>
            <?php if($errors["category"]) : ?>
            <p class="text-error"><?= isset($errors["category"]) ? $errors["category"] : "" ?></p>
             <?php endif; ?>
          </div>
          <div class="form-control">
           <textarea name="content" id="content" placeholder="Écrivez votre article..."><?= $content ?? "" ?></textarea>
            <?php if($errors["content"]) : ?>
           <p class="text-error"><?= isset($errors["content"]) ? $errors["content"] : "" ?></p>
           <?php endif; ?>
          </div>
          <div class="form-action">
            <a href="<?= $id ? "/show-article.php?id=$id" : "/" ?>" class="btn btn-danger mr-10">Annuler</a>
            <button class="btn btn-dark-light" type="submit">Enregistrer <?= $id ? " les modifications" : "" ?></button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once "includes/footer.php"?> 
  </div>
  
</body>
</html>