<?php
session_start();
$pageTitle = "500 Internal Server Error";
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
        <h1>Erreur 500</h1>
        <h4>Le serveur a rencontré une situation qu'il ne sait pas traiter.</h4>
        <a class="btn btn-info" href="javascript:history.go(-1)">Retour</a>
    </center>
</body>