<?php
require_once __DIR__ . "/database/database.php";
$authDB = require __DIR__ . "/database/security.php";

$currentUser = $authDB->isLoggedin();
if ($currentUser) {
  $articleDB = require_once __DIR__ . '/database/models/ArticleDB.php';
  $articleDB->deleteAllUserArticles($currentUser["id"]);
}

header('Location: /auth-profile.php');