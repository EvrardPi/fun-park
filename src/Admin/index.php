<?php
session_start();
$pageTitle = "Connexion Admin";
require "Structure/Bdd/config.php"; // intègre la base de données

if (isset($_POST['formconnect'])) {

  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $pwdconnect = $_POST['pwdconnect'];
  $role = "Gestionnaire";

  $stmt = $bdd->prepare('SELECT * FROM member WHERE mail = ? && role = ?');
  $stmt->execute(array($_POST['mailconnect'],$role));
  $resultGest = $stmt->fetch();

  if (empty($mailconnect) || empty($pwdconnect)) $erreur = "Tous les champs doivent être comptétés";
  if (empty($resultGest)) $erreur = "Authentification échouée"; // Adresse mail inexistante ou compte non gestionnaire

  if (isset($resultGest['password'])) {
    $pwdcrypt = $resultGest['password'];
    if (!password_verify($pwdconnect, $pwdcrypt)) $erreur = "Authentification échouée"; // Mot de passe incorrecte
  }

  if (empty($erreur)) {
    $_SESSION['id'] = $resultGest['id'];
    $_SESSION['name'] = $resultGest['name'];
    $_SESSION['firstname'] = $resultGest['firstname'];
    $_SESSION['mail'] = $resultGest['mail'];
    $_SESSION['role'] = $resultGest['role'];

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
        </form>
      </div>
    </div>
  </div>
</body>

</html>