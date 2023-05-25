<?php
session_start();
$pageTitle = "Connexion";
require "Structure/Bdd/config.php"; // intègre la base de données

if (isset($_POST['formconnect'])) {

  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $pwdconnect = $_POST['pwdconnect'];

  $stmt = $bdd->prepare('SELECT * FROM member WHERE mail = :mail');
  $stmt->bindValue('mail', $mailconnect, PDO::PARAM_STR); // Représente types de do CHAR, VARCHAR ou les autres sous forme de chaîne de caractères SQL.
  $rslt = $stmt->execute();
  $resultMember = $stmt->fetch();

  if (empty($mailconnect) || empty($pwdconnect)) $erreur = "Tous les champs doivent être comptétés";
  if (empty($resultMember)) $erreur = "Authentification échouée"; // Adresse mail inexistante
  if (!isset($resultMember['confirm'])) $erreur = "L'adresse mail doit être confirmée";


  if (isset($resultMember['password'])) {
    $pwdcrypt = $resultMember['password'];
    if (!password_verify($pwdconnect, $pwdcrypt)) $erreur = "Authentification échouée"; // Mot de passe incorrecte
  }

  if (empty($erreur)) {
    $_SESSION['id'] = $resultMember['id'];
    $_SESSION['name'] = $resultMember['name'];
    $_SESSION['firstname'] = $resultMember['firstname'];
    $_SESSION['mail'] = $resultMember['mail'];
    header("Location: attractions.php?id=" . $_SESSION['id']);
  }
}

require "Structure/Head/head.php"; //intègre le head 
?>
<link href="Design/css/login.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div id="global">
    <div id="contenu">
      <div class="login-form">
        <form method="post">
          <h4>Connexion</h4>
          <h1>Fun Park</h1>
          <div class="form-group">
            <input type="email" id="mailconnect" name="mailconnect" class="form-control" placeholder="Adresse mail" required="required" autocomplete="on">
          </div>
          <div class="form-group">
            <input type="password" id="pwdconnect" name="pwdconnect" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
            <p class="errorrr">
              <?php if (isset($erreur)) {
                echo "$erreur";
              } ?>
            </p>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-info" name="formconnect" value="Connexion">
          </div>
          <p class="pasdecompte">
            Vous n'avez pas de compte ?
          </p>
          <a type="button" class="btn btn-outline-info" href="register.php">
            S'inscrire
          </a>
        </form>
      </div>
    </div>
  </div>
</body>

</html>