<?php
$pageTitle = "Inscription";
require "Structure/Bdd/config.php"; // intègre la base de données
require "Structure/Functions/functions.php"; // intègre les fonctions

if (isset($_POST['formRegis'])) {

  if (
    empty($_POST['prenom']) ||
    empty($_POST['nom']) ||
    empty($_POST['mail']) ||
    empty($_POST['pwd']) ||
    empty($_POST['pwdConf'])
  ) die();

  // PARTIE INITIALISATION DES VARIABLES
  $prenom = htmlspecialchars($_POST['prenom']);
  $nom = htmlspecialchars($_POST['nom']);
  $mail = htmlspecialchars($_POST['mail']);
  $pwd = $_POST['pwd'];
  $pwdConf = $_POST['pwdConf'];
  $pwdFinal = password_hash($pwd, PASSWORD_BCRYPT);
  $prenomLength = strlen($prenom);
  $nomLength = strlen($nom);
  $mailLength = strlen($mail);

  // PARTIE REQUÊTE DANS LA BDD
  $reqMail = $bdd->prepare("SELECT * FROM member WHERE mail = ?");
  $reqMail->execute(array($mail));
  $mailCount = $reqMail->rowCount();

  // PARTIE DES MSGS D'ERREURS
  if ($prenomLength > 100) $errorPre = "Le prénom ne doit pas dépasser 100 caractères";
  if ($nomLength > 100) $errorNom = "Le nom ne doit pas dépasser 100 caractères";
  if ($mailLength > 255) $errorMail = "Le mail ne doit pas dépasser 255 caractères";
  if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $errorMail = "Adresse mail non valide";
  if ($mailCount != 0) $errorMail = "Adresse mail déjà utilisée";
  if ($pwd != $pwdConf) $errorPwd = "Les mots de passe ne correspondent pas";
  if (!preg_match('/^(?=.*\d)(?=.*[&\-é_èçà^ù*:!ù#~@°%§+.])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z&\-é_èçà^ù*:!ù#~@°%§+.]{4,50}$/', $pwd)) $errorPwd = "Le mot de passe doit comporter au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial de à @ é è & ç ù _ ! . + - : # % § ^ * ~ °";
  if (!isset($_POST['checkcheck'])) $errorCheck = "Vous devez accepter les règles et conditions d'utilisation pour créer un compte";

  // PARTIE KEY DE CONFIRMATION 
  $key = 0;
  for ($i = 1; $i < 16; $i++) {
    $key .= mt_rand(0, 9);
  }

  // PARTIE CREATION DE COMPTE ET MAIL DE CONFIRMATION 
  if (empty($errorPre) && empty($errorNom) && empty($errorMail) && empty($errorPwd) && empty($errorCheck)) {

    $corpsMail = 'Veuillez confirmer votre compte Fun Park pour finaliser l\'inscription en cliquant sur ce lien : http://localhost/funpark/confirmation.php?mail='.urlencode($mail).'&key='.$key.'';

    $insertmbr = $bdd->prepare("INSERT INTO member(name, firstname, mail, password, confirmkey) VALUES(?, ?, ?, ?, ?)");
    $insertmbr->execute(array($prenom, $nom, $mail, $pwdFinal, $key));
    mailer($mail, "Confirmation de compte", $corpsMail);
?>
    <script>
      var create = alert("Votre compte Fun Park a bien été créé.\nUn mail de confirmation vous a été envoyé !");
      document.location.href = "index.php";
    </script>
<?php

  }
}


require "Structure/Head/head.php"; //intègre le head 
?>
<link href="Design/css/register.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="global">
    <div id="contenu">
      <div class="login-form">
        <form method="post">
          <h4>Inscription</h4>
          <h1>Fun Park</h1>
          <div class="form-group">
            <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" required="required" autocomplete="on">
            <p class="errorr">
              <?php if (isset($errorPre)) echo $errorPre; ?>
            </p>
          </div>
          <div class="form-group">
            <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" required="required" autocomplete="on">
            <p class="errorr">
              <?php if (isset($errorNom)) echo $errorNom; ?>
            </p>
          </div>
          <div class="form-group">
            <input type="email" id="mail" name="mail" class="form-control" placeholder="Adresse mail" required="required" autocomplete="on">
            <p class="errorr">
              <?php if (isset($errorMail)) echo ($errorMail); ?>
            </p>
          </div>
          <div class="form-group">
            <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
          </div>
          <div class="form-group">
            <input type="password" id="pwdConf" name="pwdConf" class="form-control" placeholder="Confirmer le mot de passe" required="required" autocomplete="off">
            <p class="errorr">
              <?php if (isset($errorPwd)) echo $errorPwd; ?>
            </p>
          </div>
          <label>
            <p class="conditions">
              <input type="checkbox" name="checkcheck">
              &nbspEn cochant cette case, vous reconnaissez avoir lu et acceptez nos <a href="Structure/Pdf/ReglesFunPark.pdf" target="_blank">règles</a> et <a href="Structure/Pdf/ConditionsFunPark.pdf" target="_blank">conditions d'utilisation</a>.
            </p>
            <p class="errorCheck">
              <?php if (isset($errorCheck)) echo $errorCheck; ?>
            </p>
          </label>
          <div class="form-group">
            <input type="submit" class="btn btn-info" name="formRegis" value="Créer un compte">
          </div>
          <p class="dejainscrit">
            Vous avez déjà un compte ?
          </p>
          <a type="button" class="btn btn-outline-info" href="index.php">
            Se connecter
          </a>
        </form>
      </div>
    </div>
  </div>
</body>

</html>