<?php
$currentUser = $currentUser ?? false;
?>


<header>
  <a href="/" class="logo">Blog</a>
  <div class="headerMenuXS">
    <i class="fa-solid fa-bars headerMenuXSIcon" "></i>
    <ul class="headerMenuXSList" id="menuXS">
      <?php if($currentUser) : ?>
      <?php else : ?>
        <li class="<?= $_SERVER["REQUEST_URI"] === "/auth-register.php" ? "headerMenuActive" : "" ?>">
        <a href="/auth-register.php">Inscription</a>
      </li>
      <?php endif; ?>
      <?php if($currentUser) : ?>
      <?php else : ?>
      <li class="<?= $_SERVER["REQUEST_URI"] === "/auth-login.php" ? "headerMenuActive" : "" ?>">
        <a href="/auth-login.php">Connexion</a>
      </li>
      <?php endif; ?>
      <?php if($currentUser) : ?>
        <li class="<?= $_SERVER["REQUEST_URI"] === "/form-article.php" ? "headerMenuActive" : "" ?>">
        <a href="/form-article.php">Ajouter un article</a>
      </li>
      <?php endif; ?>
      <?php if($currentUser) : ?>
        <li class="<?= $_SERVER["REQUEST_URI"] === "/auth-logout.php" ? "headerMenuActive" : "" ?>">
        <a href="/auth-logout.php">Déconnexion</a>
      </li>
      <?php endif; ?>
      <?php if($currentUser) : ?>
        
        <a href="/auth-profile.php"><li class="<?= $_SERVER["REQUEST_URI"] === "/auth-profile.php" ? "headerMenuActive" : "" ?>">Mon compte</a>
      </li>
      <?php endif; ?>    
    </ul>

</div>
  <ul class="headerMenu">
    <?php if($currentUser) : ?>
    <?php else : ?>
      <li class="<?= $_SERVER["REQUEST_URI"] === "/auth-register.php" ? "headerMenuActive" : "" ?>">
      <a href="/auth-register.php">Inscription</a>
    </li>
    <?php endif; ?>
    <?php if($currentUser) : ?>
    <?php else : ?>
    <li class="<?= $_SERVER["REQUEST_URI"] === "/auth-login.php" ? "headerMenuActive" : "" ?>">
      <a href="/auth-login.php">Connexion</a>
    </li>
    <?php endif; ?>
    <?php if($currentUser) : ?>
      <li class="<?= $_SERVER["REQUEST_URI"] === "/form-article.php" ? "headerMenuActive" : "" ?>">
      <a href="/form-article.php">Ajouter un article</a>
    </li>
    <?php endif; ?>
    <?php if($currentUser) : ?>
      <li class="<?= $_SERVER["REQUEST_URI"] === "/auth-logout.php" ? "headerMenuActive" : "" ?>">
      <a href="/auth-logout.php">Déconnexion</a>
    </li>
    <?php endif; ?>
    <?php if($currentUser) : ?>
      
      <a href="/auth-profile.php"><li class="<?= $_SERVER["REQUEST_URI"] === "/auth-profile.php" ? "headerMenuActive" : "" ?> header-profile "><?= $currentUser["firstname"][0].$currentUser["lastname"][0] ?></a>
    </li>
    <?php endif; ?>
  </ul>
</header>