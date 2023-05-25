<?php
session_start();
$pageTitle = "403 Forbidden";
?>

<!DOCTYPE html>
<html lang="fr">

<?php
require "Structure/Head/head.php"; //intègre le head
?>
<link href="Design/css/erreurs.css" rel="stylesheet" type="text/css">
</head>

<body>
    <center>
        <img src="Design/picture/logo2.png">
        <h1>Erreur 403</h1>
        <h4>Vous n'avez pas les droits d'accès nécessaires.</h4>
        <a class="btn btn-info" href="javascript:history.go(-1)">Retour</a>
    </center>
</body>