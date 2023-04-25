<?php 

  $pdo = require_once __DIR__ . "/database/database.php";
  $authDB = require_once __DIR__ . "/database/security.php";

  const ERROR_REQUIRED = "Renseigner ce champ";
  const ERROR_TOO_SHORT = "Min. 2 caractères";
  const ERROR_PASSWORD_TOO_SHORT = "Min. 6 caractères";
  const ERROR_EMAIL_INVALID = "Email non valide";
  const ERROR_CONFIRMEDPASSWORD_NOT_VALID = "Les mots de passe ne correspondent pas";

  $errors = [
    "firstname" => "",
    "lastname" => "",
    "email" => "",
    "password" => "",
    "confirmedPassword" => ""
  ];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $input = filter_input_array(INPUT_POST, [
    "firstname" => FILTER_SANITIZE_SPECIAL_CHARS,
    "lastname" => FILTER_SANITIZE_SPECIAL_CHARS,
    "email" => FILTER_SANITIZE_EMAIL
  ]);

  $firstname = $input["firstname"];
  $lastname = $input["lastname"];
  $email = $input["email"];
  $password = $_POST["password"];
  $confirmedPassword = $_POST["confirmedPassword"];

  if(!$firstname) {
    $errors["firstname"] = ERROR_REQUIRED;
  } else if (mb_strlen($firstname) < 2) {
    $errors["firstname"] = ERROR_TOO_SHORT;
  }

  if(!$lastname) {
    $errors["lastname"] = ERROR_REQUIRED;
  } else if (mb_strlen($lastname) < 2) {
    $errors["lastname"] = ERROR_TOO_SHORT;
  }

  if(!$email) {
    $errors["email"] = ERROR_REQUIRED;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = ERROR_EMAIL_INVALID;
  }

  if(!$password) {
    $errors["password"] = ERROR_REQUIRED;
  } else if (mb_strlen($password) < 6) {
    $errors["password"] = ERROR_PASSWORD_TOO_SHORT;
  }

  if(!$confirmedPassword) {
    $errors["confirmedPassword"] = ERROR_REQUIRED;
  } else if ($confirmedPassword !== $password) {
    $errors["confirmedPassword"] = ERROR_CONFIRMEDPASSWORD_NOT_VALID;
  }

  if (!count(array_filter($errors, fn($e) => $e !== "" ))) {
    $authDB->register([
      "firstname" => $firstname,
      "lastname" => $lastname,
      "email" => $email,
      "password" => $password
    ]);
    header('Location: /auth-login.php');
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once "includes/head.php"?>
  <link rel="stylesheet" href="/public/css/style.css">
  <title>Inscription</title>
</head>
<body>
  <div class="app-container">
    <?php require_once "includes/header.php"?>
    <div class="page-container">
      <div class="card">
        <h2>Inscription</h2>
        <form action="/auth-register.php", method="post">
          <div class="form-control">
            <input type="text" name="firstname" placeholder="Prénom (min. 2 caractères)" id="firstname" value="<?= $firstname ?? "" ?>">
            <?php if($errors["firstname"]) : ?>
            <p class="text-error"><?= isset($errors["firstname"]) ? $errors["firstname"] : "" ?> </p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <input type="text" name="lastname" placeholder="Nom (min. 2 caractères)" id="lastname" value="<?= $lastname ?? "" ?>">
            <?php if($errors["lastname"]) : ?>
            <p class="text-error"><?= isset($errors["lastname"]) ? $errors["lastname"] : "" ?> </p>
            <?php endif; ?>
          </div>
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
          <div class="form-control">
            <input type="password" name="confirmedPassword" placeholder="Confirmation du mot de passe" id=confirmedPassword">
            <?php if($errors["confirmedPassword"]) : ?>
            <p class="text-error"><?= isset($errors["confirmedPassword"]) ? $errors["confirmedPassword"] : "" ?> </p>
            <?php endif; ?>
          </div>
          <div class="form-action">
            <a href="<?= $id ? "/show-article.php?id=$id" : "/" ?>" class="btn btn-danger mr-10">Annuler</a>
            <button class="btn btn-dark-light" type="submit">Créer un compte</button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once "includes/footer.php"?> 
  </div>
</body>
</html>
