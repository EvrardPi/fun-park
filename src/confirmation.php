<?php
$pageTitle = "Confirmation";
require "Structure/Bdd/config.php"; // intègre la base de données

if (isset($_GET['mail'], $_GET['mail'])) {

    $getMail = htmlspecialchars(urldecode($_GET['mail']));
    $key = htmlspecialchars($_GET['key']);

    $reqConfirm = $bdd->prepare("SELECT * FROM member WHERE mail = ? AND confirmkey = ?");
    $reqConfirm->execute(array($getMail, $key));
    $userExist = $reqConfirm->rowCount();
    $user = $reqConfirm->fetch();

    if ($userExist < 1) $messageRe = "Compte inexistant";
    if ($userExist > 1) $messageDM = "Erreur #DUPLIMAIL <br><br> Veuillez contacter funpark91@gmail.com";
    if (isset($user['confirm'])) $messageCo = "Votre compte a déjà été confirmé !";

    if (empty($messageCo) && empty($messageRe)) {
        $updateUser = $bdd->prepare("UPDATE member SET confirm = 1 WHERE mail = ? AND confirmkey = ?");
        $updateUser->execute(array($getMail, $key));
        $messageCo = "Votre compte a bien été confirmé !";
    }
}
?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fun Park, le plus fun des parcs d'attractions">
    <title><?php echo $pageTitle . " | Fun Park" ?></title>
    <link rel="icon" type="image/svg" href="Design/picture/logo2.png">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <img style="display:block; margin:auto; width:15%; height:auto;" src="Design/picture/logo2.png">

    <?php if (isset($messageCo) && !isset($messageDM)) { ?>
        <div style="text-align:center; margin-top:5%;">
            <h2><?php echo $messageCo; ?></h2>
            <div>
                <a style=margin-top:5%; type="button" class="btn btn-info" href="index.php">Se connecter à Fun Park</a>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($messageRe)) { ?>
        <div style="text-align:center; margin-top:5%;">
            <h2><?php echo $messageRe; ?></h2>
            <div>
                <a style=margin-top:5% type="button" class="btn btn-info" href="register.php">S'inscrire à Fun Park</a>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($messageDM)) { ?>
        <div style="text-align:center; margin-top:5%;">
            <h2><?php echo $messageDM; ?></h2>
            <div>
                <a style=margin-top:5% type="button" class="btn btn-info" href="mailto:funpark91@gmail.com?subject=HTML link">Cliquez ici pour nous envoyer un e-mail !</a>
            </div>
        </div>
    <?php } ?>

</body>

</html>