<?php 

require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . "/database/security.php";

const ERROR_REQUIRED = "Renseigner ce champ";
const ERROR_EMAIL_INVALID = "Format d'email non valide";
const ERROR_INVALID_ACCOUNT = "Email et/ou mot de passe non valide";

  $errors = [
    "global" => "",
    "email" => "",
    "password" => "",
  ];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $input = filter_input_array(INPUT_POST, [
    "email" => FILTER_SANITIZE_EMAIL
  ]);

  $email = $input["email"];
  $password = $_POST["password"];

  if(!$email) {
    $errors["email"] = ERROR_REQUIRED;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = ERROR_EMAIL_INVALID;
  }

  if(!$password) {
    $errors["password"] = ERROR_REQUIRED;
  }

  if (!count(array_filter($errors, fn($e) => $e !== "" ))) {
    $user = $authDB->getUserFromEmail($email);
    if($user) {
      if(password_verify($password, $user["password"])) {
        $authDB->login($user["id"]);
        header('Location: /');
      } else {
        $errors["global"] = ERROR_INVALID_ACCOUNT;
      }
    } else {
      $errors["global"] = ERROR_INVALID_ACCOUNT;
    }

  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "includes/head.php"?>
  <link rel="stylesheet" href="public/css/style.css">
  <link rel="stylesheet" href="public/css/auth-login.css">
  <title>Connexion</title>
</head>
<body>
  <div class="app-container">
    <?php require_once "includes/header.php"?>
    <div class="page-container">
      <div class="card">
        <h2>Connexion</h2>
        <form action="/auth-login.php", method="post">
          <div class="form-control">
            <input type="email" name="email" placeholder="Email" id="name" value="<?= $email ?? "" ?>">
            <?php if($errors["email"]) : ?>
            <p class="text-error"><?= isset($errors["email"]) ? $errors["email"] : "" ?> </p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <input type="password" name="password" placeholder="Mot de passe (min. 6 caractères)" id=password">
            <?php if($errors["password"]) : ?>
            <p class="text-error"><?= isset($errors["password"]) ? $errors["password"] : "" ?> </p>
            <?php endif; ?>
          </div>
          <div>
            <?php if($errors["global"]) : ?>
            <p class="text-error"><?= isset($errors["global"]) ? $errors["global"] : "" ?> </p>
            <?php endif; ?>
          </div>
          <div class="form-action">
            <button class="btn btn-dark-light" type="submit">Se connecter</button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once "includes/footer.php"?> 
  </div>
</body>
</html>
