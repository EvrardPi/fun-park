<?php
session_start();
$pageTitle = "Mon Profil";
require "Structure/Bdd/config.php"; // intègre la base de données

$grool = "Gestionnaire";

if (!empty($_GET['id'])) $getId = intval($_GET['id']);

// PARTIE DU CLIENT CONNECTÉ
if ($getId == $_SESSION['id'] && $_SESSION['role'] == $grool) {

  // PARTIE INFO USER
  $stmt = $bdd->prepare('SELECT * FROM member WHERE id = :id');
  $stmt->bindValue('id', $getId, PDO::PARAM_INT); // Représente le type de données INTEGER SQL.
  $result = $stmt->execute();
  $infoUser = $stmt->fetch();
  $idUser = $infoUser['id'];
  $mailUser = $infoUser['mail'];
  $nameUser = $infoUser['name'];
  $firstNameUser = $infoUser['firstname'];
  $roleUser = $infoUser['role'];
  $pwdcryptUser = $infoUser['password'];

  if (isset($_POST['formChange'])) {

    // PARTIE VERIF POUR LES CHAMPS DE MODIF MDP
    if (isset($_POST['oldPwd']) && empty($_POST['newPwd']) && empty($_POST['confirmNewPwd'])) $errorNewPwd = "Tous les champs doivent être renseignés pour modifier le mot de passe";
    if (isset($_POST['newPwd']) && empty($_POST['oldPwd']) && empty($_POST['confirmNewPwd'])) $errorNewPwd = "Tous les champs doivent être renseignés pour modifier le mot de passe";
    if (isset($_POST['confirmNewPwd']) && empty($_POST['newPwd']) && empty($_POST['oldPwd'])) $errorNewPwd = "Tous les champs doivent être renseignés pour modifier le mot de passe";
    if (empty($_POST['newPwd']) && empty($_POST['newPwd']) && empty($_POST['oldPwd'])) $errorNewPwd = "";

    // PARTIE VERIF POUR LES CHAMPS DE MODIF NOM ET PRENOM
    if ($_POST['newName'] == $nameUser) $erreurNewName = "Le nom doit être différent";
    if ($_POST['newFirstName'] == $firstNameUser) $erreurNewFirstName = "Le nom doit être différent";

    if (!isset($errorNewPwd)) {

      // PARTIE VERIF POUR MODIF MDP
      $newPwd = $_POST['confirmNewPwd'];
      if (!password_verify($_POST['oldPwd'], $pwdcryptUser)) $errorOldPwd = "Mot de passe incorrecte";
      if (!preg_match('/^(?=.*\d)(?=.*[&\-é_èçà^ù*:!ù#~@°%§+.])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z&\-é_èçà^ù*:!ù#~@°%§+.]{4,50}$/', $newPwd)) $errorNewPwd = "Le nouveau mot de passe doit comporter au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial de à @ é è & ç ù _ ! . + - : # % § ^ * ~ °";
      if ($_POST['newPwd'] != $_POST['confirmNewPwd']) $errorNewPwd = "Les nouveaux mots de passe ne correspondent pas";

      // PARTIE MODIF MDP
      if (!isset($errorNewPwd)) {
        $newPwdCrypt = password_hash($newPwd, PASSWORD_BCRYPT);
        $insertNewPwd = $bdd->prepare('UPDATE member SET password = ? WHERE id = ?');
        $insertNewPwd->execute(array($newPwdCrypt, $_SESSION['id']));
        header("Location: profil.php?id=" . $_SESSION['id']);
      }
    }

    // PARTIE MODIF PRENOM
    if (isset($_POST['newFirstName']) && !empty($_POST['newFirstName']) && !isset($erreurNewFirstName)) {
      $newFirstname = htmlspecialchars($_POST['newFirstName']);
      $insertFirstName = $bdd->prepare('UPDATE member SET firstname = ? WHERE id = ?');
      $insertFirstName->execute(array($newFirstname, $_SESSION['id']));
      header("Location: profil.php?id=" . $_SESSION['id']);
    }

    // PARTIE MODIF NOM
    if (isset($_POST['newName']) && !empty($_POST['newName']) && !isset($erreurNewName)) {
      $newName = htmlspecialchars($_POST['newName']);
      $insertName = $bdd->prepare('UPDATE member SET name = ? WHERE id = ?');
      $insertName->execute(array($newName, $_SESSION['id']));
      header("Location: profil.php?id=" . $_SESSION['id']);
    }
  }

  require "Structure/Head/head.php"; // intègre le head
?>
  <link href="Design/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="Design/css/profil.css" rel="stylesheet" type="text/css">
  </head>

  <?php require "Structure/Sidebar/sidebar.php"; // intègre la sidebar
  ?>
  <li class="profile">
    <div class="profile-details">
      <img src="Design/picture/smile.svg" alt="profileImg">
      <div class="name_job">
        <div class="name"><?php echo ($nameUser . " " . $firstNameUser); ?></div>
        <div class="job"><?php echo ($roleUser); ?></div>
      </div>
    </div>
    <a class="nobackground" href="logout.php"><i class='bx bx-log-out' id="log_out"></i></a>
  </li>
  </ul>
  </div>

  <section class="home-section">

    <div class="text">
      <?php echo $pageTitle ?>
    </div>

    <div class="container">
      <div class="row">
        <div class="col">
          <div class="login-form">
            <form method="post">

              <h5>Modifier les informations personnelles</h5>
              <div class="form-group">
                <div class="row">
                  <div class="col">
                    <p class="info">Prénom :</p>
                    <input type="text" id="newFirstName" name="newFirstName" class="form-control" placeholder="<?php echo $firstNameUser ?>">
                    <p class="errorrr">
                      <?php if (isset($erreurNewFirstName)) echo $erreurNewFirstName; ?>
                    </p>
                  </div>
                  <div class="col">
                    <p class="info">Nom :</p>
                    <input type="text" id="newName" name="newName" class="form-control" placeholder="<?php echo $nameUser ?>">
                    <p class="errorrr">
                      <?php if (isset($erreurNewName)) echo $erreurNewName; ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <p class="info">Mail :</p>
                <input type="mail" id="mailU" name="mailU" class="form-control" placeholder="<?php echo $mailUser ?>" disabled="disabled">
              </div>

              <hr>

              <h5>Modifier le mot de passe</h5>
              <div class="form-group">
                <input type="password" id="oldPwd" name="oldPwd" class="form-control" placeholder="Mot de passe actuel">
                <p class="errorrr">
                  <?php if (isset($errorOldPwd)) echo $errorOldPwd; ?>
                </p>
              </div>
              <div class="form-group">
                <input type="password" id="newPwd" name="newPwd" class="form-control" placeholder="Nouveau mot de passe">
              </div>
              <div class="form-group">
                <input type="password" id="confirmNewPwd" name="confirmNewPwd" class="form-control" placeholder="Confirmer le nouveau mot de passe">
              </div>
              <p class="errorrr">
                <?php if (isset($errorNewPwd)) echo $errorNewPwd; ?>
              </p>

              <hr>

              <div class="form-group">
                <input type="submit" class="btn btn-info" name="formChange" value="Sauvegarder">
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </section>

  <script src="Structure/Sidebar/sidebar.js"></script>
  </body>

  </html>

<?php
} else {
  header("Location: index.php");
}
?>